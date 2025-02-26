<?php

namespace App\Http\Controllers;

use App\Models\course_regular;
use App\Models\course_regular_user;
use App\Models\courses;
use App\Models\rooms;
use App\Models\Shop_article;
use App\Models\Shop_category;
use App\Models\shop_article_1;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\Basket;
use App\Models\shop_article_2;
use App\Models\BankAccount;
use App\Models\Declinaison;

require_once(app_path() . '/fonction.php');


class A_Controller_categorie extends Controller
{
    //
    //---------------------------------------------------------------------Backoffice method pour le shop ----------------'

    public function index()
    {
        // $a_requete = Shop_category::get();

        //  $info = Shop_category::where('id_shop_category_parent','=','0')->orderBy('order_category', 'ASC')->get();
        $info = [
            'categories' =>  Shop_category::where('id_shop_category_parent', '=>', '9')->orderBy('order_category', 'ASC')->get(),
        ];

        $shop_category =   Shop_category::get();

        MiseAjourStock();

        return view('A_Categorie', $info, compact('shop_category'))->with('user', auth()->user());
    }

    public function payment_selection()
    {
        $bank_accounts = BankAccount::all();
        $selected_bank_id = SystemSetting::where('name', 'selected_bank_id')->value('value');
        return view('payment_selection', compact('bank_accounts', 'selected_bank_id'));
    }

    public function processPayment(Request $request)
    {
        SystemSetting::where('name', 'selected_bank_id')->update(['value' => $request->bank_id]);
        return redirect()->back()->with('success', 'La banque sélectionnée a été mise à jour.');
    }

    public function mode_strict()
    {
        return view('A_Categorie_strict');
    }

    //boutton Payer
    public function Passer_au_paiement($id, Request $request)
    {
        $shop = Shop_article::where('id_shop_article', $id)->firstOrFail();

        if ($shop->type_article == 2) {
            $quantite = $request->qte;

            $declinaisonArticle  = Declinaison::where('shop_article_id', '=', $id)
                ->where('id', '=', $request->declinaison)->first();

            if ($quantite > $declinaisonArticle->stock_actuel_d) {
                return redirect()->back()->with('error', "Le stock pour cette taille est insuffisant, il reste {$declinaisonArticle->stock_actuel_d} exemplaires");
            }
        }

        if ($request->qte <= 0) {
            return redirect()->back()->with('error', "Vous ne pouvez pas commander 0 ou moins d'articles");
        }

        //step 1 : Mise à jour de stock de l'article
        MiseAjourArticle($shop);

        $id_article = $shop->id_shop_article;
        $need_member = $shop->need_member;
        $selected_user_id = $request->selected_user_id;
        $quantite = $request->qte;

        if ($request->qte == null) {
            $quantite = 1;
        }
        $declinaison = $request->declinaison;

        // Vérifiez le stock et les conditions d'achat
        if (verifierStockUnArticle($shop, $quantite) && countArticle($selected_user_id, $id_article) < $shop->max_per_user && canAddMoreOfArticle($selected_user_id, $shop)) {

            $panier = Basket::where([
                ['user_id', auth()->user()->user_id],
                ['pour_user_id', $selected_user_id],
                ['ref', $id_article],
                ['declinaison', $declinaison],
            ])->first();

            // Si l'article est déjà dans le panier, augmentez la quantité pour les articles de type 2 uniquement
            if ($panier) {
                if ($shop->type_article == 2) {
                    $panier->qte = $quantite;
                    $panier->qte += $quantite;
                    $panier->save();
                } else {
                    return redirect()->back()->with('error', 'Cet article est déjà dans votre panier');
                }
            } else {
                $addcommand = new Basket();
                $addcommand->user_id = auth()->user()->user_id;
                $addcommand->family_id = auth()->user()->family_id;
                $addcommand->pour_user_id = $request->selected_user_id;
                $addcommand->ref = $shop->id_shop_article;
                $addcommand->qte = $quantite;
                $addcommand->prix = $shop->price;  // Use the original price
                $addcommand->declinaison = $declinaison;
                $addcommand->save();
            }

            // Add the total reduction line
            if (!hasExistingReduction(auth()->user()->user_id, $selected_user_id, $shop->id_shop_article)) {
                $totalReductionValue = getReducedPrice($shop->id_shop_article, $shop->price, $selected_user_id) * (-1);
                $reductionLine = Basket::where([
                    ['user_id', auth()->user()->user_id],
                    ['ref', -1],
                ])->first();

                if ($reductionLine) {
                    $reductionLine->prix += $totalReductionValue;
                    $reductionLine->save();
                } else {
                    $reductionItem = new Basket();
                    $reductionItem->user_id = auth()->user()->user_id;
                    $reductionItem->family_id = auth()->user()->family_id;
                    $reductionItem->pour_user_id = $request->selected_user_id;
                    $reductionItem->ref = -1;
                    $reductionItem->qte = 1;
                    $reductionItem->prix = $totalReductionValue;
                    $reductionItem->save();
                }
            }
            if ($need_member != 0) {
                $result = MiseAuPanier($selected_user_id, $id_article);
                if ($result == 0) {
                    return redirect()->route('basket');
                } elseif ($result == $need_member) {

                    $shop1 = Shop_article::where('id_shop_article', $need_member)->firstOrFail();
                    $currentPrice = getReducedPrice($shop1->id_shop_article, $shop1->price, $selected_user_id);

                    // Check if there is already a type 0 article in the cart
                    $existingBasketItem = Basket::join('shop_article', 'basket.ref', '=', 'shop_article.id_shop_article')
                        ->where('basket.pour_user_id', $selected_user_id)
                        ->where('shop_article.type_article', 0)
                        ->select('basket.*') // Select basket fields
                        ->first();

                    if ($existingBasketItem) {
                        // If the existing article is the same, do not add
                        if ($existingBasketItem->ref == $need_member) {
                            return redirect()->route('basket');
                        }

                        // If the existing article is different, only add if the new article is more expensive
                        $existingArticle = Shop_article::where('id_shop_article', $existingBasketItem->ref)->first();
                        if ($currentPrice > $existingArticle->price) {
                            $existingBasketItem->delete();  // Remove the cheaper article
                        } else {
                            // Do not add the new article
                            return redirect()->route('basket');
                        }
                    }


                    // Now we can add the new article
                    $addcommand = new Basket();
                    $addcommand->user_id = auth()->user()->user_id;
                    $addcommand->family_id = auth()->user()->family_id;
                    $addcommand->pour_user_id = $request->selected_user_id;
                    $addcommand->ref = $need_member;
                    $addcommand->qte = 1;
                    $addcommand->prix =  $shop1->price;
                    if ($addcommand->prix < $shop1->price) {
                        $description = getFirstReductionDescription($shop1->id_shop_article, $selected_user_id);
                        $addcommand->reduction = $description;
                    }
                    $addcommand->save();

                    $totalReductionValue = getReducedPrice($shop1->id_shop_article, $shop1->price, $selected_user_id) * (-1);
                    $reductionLine = Basket::where([
                        ['user_id', auth()->user()->user_id],
                        ['ref', -1],
                    ])->first();

                    if ($reductionLine) {
                        $reductionLine->prix += $totalReductionValue; // Add the reduction
                        $reductionLine->save();
                    } else {
                        $reductionItem = new Basket();
                        $reductionItem->user_id = auth()->user()->user_id;
                        $reductionItem->family_id = auth()->user()->family_id;
                        $reductionItem->pour_user_id = $request->selected_user_id;
                        $reductionItem->ref = -1; // Special reference for total reduction
                        $reductionItem->qte = 1;
                        $reductionItem->prix = $totalReductionValue;
                        $reductionItem->save();
                    }

                    applyFamilyDiscount($shop1, $selected_user_id);
                    return redirect()->route('basket');
                } elseif (is_numeric($result)) {
                    // Si le résultat est diff_price
                    //ajouter l'article avec le prix réduit au panier

                    $shop1 = Shop_article::where('id_shop_article', $need_member)->firstOrFail();
                    $currentPrice = $result; // Utilisez diff_price comme le prix actuel

                    // Check if there is already a type 0 article in the cart
                    $existingBasketItem = Basket::join('shop_article', 'basket.ref', '=', 'shop_article.id_shop_article')
                        ->where('basket.pour_user_id', $selected_user_id)
                        ->where('basket.prix', $currentPrice)
                        ->where('shop_article.type_article', 0)
                        ->select('basket.*') // Select basket fields
                        ->first();

                    if ($existingBasketItem) {
                        // If the existing article is the same, do not add
                        if ($existingBasketItem->ref == $need_member) {
                            return redirect()->route('basket');
                        }

                        // If the existing article is different, only add if the new article is more expensive
                        $existingArticle = Shop_article::where('id_shop_article', $existingBasketItem->ref)->first();
                        if ($currentPrice > $existingArticle->price) {
                            $existingBasketItem->delete();  // Remove the cheaper article
                        } else {
                            // Do not add the new article
                            return redirect()->route('basket');
                        }
                    }


                    // Now we can add the new article
                    $addcommand = new Basket();
                    $addcommand->user_id = auth()->user()->user_id;
                    $addcommand->family_id = auth()->user()->family_id;
                    $addcommand->pour_user_id = $request->selected_user_id;
                    $addcommand->ref = $need_member;
                    $addcommand->qte = 1;
                    $addcommand->prix =  $shop1->price;
                    if ($addcommand->prix < $shop1->price) {
                        $description = getFirstReductionDescription($shop1->id_shop_article, $selected_user_id);
                        $addcommand->reduction = $description;
                    }
                    $addcommand->save();

                    $totalReductionValue = getReducedPrice($shop1->id_shop_article, $shop1->price, $selected_user_id) * (-1);

                    $reductionLine = Basket::where([
                        ['user_id', auth()->user()->user_id],
                        ['ref', -1], // Using -1 as a special reference for total reduction
                    ])->first();

                    if ($reductionLine) {
                        $reductionLine->prix += $totalReductionValue; // Add the reduction
                        $reductionLine->save();
                    } else {
                        $reductionItem = new Basket();
                        $reductionItem->user_id = auth()->user()->user_id;
                        $reductionItem->family_id = auth()->user()->family_id;
                        $reductionItem->pour_user_id = $request->selected_user_id;
                        $reductionItem->ref = -1; // Special reference for total reduction
                        $reductionItem->qte = 1;
                        $reductionItem->prix = $totalReductionValue;
                        $reductionItem->save();
                    }

                    applyFamilyDiscount($shop1, $selected_user_id);
                    return redirect()->route('basket');
                }
            } else {
                return redirect()->route('basket');
            }
        } else {
            if (!verifierStockUnArticle($shop, $quantite)) {
                return redirect()->back()->with('error', 'Le stock est insuffisant.');
            } else if (!(countArticle($selected_user_id, $id_article) < $shop->max_per_user)) {
                return redirect()->back()->with('error', 'Vous possédez déjà cet article/cours, vous ne pouvez pas en avoir plusieurs.');
            } else if (!canAddMoreOfArticle($selected_user_id, $shop)) {
                return redirect()->back()->with('error', 'Vous ne pouvez pas avoir plusieurs fois cet article.');
            } else {
                return redirect()->back()->with('error', 'Une erreur inattendue est survenue, veuillez nous contacter');
            }
        }
    }

    //boutton commander
    public function commander_article($id, Request $request)
    {
        $shop = Shop_article::where('id_shop_article', $id)->firstOrFail();

        if ($shop->type_article == 2) {
            $quantite = $request->qte;

            $declinaisonArticle  = Declinaison::where('shop_article_id', '=', $id)
                ->where('id', '=', $request->declinaison)->first();

            if ($quantite > $declinaisonArticle->stock_actuel_d) {
                return redirect()->back()->with('error', "Le stock pour cette taille est insuffisant, il reste {$declinaisonArticle->stock_actuel_d} exemplaires");
            }
        }

        if ($request->qte <= 0) {
            return redirect()->back()->with('error', "Vous ne pouvez pas commander 0 ou moins d'articles");
        }

        //step 1 : Mise à jour de stock de l'article
        MiseAjourArticle($shop);

        $id_article = $shop->id_shop_article;
        $need_member = $shop->need_member;
        $selected_user_id = $request->selected_user_id;
        $quantite = $request->qte;
        if ($request->qte == null) {
            $quantite = 1;
        }
        $declinaison = $request->declinaison;

        // Vérifiez le stock et les conditions d'achat
        if (verifierStockUnArticle($shop, $quantite) && countArticle($selected_user_id, $id_article) < $shop->max_per_user && canAddMoreOfArticle($selected_user_id, $shop)) {

            $panier = Basket::where([
                ['user_id', auth()->user()->user_id],
                ['pour_user_id', $selected_user_id],
                ['ref', $id_article],
                ['declinaison', $declinaison],
            ])->first();

            if ($panier) {
                if ($shop->type_article == 2) {
                    $panier->qte = $quantite;
                    $panier->qte += $quantite;
                    $panier->save();
                } else {
                    return redirect()->back()->with('error', 'Cet article est déjà dans votre panier');
                }
            } else {
                $addcommand = new Basket();
                $addcommand->user_id = auth()->user()->user_id;
                $addcommand->family_id = auth()->user()->family_id;
                $addcommand->pour_user_id = $request->selected_user_id;
                $addcommand->ref = $shop->id_shop_article;
                $addcommand->qte = $quantite;
                $addcommand->prix = $shop->price;  // Use the original price
                $addcommand->declinaison = $declinaison;
                $addcommand->save();
            }
            if (!hasExistingReduction(auth()->user()->user_id, $selected_user_id, $shop->id_shop_article)) {

                $totalReductionValue = getReducedPrice($shop->id_shop_article, $shop->price, $selected_user_id) * (-1);
                $reductionLine = Basket::where([
                    ['user_id', auth()->user()->user_id],
                    ['ref', -1], // Using -1 as a special reference for total reduction
                ])->first();

                if ($reductionLine) {
                    $reductionLine->prix += $totalReductionValue; // Add the reduction
                    $reductionLine->save();
                } else {
                    $reductionItem = new Basket();
                    $reductionItem->user_id = auth()->user()->user_id;
                    $reductionItem->family_id = auth()->user()->family_id;
                    $reductionItem->pour_user_id = $request->selected_user_id;
                    $reductionItem->ref = -1; // Special reference for total reduction
                    $reductionItem->qte = 1;
                    $reductionItem->prix = $totalReductionValue;
                    $reductionItem->save();
                }
            }


            if ($need_member != 0) {

                $result = MiseAuPanier($selected_user_id, $id_article);
                if ($result == 0) {
                    return redirect()->back()->with('success', 'Article ajouté au panier');
                } elseif ($result == $need_member) {
                    $shop1 = Shop_article::where('id_shop_article', $need_member)->firstOrFail();
                    // Check if there is already a type 0 article in the cart
                    $existingBasketItem = Basket::join('shop_article', 'basket.ref', '=', 'shop_article.id_shop_article')
                        ->where('basket.pour_user_id', $selected_user_id)
                        ->where('shop_article.type_article', 0)
                        ->select('basket.*') // Select basket fields
                        ->first();
                    if ($existingBasketItem) {
                        // If the existing article is the same, do not add
                        if ($existingBasketItem->ref == $need_member) {
                            return redirect()->back()->with('success', 'Article ajouté au panier');
                        }

                        // If the existing article is different, only add if the new article is more expensive
                        $existingArticle = Shop_article::where('id_shop_article', $existingBasketItem->ref)->first();
                        if ($currentPrice > $existingArticle->price) {
                            $existingBasketItem->delete();  // Remove the cheaper article
                        } else {
                            // Do not add the new article
                            return redirect()->back()->with('success', 'Article ajouté au panier');
                        }
                    }

                    // Now we can add the new article
                    $addcommand = new Basket();
                    $addcommand->user_id = auth()->user()->user_id;
                    $addcommand->family_id = auth()->user()->family_id;
                    $addcommand->pour_user_id = $request->selected_user_id;
                    $addcommand->ref = $need_member;
                    $addcommand->qte = 1;
                    $addcommand->prix =  $shop1->price;
                    if ($addcommand->prix < $shop1->price) {
                        $description = getFirstReductionDescription($shop1->id_shop_article, $selected_user_id);
                        $addcommand->reduction = $description;
                    }
                    $addcommand->save();

                    $totalReductionValue = getReducedPrice($shop1->id_shop_article, $shop1->price, $selected_user_id) * (-1);

                    $reductionLine = Basket::where([
                        ['user_id', auth()->user()->user_id],
                        ['ref', -1], // Using -1 as a special reference for total reduction
                    ])->first();

                    if ($reductionLine) {
                        $reductionLine->prix += $totalReductionValue; // Add the reduction
                        $reductionLine->save();
                    } else {
                        $reductionItem = new Basket();
                        $reductionItem->user_id = auth()->user()->user_id;
                        $reductionItem->family_id = auth()->user()->family_id;
                        $reductionItem->pour_user_id = $request->selected_user_id;
                        $reductionItem->ref = -1; // Special reference for total reduction
                        $reductionItem->qte = 1;
                        $reductionItem->prix = $totalReductionValue;
                        $reductionItem->save();
                    }

                    applyFamilyDiscount($shop1, $selected_user_id);
                    return redirect()->back()->with('success', 'Article ajouté au panier');
                }
            }

            return redirect()->back()->with('success', 'Article ajouté au panier');
        } else {
            if (!verifierStockUnArticle($shop, $quantite)) {
                return redirect()->back()->with('error', 'Le stock est insuffisant.');
            } else if (!(countArticle($selected_user_id, $id_article) < $shop->max_per_user)) {
                return redirect()->back()->with('error', 'Vous possédez déjà cet article/cours, vous ne pouvez pas en avoir plusieurs.');
            } else if (!canAddMoreOfArticle($selected_user_id, $shop)) {
                return redirect()->back()->with('error', 'Vous ne pouvez pas avoir plusieurs fois cet article.');
            } else {
                return redirect()->back()->with('error', 'Une erreur inattendue est survenue, veuillez nous contacter');
            }
        }
    }



    public function commanderModal($shop_id, $user_id, Request $request)
    {
        MiseAjourStock();
        $declinaison = $request->input('declinaison');
        $qte = $request->input('qte');
        $selected_user = $user_id;
        $shop = Shop_article::where('id_shop_article', $shop_id)->firstOrFail();
        $needMember = $shop->need_member != 0;

        return view('Articles.modal.commanderModal', compact('shop', 'user_id', 'qte', 'declinaison', 'needMember'));
    }


    public function saveNestedCategories(Request $request)
    {

        $json = $request->nested_category_array;
        dd($json);
        $decoded_json = json_decode($json, TRUE);

        $simplified_list = [];
        $this->recur1($decoded_json, $simplified_list);

        // -------- here the issue ------------



        foreach ($simplified_list as $k => $v) {
            Shop_category::where('id_shop_category', '=', $v['id_shop_category'])->update([

                "id_shop_category_parent" => $v['id_shop_category_parent'],
                "order_category" => $v['order_category']
            ]);
        }






        return redirect(route('A_Categorie'));
        //print_r($simplified_list);
    }



    public function recur1($nested_array = [], &$simplified_list = [])
    {

        static $counter = 0;

        foreach ($nested_array as $k => $v) {

            $order_category = $k + 1;
            $simplified_list[] = [
                "id_shop_category" => $v['id'],
                "id_shop_category_parent" => 0,
                "order_category" =>  $order_category
            ];

            if (!empty($v["children"])) {
                $counter += 1;
                $this->recur2($v['children'], $simplified_list, $v['id']);
            }
        }
    }


    public function recur2($sub_nested_array = [], &$simplified_list = [], $parent_id = NULL)
    {

        static $counter = 0;

        foreach ($sub_nested_array as $k => $v) {

            $order_category = $k + 1;
            $simplified_list[] = [
                "id_shop_category" => $v['id'],
                "id_shop_category_parent" => $parent_id,
                "order_category" => $order_category
            ];

            if (!empty($v["children"])) {
                $counter += 1;
                return $this->recur2($v['children'], $simplified_list, $v['id']);
            }
        }
    }

    public function create(Request $request)
    {

        $this->validate($request, [
            'nom' => 'required|max:255|string',
            'image' => 'required|max:255',
            'editor1' => 'required'

        ]);

        $category  = new Shop_category;
        $category->name = $request->input('nom');
        $category->image = $request->input('image');
        $category->description = $request->input('editor1');

        if ($request->input('action') == "new_cat") {

            // Level 1 new category: Generate the next 1-digit ID
            $lastChildId = Shop_category::select('id_shop_category ')->where("id_shop_category_parent", "=", 0)->max('id_shop_category');
            $lastChildId = $lastChildId + 1;

            if ($lastChildId == 100) //mean we reache the max of id for the childs level 0 
            {
                $existID = true;
                $lastChildId = 1; // we begin from the first child id like 110 or 210...
                while ($existID) // we test if we have the same id with other category 
                {
                    $islastChildIdExist = Shop_category::select('id_shop_category')->where("id_shop_category", "=", $lastChildId)->max('id_shop_category');
                    if ($islastChildIdExist != null)
                        $lastChildId = $lastChildId + 1;
                    else
                        $existID = false; // we get an new id 
                }
            }
            $category->id_shop_category = $lastChildId;
            $category->id_shop_category_parent = 0;
            $category->order_category = $lastChildId;

            if ($request->input('active') == 1)
                $category->active = $request->input('active');
            else
                $category->active = 0;
        } else {

            $requete_order = Shop_category::select('order_category')->where("id_shop_category", "=", " $request->input('action') ")->get();
            $parentIdLength = strlen((string) $request->input('action'));

            if ($parentIdLength < 3) {
                $lastChildId = Shop_category::select('id_shop_category')->where("id_shop_category_parent", "=", $request->input('action'))->max('id_shop_category');

                if ($lastChildId != null) {
                    $lastChildId = $lastChildId + 1;

                    if ($lastChildId == ($request->input('action') + 1) * 100) //mean we reache the max of id for the childs 
                    {
                        $existID = true;
                        $lastChildId = $request->input('action') * 100 + 1; // we begin from the first child id like 110 or 210...
                        while ($existID) // we test if we have the same id with other category 
                        {
                            $islastChildIdExist = Shop_category::select('id_shop_category ')->where("id_shop_category", "=", $lastChildId)->max('id_shop_category');
                            if ($islastChildIdExist != null)
                                $lastChildId = $lastChildId + 1;
                            else
                                $existID = false; // we get an new id 
                        }
                    }
                } else
                    // no child for the parent so we create new one 
                    $lastChildId = $request->input('action') * 100 + 1;
            } elseif ($parentIdLength < 5) {
                $lastChildId = Shop_category::select('id_shop_category')->where("id_shop_category_parent", "=", $request->input('action'))->max('id_shop_category');

                if ($lastChildId != null) {

                    $lastChildId = (int)substr($lastChildId, 3) + 1;
                    $lastChildId = (int)($request->input('action') . $lastChildId);

                    if ($lastChildId == ($request->input('action') + 1) * 100) //mean we reache the max of id for the childs 
                    {
                        $existID = true;
                        $lastChildId = $request->input('action') * 100 + 1; // we begin from the first child id like 1251 or 1252...
                        while ($existID) // we test if we have the same id with other category 
                        {
                            $islastChildIdExist = Shop_category::select('id_shop_category ')->where("id_shop_category", "=", $lastChildId)->max('id_shop_category');
                            if ($islastChildIdExist != null)
                                $lastChildId = $lastChildId + 1;
                            else
                                $existID = false; // we get an new id 
                        }
                    }
                } else
                    // no child for the parent so we create new one 
                    $lastChildId = $request->input('action') * 100 + 1;
            } elseif ($parentIdLength < 7) {
                $lastChildId = Shop_category::select('id_shop_category')->where("id_shop_category_parent", "=", $request->input('action'))->max('id_shop_category');

                if ($lastChildId != null) {
                    $lastChildId = (int)substr($lastChildId, 5) + 1;
                    $lastChildId = (int)($request->input('action') . $lastChildId);

                    if ($lastChildId == ($request->input('action') + 1) * 100) //mean we reache the max of id for the childs 
                    {
                        $existID = true;
                        $lastChildId = $request->input('action') * 100 + 1; // we begin from the first child id like 1251 or 1252...
                        while ($existID) // we test if we have the same id with other category 
                        {
                            $islastChildIdExist = Shop_category::select('id_shop_category ')->where("id_shop_category", "=", $lastChildId)->max('id_shop_category');
                            if ($islastChildIdExist != null)
                                $lastChildId = $lastChildId + 1;
                            else
                                $existID = false; // we get an new id 
                        }
                    }
                } else
                    // no child for the parent so we create new one 
                    $lastChildId = $request->input('action') * 100 + 1;
            }

            $category->id_shop_category = $lastChildId;
            $category->id_shop_category_parent = (int)$request->input('action');
            $category->order_category = $lastChildId;
            if ($request->input('active') == 1)
                $category->active = $request->input('active');
            else
                $category->active = 0;
        }


        $category->save();
        return redirect(route('A_Categorie'));
    }

    public function remove(Request $request, $id)
    {

        Shop_category::where('id_shop_category', $id)->delete();

        return redirect(route('A_Categorie'))->with('success', "Categorie supprimée.");
    }


    public function edit_index(Request $request, $id)
    {

        //'categories' => Shop_category::orderBy('name', 'ASC')->get(),
        $info = Shop_category::where('id_shop_category', $id)->first();
        $shop_article = Shop_article::get();
        $Allshop_category =   Shop_category::get();
        $parrent_Category = Shop_category::where('id_shop_category', $info->id_shop_category_parent)->first();

        return view('Category_modify', compact('shop_article', 'info', 'Allshop_category', 'parrent_Category'))->with('user', auth()->user());
    }

    // save the edited Category 
    public function saveEditedCategory(Request $request)
    {

        $this->validate($request, [
            'id' => 'required|numeric',
            'nom' => 'required|max:255|string',
            'image' => 'required|max:255',
            'editor1' => 'required'

        ]);
        $category = Shop_category::where('id_shop_category', $request->input('id'))->first();

        if ($request->input('action') == "new_cat") {

            $lastChildId = Shop_category::select('id_shop_category')->where("id_shop_category_parent", "=", 0)->max('id_shop_category');

            if ($lastChildId == 100) //mean we reache the max of id for the childs level 0 
            {
                $existID = true;
                $lastChildId = 1; // we begin from the first child id like 110 or 210...
                while ($existID) // we test if we have the same id with other category 
                {
                    $islastChildIdExist = Shop_category::select('id_shop_category')->where("id_shop_category", "=", $lastChildId)->max('id_shop_category');
                    if ($islastChildIdExist != null)
                        $lastChildId = $lastChildId + 1;
                    else
                        $existID = false; // we get an new id 
                }
            }

            $category->id_shop_category = $lastChildId;
            $category->id_shop_category_parent = 0;
            $category->order_category = $lastChildId;

            if ($request->input('active') == 1)
                $category->active = $request->input('active');
            else
                $category->active = 0;
        } else {
            $category->name = $request->input('nom');
            $category->image = $request->input('image');
            $category->description = $request->input('editor1');

            $parentIdLength = strlen((string) $request->input('action'));

            if ($parentIdLength < 3) {
                $lastChildId = Shop_category::select('id_shop_category')->where("id_shop_category_parent", "=", $request->input('action'))->max('id_shop_category');

                if ($lastChildId != null) {
                    $lastChildId = $lastChildId + 1;

                    if ($lastChildId == ($request->input('action') + 1) * 100) //mean we reache the max of id for the childs 
                    {
                        $existID = true;
                        $lastChildId = $request->input('action') * 100 + 1; // we begin from the first child id like 110 or 210...
                        while ($existID) // we test if we have the same id with other category 
                        {
                            $islastChildIdExist = Shop_category::select('id_shop_category')->where("id_shop_category", "=", $lastChildId)->max('id_shop_category');
                            if ($islastChildIdExist != null)
                                $lastChildId = $lastChildId + 1;
                            else
                                $existID = false; // we get an new id 
                        }
                    }
                } else
                    // no child for the parent so we create new one 
                    $lastChildId = $request->input('action') * 100 + 1;
            }

            if ($parentIdLength < 5) {
                $lastChildId = Shop_category::select('id_shop_category')->where("id_shop_category_parent", "=", $request->input('action'))->max('id_shop_category');

                if ($lastChildId != null) {
                    $lastChildId = (int)substr($lastChildId, 3) + 1;
                    $lastChildId = (int)($request->input('action') . $lastChildId);

                    if ($lastChildId == ($request->input('action') + 1) * 100) //mean we reache the max of id for the childs 
                    {
                        $existID = true;
                        $lastChildId = $request->input('action') * 100 + 1; // we begin from the first child id like 1251 or 1252...
                        while ($existID) // we test if we have the same id with other category 
                        {
                            $islastChildIdExist = Shop_category::select('id_shop_category')->where("id_shop_category", "=", $lastChildId)->max('id_shop_category');
                            if ($islastChildIdExist != null)
                                $lastChildId = $lastChildId + 1;
                            else
                                $existID = false; // we get an new id 
                        }
                    }
                } else
                    // no child for the parent so we create new one 
                    $lastChildId = $request->input('action') * 100 + 1;
            }
            if ($parentIdLength < 7) {
                $lastChildId = Shop_category::select('id_shop_category')->where("id_shop_category_parent", "=", $request->input('action'))->max('id_shop_category');

                if ($lastChildId != null) {
                    $lastChildId = (int)substr($lastChildId, 3) + 1;
                    $lastChildId = (int)($request->input('action') . $lastChildId);

                    if ($lastChildId == ($request->input('action') + 1) * 100) //mean we reache the max of id for the childs 
                    {
                        $existID = true;
                        $lastChildId = $request->input('action') * 100 + 1; // we begin from the first child id like 1251 or 1252...
                        while ($existID) // we test if we have the same id with other category 
                        {
                            $islastChildIdExist = Shop_category::select('id_shop_category')->where("id_shop_category", "=", $lastChildId)->max('id_shop_category');
                            if ($islastChildIdExist != null)
                                $lastChildId = $lastChildId + 1;
                            else
                                $existID = false; // we get an new id 
                        }
                    }
                } else
                    // no child for the parent so we create new one 
                    $lastChildId = $request->input('action') * 100 + 1;
            }

            $category->id_shop_category = $lastChildId;
            $category->id_shop_category_parent = (int)$request->input('action');
            $category->order_category = $lastChildId;

            if ($request->input('active') == 1)
                $category->active = $request->input('active');
            else
                $category->active = 0;
        }


        $category->save();
        return redirect(route('A_Categorie'))->with('success', 'La catégorie a été modifiée avec succes.');
    }

    /*
    public function store(Request $request){
        $rules=[
            'name' => 'required',
            'parent_id' => 'nullable',
        ];

        $messages = [
            "name.required" => "Category name is required.",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        
        if( $validator->fails() ){
            return back()->withErrors($validator)->withInput();
        } else {
            DB::beginTransaction();
            try {
                $info = [
                    "success" => FALSE,
                ];
                $query = [
                    'name' => $request->name,
                    'parent_id' => (!empty($request->parent_id))? $request->parent_id : 0,
                ];
    
                $category = CategoryModel::updateOrCreate(['category_id' => $request->category_id], $query);

                DB::commit();
                $info['success'] = TRUE;
            } catch (\Exception $e) {
                DB::rollback();
                $info['success'] = FALSE;
            }

            if(!$info['success']){
                return redirect(route('category-subcategory.create'))->with('error', "Failed to save.");
            }

            return redirect(route('category-subcategory.edit', ['category_id' => $category->category_id]))->with('success', "Successfully saved.");
        }
    }



*/

    //---------------------------------------------------------------------frontoffice method pour le shop -------------------------------------------------------'
    public function MainShop()
    {

        $info = Shop_category::where('id_shop_category', '<=', '9')->orderBy('order_category', 'ASC')->get();


        $systemSetting = SystemSetting::find(3);
        $messageContent = null;

        if ($systemSetting && $systemSetting->value == 1) {
            $messageContent = $systemSetting->Message;
        }

        return view('A_Shop_Categorie_index', compact('info', 'messageContent'))->with('user', auth()->user());
    }



    // Methode qui permet l'affichage du contenu des sous categories
    public function Shop_souscategorie($id)
    {
        MiseAjourStock();

        $indice = $id;
        $info_parent = Shop_category::select('id_shop_category_parent', 'name')->get();
        $info = Shop_category::where('active', 1)->get();
        $info2 = Shop_category::select('name', 'description')->where('id_shop_category', '=', $indice)->first();
        $article = Shop_article::get();
        $shopService = shop_article_1::get();
        $rooms = rooms::get();
        $a_user = User::all();

        $saison_active = saison_active();

        $n_var = DB::table('shop_article')
            ->leftJoin('shop_article_1', function ($join) {
                $join->on('shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')
                    ->where('shop_article.type_article', '=', 1);
            })
            ->join('liaison_shop_articles_shop_categories', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_shop_categories.id_shop_article')
            ->join('shop_category', 'shop_category.id_shop_category', '=', 'liaison_shop_articles_shop_categories.id_shop_category')
            ->select('shop_article.*', 'shop_article_1.lesson', 'shop_category.*', 'shop_article.image as image') // Added 'shop_article_1.lesson'
            ->where('shop_article.saison', '=', $saison_active)
            ->get()
            ->map(function ($item) {
                $lesson = json_decode($item->lesson, true);
                $item->start_date = isset($lesson['start_date'][0]) ? $lesson['start_date'][0] : null;
                return $item;
            })
            ->all();

        usort($n_var, function ($a, $b) {
            return strcmp($a->start_date, $b->start_date);
        });


        $user =  Auth::user();
        $user_id = Auth::id();

        if ($user_id === null || $user->role < 90) {
            $n_var = filterArticlesByValidityDate($n_var);
        }

        if ($user_id === null || $user->role  < 90) {
            $requete = getFilteredArticles($n_var);
        } else {
            $requete = $n_var;
        }


        $systemSetting = SystemSetting::find(3);
        $messageContent = null;

        if ($systemSetting && $systemSetting->value == 1) {
            $messageContent = $systemSetting->Message;
        }

        return view('A_Shop_SousCategorie_index', compact('info', 'info_parent', 'messageContent', 'indice', 'requete', 'info2', 'article', 'shopService', 'rooms', 'a_user'))
            ->with('user', auth()->user())
            ->with('getReducedPriceGuest', 'getReducedPriceGuest', 'getFirstReductionDescriptionGuest', 'getFirstReductionDescriptionGuest');
    }



    public function Handle_details($id)
    {
        MiseAjourStock();
        $articl = Shop_article::where('id_shop_article', $id)->firstOrFail();
        $reducedPrice = getReducedPriceGuest($id, $articl->totalprice);
        $priceToDisplay = $reducedPrice ? $reducedPrice : $articl->totalprice;
        $DescReduc = getFirstReductionDescriptionGuest($id);


        if ($articl->type_article == 2) {
            $déclinaison = shop_article_2::where('id_shop_article', $id)->select('declinaison')->firstOrFail();
            $declinaisons = json_decode($déclinaison->declinaison, true);
        }

        $indice = $id;
        $article = Shop_article::get();
        $shopService =  shop_article_1::get();
        $rooms = rooms::get();
        $a_user = User::get();
        $info =   Shop_category::get();
        $selectedUsers = array();

        $systemSetting = SystemSetting::find(3);
        $messageContent = null;

        if ($systemSetting && $systemSetting->value == 1) {
            $messageContent = $systemSetting->Message;
        }

        $coursVente = SystemSetting::find(5);
        // Convertir la chaîne JSON en tableau PHP
        if (Auth::check()) {
            $selectedUsers = getArticleUsers($articl);
        }
        if ($articl->type_article == 2) {

            return view('Details_article', compact('DescReduc', 'id', 'indice', 'messageContent', 'coursVente', 'article', 'rooms', 'shopService', 'a_user', 'selectedUsers', 'info', 'declinaisons'))->with('user', auth()->user())->with('priceToDisplay', $priceToDisplay);
        }
        return view('Details_article', compact('DescReduc', 'id', 'indice', 'messageContent', 'coursVente', 'article', 'rooms', 'shopService', 'a_user', 'selectedUsers', 'info'))->with('user', auth()->user())->with('priceToDisplay', $priceToDisplay);
    }




    /* methode pour qui creer un JSON  et remplit le champ teacher de la table shop_article_1  */

    public function  JsonProcess()
    {

        $tab1 = array();
        $tab2 = array();
        $tab3 = array();

        $request1 = course_regular::get();
        $request2 = course_regular_user::get();
        $request3 = shop_article_1::get();


        foreach ($request3 as $data3) {

            foreach ($request1 as $data1) {

                if ($data1->id_shop_article == $data3->id_shop_article) {


                    array_push($tab1, $data1->id_user);
                }
            }



            foreach ($request2 as $data2) {

                if ($data2->id_shop_article == $data3->id_shop_article) {


                    array_push($tab2, $data2->id_user);
                }
            }



            print("=================[.$data3->id_shop_article.]==================<br>");
            $tab3 =  array_merge($tab1, $tab2);
            $tab3 = array_unique($tab3);
            print_r($tab3);

            DB::table('shop_article_1')
                ->where('id_shop_article', $data3->id_shop_article)
                ->update(['teacher' => $tab3]);

            $tab1 = array();
            $tab2 = array();
            $tab3 = array();
        }
    }


    /* methode pour qui creer un JSON  a partir des elements de la table Courses (start_date , end_date, id_room) et remplir le champ
    lesson de la table shop_article_1 */

    public function  JsonProcess2()
    {

        $tab1 = array();
        $tab2 = array();
        $tab3 = array();

        $request1 = courses::orderBy('id_shop_article', 'ASC')->get();
        $request3 = shop_article_1::get();


        foreach ($request3 as $data3) {

            foreach ($request1 as $data1) {


                if ($data1->id_shop_article == $data3->id_shop_article) {


                    array_push($tab1, $data1->start_date);
                    array_push($tab2, $data1->end_date);
                    array_push($tab3, $data1->id_room);

                    $json = array(
                        'start_date' => $tab1,
                        'end_date' => $tab2,
                        'room' => $tab3
                    );
                }
            }





            // $json = json_encode($json);
            print_r($json);


            DB::table('shop_article_1')
                ->where('id_shop_article', $data3->id_shop_article)
                ->update(['lesson' => $json]);

            $tab1 = array();
            $tab2 = array();
            $tab3 = array();
        }
    }

    /* methode pour qui creer un JSON  et remplit le champ shop ini de la table shop_article_1 */

    public function  JsonProcess3()
    {

        $tab1 = array();
        $tab2 = array();
        $tab3 = array();

        $request1 = Shop_article::orderBy('id_shop_article', 'ASC')->get();
        $request3 = shop_article_1::get();


        foreach ($request3 as $data3) {

            foreach ($request1 as $data1) {


                if ($data1->id_shop_article == $data3->id_shop_article) {


                    array_push($tab1, $data1->stock_ini);

                    $json = array('stock_ini' => $tab1);
                }
            }



            print("=================[.$data3->id_shop_article.]==================<br>");

            // $json = json_encode($json);
            print_r($json);


            DB::table('shop_article_1')
                ->where('id_shop_article', $data3->id_shop_article)
                ->update(['stock_ini' => $json]);

            $tab1 = array();
        }
    }
}

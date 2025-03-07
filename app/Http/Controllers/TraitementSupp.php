<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Models\A_Blog_Post;
use App\Models\Category;
use App\Models\Shop_article;
use App\Models\Shop_category;
use App\Models\shop_article_1;
use App\Models\shop_article_2;
use App\Models\Room;
use App\Models\User;
use App\Models\SystemSetting;
use App\Models\bills;
use App\Models\old_bills;
use App\Models\BillStatus;
use App\Models\BillPaymentMethod;
use App\Models\liaison_shop_articles_bills;
use App\Models\LiaisonShopArticlesBill;
use App\Models\PaiementImmediat;
use App\Models\Declinaison;
use App\Models\AdditionalCharge;
use App\Models\Carousel;
use PDF;


use DateTime;

require_once(app_path() . '/fonction.php');

use Illuminate\Support\Facades\DB;

class TraitementSupp extends Controller
{
    public function fusionsql()
    {

        $posts = A_Blog_Post::all();

        $blogPosts = A_Blog_Post::all();
        foreach ($blogPosts as $post) {
            $categorie1 = json_decode($post->categorie);
            $categorie2 = json_decode($post->categorie);

            // Merge the two arrays and re-encode to JSON
            $mergedCategories = array_merge($categorie1, $categorie2);
            $post->categorie = json_encode($mergedCategories);

            $post->save();
        }
        dd('ok');
    }

    public function storeAdditionalCharge(Request $request)
    {
        $data = $request->validate([
            'bill_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        $data['family_id'] = bills::find($data['bill_id'])->family_id;

        AdditionalCharge::create($data);

        return redirect()->back()->with('success', 'Additional charge added successfully!');
    }


    public function jsoncorrection()
    {

        $blogPosts = A_Blog_Post::all();
        foreach ($blogPosts as $post) {
            $categorie = json_decode($post->categorie);

            for ($i = 0; $i < count($categorie); $i++) {
                if ($categorie[$i] >= 20 && $categorie[$i] <= 28) {
                    $categorie[$i] += 200;
                }
                if ($categorie[$i] >= 60 && $categorie[$i] <= 85) {
                    $categorie[$i] += 100;
                }
            }

            // Re-encode to JSON and save
            $post->categorie = json_encode($categorie);
            $post->save();
        }

        dd('ok');
    }

    public function fusionnertable()
    {
        $categorie1s = DB::table('categorie1')->get();
        $categorie2s = DB::table('categorie2')->get();

        foreach ($categorie1s as $categorie1) {
            DB::table('categories')->insert([
                'id_categorie' => $categorie1->Id_categorie,
                'nom_categorie' => $categorie1->nom_categorie,
                'description' => $categorie1->description,
                'categorie_URL' => $categorie1->categorie_URL,
                'image' => $categorie1->image,
                'visibilite' => $categorie1->visibilite,
                'created_at' => $categorie1->created_at,
                'updated_at' => $categorie1->updated_at
            ]);
        }

        foreach ($categorie2s as $categorie2) {
            DB::table('categories')->insert([
                'id_categorie' => $categorie2->Id_categorie,
                'nom_categorie' => $categorie2->nom_categorie,
                'description' => $categorie2->description,
                'categorie_URL' => $categorie2->categorie_URL,
                'image' => $categorie2->image,
                'created_at' => $categorie2->created_at,
                'updated_at' => $categorie2->updated_at
            ]);
        }

        dd('ok');
    }


    public function carouselblog()
    {
        $posts = A_Blog_Post::latest()
            ->where('status', '=', 'Publié')
            ->where('date_post', '<=', now())
            ->paginate(5);

        $categorie = Category::all();

        $saison  = saison_active();

        $all_valid_articles = Shop_article::where('type_article', '=', '2')
            ->where('saison', '=', $saison)
            ->get();

        $filtered_articles = filterArticlesByValidityDate($all_valid_articles);

        $shop_articles = $filtered_articles->shuffle()->take(7);

        // $images = File::files(public_path('uploads/Slider'));
        // $imageUrls = [];

        // foreach ($images as $image) {
        //     if (in_array($image->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
        //         $imageUrls[] = asset('uploads/Slider/' . $image->getFilename());
        //     }
        // }

        $imageUrls = Carousel::where('active', '=', '1')
            ->orderBy('image_order', direction: 'ASC')
            ->get();

        $directory = public_path('uploads/Partenaires');
        $files = File::files($directory);

        // Filter only images 
        $imageFiles = array_filter($files, function ($file) {
            return in_array(strtolower($file->getExtension()), ['png', 'jpg', 'jpeg', 'gif']);
        });

        return view('carousel', compact('posts', 'categorie', 'shop_articles', 'imageUrls', 'imageFiles'));
    }


    public function tousLesArticles()
    {
        $posts = A_Blog_Post::where('status', '=', 'Publié')
            ->where('date_post', '<=', now())
            ->orderBy('date_post', 'desc')
            ->paginate(9);



        $categorie = Category::all();

        return view('tousLesArticles', compact('posts', 'categorie'));
    }

    public function blog($id)
    {
        $posts = A_Blog_Post::FindOrFail($id);

        $categorie = Category::all();

        $carouselle =  A_Blog_Post::latest()
            ->where('status', '=', 'Publié')
            ->paginate(5);

        return view('blog', compact('posts', 'categorie', 'carouselle'));
    }

    public function shop()
    {
        $shop_articles = Shop_article::take(12)
            ->where('type_article', '=', '2')
            ->get();
        return view('shop', ['shop_articles' => $shop_articles]);
    }

    public function shop_categories()
    {
        $shop_categories = Shop_category::where('id_shop_category', '<=', '9')->orderBy('order_category', 'ASC')->get();
        $message_general = SystemSetting::where('name', 'Message general')->where('value', 1)->value('Message');

        return view('shop_categories', [
            'shop_categories' => $shop_categories,
            'message_general' => $message_general
        ]);
    }



    public function sub_shop_categories($id)
    {
        MiseAjourStock();
        $indice = $id;
        $info = Shop_category::where('active', 1)->get();
        $breadcrumb = $this->getBreadcrumb($indice, $info);
        $info2 = Shop_category::select('name', 'description')->where('id_shop_category', '=', $indice)->first();

        // Vérification de la dernière sous-catégorie
        $isLastSubCategory = !$info->where('id_shop_category_parent', $indice)->count();
        $display_product = $isLastSubCategory;

        $message_general = SystemSetting::where('name', 'Message general')->where('value', 1)->value('Message');

        // Redirection si c'est la dernière sous-catégorie
        if ($display_product) {
            return redirect()->route('boutique', ['id' => $indice]);
        }

        return view('sub_shop_categories', compact('info', 'breadcrumb', 'indice', 'info2', 'message_general'));
    }


    public function getBreadcrumb($categoryId, $categories)
    {
        $breadcrumb = [];
        while ($categoryId) {
            $currentCategory = $categories->firstWhere('id_shop_category', $categoryId);
            if ($currentCategory) {
                $breadcrumb[] = $currentCategory;
                $categoryId = $currentCategory->id_shop_category_parent;
            } else {
                break;
            }
        }
        return array_reverse($breadcrumb);
    }



    public function boutique($id)
    {
        $info = Shop_category::where('active', 1)->get();

        $breadcrumb = $this->getBreadcrumb($id, $info);

        $info2 = Shop_category::select('name', 'description', 'id_shop_category')->where('id_shop_category', '=', $id)->first();

        $saison_active = saison_active();

        $articles = Shop_article::getArticlesByCategories($id, $saison_active);

        $message_general = SystemSetting::where('name', 'Message general')->where('value', 1)->value('Message');

        return view('boutique', compact('articles', 'breadcrumb', 'info2', 'message_general'));
    }

    public function singleProduct($id)
    {
        MiseAjourStock();

        $articl = Shop_article::where('id_shop_article', $id)->firstOrFail();

        $saisonActive = saison_active();
        $saisonPrecedente = $saisonActive - 1;

        $userAchetéType0 = false;
        if (Auth::check()) {
            $userId = Auth::id();
            $user = Auth::user();
            $familyId = $user->family_id;

            $userAchetéType0 = LiaisonShopArticlesBill::whereHas('shopArticle', function ($query) use ($saisonPrecedente) {
                $query->where('type_article', 0)
                    ->where('saison', $saisonPrecedente);
            })
                ->whereHas('user', function ($query) use ($familyId) {
                    $query->where('family_id', $familyId);
                })
                ->exists();
        }

        if ($articl->type_article == 1) {
            $articl = Shop_article::where('shop_article.id_shop_article', $id)
                ->join('shop_article_1', 'shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')
                ->select('shop_article.*', 'shop_article_1.*', 'shop_article.stock_actuel as stock_actuel')
                ->firstOrFail();

            $teacherIds = json_decode($articl->teacher, true);
            $teachers = User::whereIn('user_id', $teacherIds)->get();

            $schedules = [];
            if (isset($articl->lesson)) {
                $Data_lesson = json_decode($articl->lesson, true);
                $formattedDates = [];
                foreach ($Data_lesson['start_date'] as $index => $startDate) {
                    setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');

                    $startHour = (new DateTime($startDate))->format('H:i');
                    $endHour = (new DateTime($Data_lesson['end_date'][$index]))->format('H:i');
                    $dayWithHours = strftime('%A', strtotime($startDate)) . " de $startHour à $endHour";
                    $schedules[] = $dayWithHours;

                    $dayWithoutHours = strftime('%A %d %B %Y', strtotime($startDate));
                    $formattedDates[] = $dayWithoutHours;
                }

                usort($formattedDates, function ($a, $b) {
                    return strtotime($a) <=> strtotime($b);
                });

                $repriseDate = $formattedDates[0] ?? 'Pas d\'horaire disponible';
            }

            $rooms = Room::whereIn('id_room', $Data_lesson['room'])->get();
            $locations = [];
            foreach ($Data_lesson['room'] as $roomId) {
                $room = $rooms->where('id_room', $roomId)->first();
                if ($room) {
                    $locations[] = [
                        'name' => $room->name,
                        'address' => $room->address,
                        'map' => $room->map
                    ];
                }
            }
        } elseif ($articl->type_article == 2) {
            $articl = Shop_article::where('shop_article.id_shop_article', $id)
                ->join('shop_article_2', 'shop_article.id_shop_article', '=', 'shop_article_2.id_shop_article')
                ->firstOrFail();
        }

        $selectedUsers = array();
        $coursVente = SystemSetting::where('name', 'Cours en vente')->value('value');
        $coursVenteMember = SystemSetting::where('name', 'cours membres n-1')->value('value');
        if (Auth::check()) {
            $selectedUsers = getArticleUsers($articl);
        }
        $declinaisons = Declinaison::where('shop_article_id', $articl->id_shop_article)->where('stock_actuel_d', '>', '0')->get();
        $message_general = SystemSetting::where('name', 'Message general')->where('value', 1)->value('Message');

        if ($articl->type_article == 1) {
            return view('singleProduct', compact('saisonActive', 'coursVenteMember', 'message_general', 'articl', 'teachers', 'schedules', 'locations', 'selectedUsers', 'coursVente', 'repriseDate', 'declinaisons', 'userAchetéType0'));
        } else {
            return view('singleProduct', compact('saisonActive', 'coursVenteMember', 'message_general', 'articl', 'selectedUsers', 'coursVente', 'declinaisons', 'userAchetéType0'));
        }
    }



    public function basket()
    {
        if (Auth::check()) {
            $paniers = DB::table('basket')
                ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
                ->leftJoin('shop_article', function ($join) {
                    $join->on('shop_article.id_shop_article', '=', 'basket.ref')
                        ->where('shop_article.id_shop_article', '<>', -1); // Exclude invalid IDs
                })
                ->leftJoin('declinaisons', 'declinaisons.id', '=', 'basket.declinaison')
                ->where('basket.user_id', '=', auth()->user()->user_id)
                ->groupBy('basket.pour_user_id', 'basket.declinaison', 'basket.user_id', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'basket.prix', 'shop_article.ref', 'users.name', 'users.lastname', 'basket.reduction', 'declinaisons.libelle')
                ->orderBy('basket.pour_user_id')
                ->orderBy('basket.ref')
                ->select('basket.user_id', 'basket.declinaison', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'basket.prix', 'shop_article.ref as reff', 'users.name', 'users.lastname', DB::raw('SUM(basket.qte) as total_qte'), 'declinaisons.libelle as declinaison_libelle', 'basket.reduction')
                ->get();


            $total = 0;
            foreach ($paniers as $panier) {
                $total += $panier->total_qte * $panier->prix;
            }

            if ($total < 0) {
                $total = 0;
            }

            return  view('basket', compact('paniers', 'total'))->with('user', auth()->user());
        } else {
            return redirect()->route('login');
        }
    }


    public function paiement()
    {

        $Mpaiement = DB::table('bills_payment_method')->get();
        $order = [1, 3, 5, 4, 6, 2];
        $Mpaiement = $Mpaiement->sortBy(function ($item) use ($order) {
            return array_search($item->id, $order);
        });
        $user_id = auth()->user()->user_id;
        $adresse = DB::table('users')
            ->select('address', 'zip', 'city', 'country')
            ->where('user_id', $user_id)
            ->first();

        $paniers = DB::table('basket')
            ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
            ->join('shop_article', 'basket.ref', '=', 'shop_article.id_shop_article')
            ->select('basket.qte', 'basket.ref', 'shop_article.title', 'shop_article.image', 'shop_article.totalprice', 'shop_article.ref as reff', 'users.name', 'users.lastname')
            ->get();

        MiseAjourArticlePanier($paniers);

        $paniers = DB::table('basket')
            ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
            ->leftJoin('shop_article', function ($join) {
                $join->on('shop_article.id_shop_article', '=', 'basket.ref')
                    ->where('shop_article.id_shop_article', '<>', -1); // Exclude invalid IDs
            })
            ->where('basket.user_id', '=', auth()->user()->user_id)
            ->groupBy('basket.pour_user_id', 'basket.user_id', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'basket.prix', 'shop_article.ref', 'users.name', 'users.lastname', 'basket.reduction')
            ->orderBy('basket.pour_user_id')
            ->orderBy('basket.ref')
            ->select('basket.user_id', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'basket.prix as totalprice', 'basket.reduction', 'shop_article.ref as reff', 'users.name', 'users.lastname', DB::raw('SUM(basket.qte) as total_qte'))
            ->get();

        $total = 0;

        foreach ($paniers as $panier) {
            $total += $panier->qte * $panier->totalprice;
        }
        if ($total < 0) {
            $total = 0;
        }

        $can_purchase = true;
        $unavailable_articles = [];

        foreach ($paniers as $panier) {
            if ($panier->ref != -1) {
                $shop = Shop_article::find($panier->ref);
                $quantite = $panier->total_qte;
                if (!verifierStockUnArticlePanier($shop, $quantite)) {
                    $can_purchase = false;
                    $unavailable_articles[] = $shop->title;
                }
            }
        }
        $Espece = DB::table('bills_payment_method')->where('payment_method', 'Espèces')->first();
        $Bons = DB::table('bills_payment_method')->where('payment_method', 'Bons')->first();
        $Cheques = DB::table('bills_payment_method')->where('payment_method', 'Chèques')->first();
        $Virement = DB::table('bills_payment_method')->where('payment_method', 'Virement')->first();
        $cb = DB::table('bills_payment_method')->where('payment_method', 'Carte Bancaire')->first();
        if (!$can_purchase) {
            $error_msg = "Les articles suivants ne peuvent pas être achetés: " . implode(', ', $unavailable_articles);
            return redirect()->back()->withErrors([$error_msg]);
        } else {
            return view('paiement', compact('paniers', 'total', 'adresse', 'Mpaiement', 'Espece', 'Bons', 'Cheques', 'Virement', "cb"))->with('user', auth()->user());
        }
    }

    public function fichepaiement($id, $nombre_cheques)
    {
        $paniers = DB::table('basket')
            ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
            ->join('shop_article', 'shop_article.id_shop_article', '=', 'basket.ref')
            ->where('basket.user_id', '=', auth()->user()->user_id)
            ->groupBy('basket.pour_user_id', 'basket.user_id', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'shop_article.totalprice', 'shop_article.ref', 'users.name', 'users.lastname')
            ->orderBy('basket.pour_user_id')
            ->orderBy('basket.ref')
            ->select('basket.user_id', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'shop_article.totalprice', 'shop_article.ref as reff', 'users.name', 'users.lastname', DB::raw('SUM(basket.qte) as total_qte'))
            ->get();



        MiseAjourArticlePanier($paniers);
        $can_purchase = true;
        $unavailable_articles = [];

        foreach ($paniers as $panier) {
            $shop = Shop_article::find($panier->ref);
            $quantite = $panier->total_qte;
            if (!verifierStockUnArticlePanier($shop, $quantite)) {
                $can_purchase = false;
                $unavailable_articles[] = $shop->title;
            }
        }

        if (!$can_purchase) {
            $error_msg = "Les articles suivants ne peuvent pas être achetés: " . implode(', ', $unavailable_articles);
            return redirect()->back()->withErrors([$error_msg]);
        } else {
            $paniers = DB::table('basket')
                ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
                ->leftJoin('shop_article', function ($join) {
                    $join->on('shop_article.id_shop_article', '=', 'basket.ref')
                        ->where('shop_article.id_shop_article', '<>', -1);
                })
                ->where('basket.user_id', '=', auth()->user()->user_id)
                ->groupBy('basket.pour_user_id', 'basket.user_id', 'basket.ref', 'basket.qte', 'basket.declinaison', 'shop_article.title', 'shop_article.image', 'basket.prix', 'shop_article.ref', 'users.name', 'users.lastname', 'basket.reduction', 'shop_article.type_article')
                ->orderBy('basket.pour_user_id')
                ->orderBy('basket.ref')
                ->select('basket.user_id', 'basket.ref', 'basket.qte', 'basket.pour_user_id', 'shop_article.title', 'shop_article.type_article', 'basket.declinaison', 'shop_article.image', 'basket.prix as totalprice', 'basket.reduction', 'shop_article.ref as reff', 'users.name', 'users.lastname', DB::raw('SUM(basket.qte) as total_qte'))
                ->get();

            $payment = DB::table('bills_payment_method')->where('id', '=', $id)->first()->payment_method;

            $total = 0;
            foreach ($paniers as $panier) {
                $total += $panier->qte * $panier->totalprice;
            }
            if ($total < 0) {
                $total = 0;
            }

            if ($paniers->count() == 0) {
                return redirect()->route('basket');
            } else {
                $bill = new bills;
                $bill->user_id = auth()->user()->user_id;
                $bill->date_bill = date('Y-m-d H:i:s');
                $bill->type = "facture";
                $bill->number = $nombre_cheques;
                $bill->payment_method = $id;


                if ($id == 3) {
                    $bill->status = 32;
                    $text = DB::table('bills_payment_method')->where('payment_method', 'Espèces')->first();
                } elseif ($id == 2) {
                    $bill->status = 38;
                    $text = DB::table('bills_payment_method')->where('payment_method', 'Mixte')->first();
                } elseif ($id == 4) {
                    $text = DB::table('bills_payment_method')->where('payment_method', 'Chèques')->first();
                    $total += $nombre_cheques;
                    $bill->status = 30;
                } elseif ($id == 5) {
                    $bill->status = 34;
                    $text = DB::table('bills_payment_method')->where('payment_method', 'Bons')->first();
                    $total += 5;
                } elseif ($id == 6) {
                    $bill->status = 36;
                    $text = DB::table('bills_payment_method')->where('payment_method', 'Virement')->first();
                } elseif ($id == 1) {
                    $bill->status = 100;
                    $text = DB::table('bills_payment_method')->where('payment_method', 'Carte Bancaire')->first();
                }

                $nb_paiment = calculerPaiements($id, $total, $nombre_cheques);

                $bill->payment_total_amount = $total;
                $bill->family_id = auth()->user()->family_id;
                $bill->ref = "0";
                $bill->save();

                $year = date('Y');
                $billIdWithOffset = $bill->id + 10000;
                $bill->ref = "{$year}-{$billIdWithOffset}";

                $bill->save();

                // envoi du mail à la propriétaire de la facture
                $user = auth()->user();
                $receiverEmail = $user->email;
                $userName = 'Gym Concordia [Bureau]';
                $message = "Votre facture n°{$bill->id} a été créée avec succès.";
                $userEmail = "webmaster@gym-concordia.com";
                envoiBillInfoMail($userEmail, $message, $receiverEmail, $userName, $paniers, $total, $nb_paiment, $payment, $bill, $text);

                // envoi du mail Paiement Accepté
                if ($bill->status == 100) {
                    $generatePDFController = new generatePDF();
                    $pdfPath = $generatePDFController->generatePDFreduction_FiscaleOutput($bill->id);
                    Mail::send('emails.order_accepted', ['user' => $user, 'bill' => $bill], function ($message) use ($receiverEmail, $pdfPath, $bill) {
                        $message->from(config('mail.from.address'), config('mail.from.name'));
                        $message->to($receiverEmail);
                        $message->subject("Paiement accepté - Commande : " . $bill->ref);
                    });
                }



                // Ajouter des lignes dans la table de liaison
                foreach ($paniers as $panier) {
                    $pou_user = User::where('user_id', $panier->pour_user_id)->first();
                    $liaison = new liaison_shop_articles_bills;
                    $liaison->bill_id = $bill->id;
                    $liaison->href_product = $panier->reff;
                    $liaison->quantity = $panier->qte;
                    $liaison->ttc = round($panier->totalprice, 2);
                    $liaison->addressee = $pou_user->lastname . ' ' . $pou_user->name;
                    $liaison->sub_total = round($panier->qte * $panier->totalprice, 2);
                    if ($panier->ref == -1) {
                        $liaison->designation = "Réduction";
                    } else {
                        $liaison->designation = $panier->title;
                    }
                    $liaison->id_shop_article = $panier->ref;
                    $liaison->declinaison = $panier->declinaison;
                    $liaison->id_user = $pou_user->user_id;
                    if ($panier->type_article == 2) {
                        $liaison->is_prepared = 0;
                        $liaison->is_distributed = 0;
                    } else {
                        $liaison->is_prepared = 1;
                        $liaison->is_distributed = 1;
                    }
                    $liaison->save();
                }
                incrementReductionUsageCount($paniers);
                DB::table('basket')->where('user_id', auth()->user()->user_id)->delete();
                MiseAjourStock();


                return view('postAchat', compact('paniers', 'total', 'payment', 'nb_paiment', 'bill', 'text'))->with('user', auth()->user());
            }
        }
    }

    public function mesfactures()
    {
        $user = Auth::user();
        $familyId = $user->family_id;

        $userRole = $user->role;

        $currentBillsQuery = bills::with(['Billstat', 'paymentMethod', 'user', 'additionalCharges'])
            ->where('family_id', $familyId);

        $oldBillsQuery = old_bills::with(['Billstat', 'paymentMethod', 'user', 'additionalCharges'])
            ->where('family_id', $familyId);

        if ($userRole == 5) {
            $currentBillsQuery->where('status', '!=', 31);
            $oldBillsQuery->where('status', '!=', 31);
        }

        $currentBills = $currentBillsQuery->get();
        $oldBills = $oldBillsQuery->get();

        $bills = $currentBills->merge($oldBills)->sortByDesc('date_bill');

        $additionalChargesCount = AdditionalCharge::where('family_id', $familyId)->count();

        if (PaiementImmediat::where('family_id', $familyId)->exists()) {
            return view('mesfactures', compact('bills', 'additionalChargesCount'))
                ->with('user', $user)
                ->with('showPaymentButton', true);
        } else {
            return view('mesfactures', compact('bills', 'additionalChargesCount'))
                ->with('user', $user)
                ->with('showPaymentButton', false);
        }
    }

    public static function getBillType($id)
    {
        $bill = DB::table('bills')->where('id', $id)->first();
        if ($bill) {
            return 'bill';
        }

        $oldBill = DB::table('old_bills')->where('id', $id)->first();
        if ($oldBill) {
            return 'old_bill';
        }

        return null;
    }

    public function mafacture($id)
    {
        $user = auth()->user();

        $billType = $this->getBillType($id);
        updateTotalCharges($id);

        if ($billType == "bill") {
            $bill = DB::table('bills')
                ->join('users', 'bills.user_id', '=', 'users.user_id')
                ->join('bills_status', 'bills.status', '=', 'bills_status.id')
                ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
                ->where('bills.id', $id)
                ->select('bills.*', 'bills_status.row_color', 'bills_status.status as bill_status', 'users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country', 'users.birthdate', 'bills_payment_method.payment_method as method')
                ->first();
        } else if ($billType == "old_bill") {
            $bill = DB::table('old_bills')
                ->join('users', 'old_bills.user_id', '=', 'users.user_id')
                ->leftJoin('bills_status', 'old_bills.status', '=', 'bills_status.id')
                ->leftJoin('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
                ->where('old_bills.id', $id)
                ->select('old_bills.*', 'bills_status.row_color', 'bills_status.status as bill_status', 'users.name', 'users.lastname', 'users.email', 'users.phone', 'users.address', 'users.city', 'users.zip', 'users.country', 'users.birthdate', 'bills_payment_method.payment_method as method')
                ->first();

            if ($bill) {
                if (empty($bill->status)) {
                    $bill->status = 100;
                }
                if (empty($bill->bill_status)) {
                    $bill->bill_status = 'Paiement Accepté';
                }
                if (empty($bill->payment_method)) {
                    $bill->payment_method = 1;
                }
            }
        } else {
            $bill = null;
        }

        if ($user->belongsToFamily($bill?->family_id) || Route::currentRouteName() === 'user.showBill') {
            $shop = DB::table('liaison_shop_articles_bills')
                ->leftJoin('declinaisons', 'declinaisons.id', '=', 'liaison_shop_articles_bills.declinaison')
                ->select(
                    'id_user',
                    'quantity',
                    'ttc',
                    'sub_total',
                    'designation',
                    'addressee',
                    'shop_article.image',
                    'shop_article.id_shop_article',
                    'liaison_shop_articles_bills.id_liaison',
                    'declinaisons.libelle as declinaison_libelle',
                    'liaison_shop_articles_bills.id_shop_article as article_id'
                )
                ->leftJoin('shop_article', function ($join) {
                    $join->on('shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
                        ->where('shop_article.id_shop_article', '<>', -1);
                })
                ->leftJoin('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
                ->leftJoin('old_bills', 'old_bills.id', '=', 'liaison_shop_articles_bills.bill_id')
                ->where(function ($query) use ($id) {
                    $query->where('bills.id', '=', $id)
                        ->orWhere('old_bills.id', '=', $id);
                })
                ->orderBy('designation', 'asc')
                ->get();



            $messages = DB::table('shop_messages')
                ->join('users', 'shop_messages.id_admin', '=', 'users.user_id')
                ->where('shop_messages.id_bill', $id)
                ->where('shop_messages.state', 'Public')
                ->select('shop_messages.message', 'shop_messages.id_shop_message', 'shop_messages.date', 'shop_messages.somme_payé', 'users.name', 'users.lastname', 'shop_messages.id_customer', 'shop_messages.id_admin', 'shop_messages.state')
                ->orderBy('shop_messages.date', 'asc')
                ->get();

            $saison  = saison_active();

            $all_valid_articles = Shop_article::where('type_article', '=', '2')
                ->where('saison', '=', $saison)
                ->get();

            $filtered_articles = filterArticlesByValidityDate($all_valid_articles);

            $shop_articles = $filtered_articles->shuffle()->take(7);


            $nb_paiment = calculerPaiements((int) $bill->payment_method ?? 1, $bill->payment_total_amount, $bill->number);
            $additionalCharge = \App\Models\AdditionalCharge::where('family_id', $user->family_id)->first();

            return view('mafacture', compact('bill', 'shop_articles', 'nb_paiment', 'shop', 'messages', 'additionalCharge'))->with('user', auth()->user());
        }

        abort(403, 'Vous n\'êtes pas autorisé à accéder à cette facture.');
    }

    public function mafamille()
    {

        $posts = A_Blog_Post::latest()
            ->where('status', '=', 'Publié')
            ->paginate(5);

        $n_users = User::with(['adhesions' => function ($query) {
            $query->orderBy('saison', 'desc')->orderBy('shop_article.title', 'asc');
        }])
            ->with(relations: ['familyParents'])
            ->where('family_id', Auth::user()->family_id)
            ->orderBy('family_level', 'desc')
            ->get();

        if (is_null($n_users)) {
            return view('mafamille', compact('posts'))->with('user', auth()->user());
        } else {
            return view('mafamille', compact('n_users', 'posts'))->with('user', auth()->user());
        }
    }



    public function generatePDF($id)
    {
        $info2 = Shop_category::select('name', 'description')->where('id_shop_category', '=', $id)->first();

        $saison_active = saison_active();
        $articles = Shop_article::getArticlesByCategories($id, $saison_active);

        $pdf = PDF::loadView('pdfBoutique', compact('info2', 'articles'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream($info2->name . '.pdf');
    }
}

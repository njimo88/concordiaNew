<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AddUserform;
use App\Http\Requests\AddEnfantform;
use App\Http\Requests\AddMemberform;
use Illuminate\Support\Facades\Auth;
use App\Models\bills;
use App\Models\liaison_shop_articles_bills;
use App\Models\old_bills;
use App\Models\BankAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Shop_article;
use App\Models\Shop_article_2;
use App\Models\PaiementImmediat;
use App\Models\Role;
use App\Models\SystemSetting;
use App\Models\AdditionalCharge;
use App\Models\ShopMessage;
use App\Models\MedicalCertificates;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

require_once(app_path() . '/fonction.php');




class UsersController extends Controller
{



    public function showForm($nombre_virment, $total)
    {
        $selectedBankId = DB::table('system')->where('name', 'selected_bank_id')->value('value');
        $bank = BankAccount::find($selectedBankId);

        $vads_site_id = $bank->site_id;
        $key = $bank->secret_key;

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

            $payment = DB::table('bills_payment_method')->where('id', '=', 1)->first()->payment_method;

            $total = 0;
            foreach ($paniers as $panier) {
                $total += $panier->qte * $panier->totalprice;
            }
            if ($total < 0) {
                $total = 0;
            }
            if ($paniers->count() == 0) {
                return redirect()->route('panier');
            } else {
                $bill = new bills;
                $bill->user_id = auth()->user()->user_id;
                $bill->date_bill = date('Y-m-d H:i:s');
                $bill->type = "facture";
                $bill->number = $nombre_virment;
                $bill->payment_method = 1;
                $bill->status = 31;
                $text = DB::table('bills_payment_method')->where('payment_method', 'Carte Bancaire')->first();

                $nb_paiment = calculerPaiements(1, $total, $nombre_virment);

                $bill->payment_total_amount = $total;
                $bill->family_id = auth()->user()->family_id;
                $bill->ref = "0";
                $bill->save();

                $year = date('Y');
                $billIdWithOffset = $bill->id + 10000;
                $bill->ref = "{$year}-{$billIdWithOffset}";

                $bill->save();

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

                $userId = Auth::user()->user_id;
                $user = User::find($userId);

                $year = date('Y');
                $billIdWithOffset = $bill->id + 10000;
                $userId = $user->user_id;
                $orderId = "{$year}-{$billIdWithOffset}-{$userId}";

                $paiements = calculerPaiements(1, $total, $nombre_virment);

                $utcDate = gmdate('YmdHis');
                $vads_trans_id = substr(uniqid(), -6);

                $payment_config = $nombre_virment > 1
                    ? 'MULTI:first=' . $paiements[0] * 100 . ';count=' . $nombre_virment . ';period=30'
                    : 'SINGLE';

                $data = [
                    "vads_cust_id" => $user->user_id,
                    "vads_cust_email" => $user->email,
                    "vads_cust_first_name" => remove_accents($user->name),
                    "vads_cust_last_name" => remove_accents($user->lastname),
                    "vads_cust_phone" => $user->phone,
                    "vads_cust_address" => remove_accents($user->address),
                    "vads_cust_zip" => remove_accents($user->zip),
                    "vads_cust_city" => remove_accents($user->city),
                    "vads_cust_country" => remove_accents($user->country),
                    "vads_action_mode" => "INTERACTIVE",
                    "vads_amount" => $total * 100,
                    "vads_currency" => "978",
                    "vads_ctx_mode" => "PRODUCTION",
                    "vads_order_id" => $orderId,
                    "vads_page_action" => "PAYMENT",
                    "vads_payment_cards" => "VISA;MASTERCARD",
                    "vads_payment_config" => $payment_config,
                    "vads_site_id" => $vads_site_id,
                    "vads_trans_date" => $utcDate,
                    "vads_trans_id" => $vads_trans_id,
                    "vads_version" => "V2",
                    "vads_url_success" => route('detail_paiement', ['id' => $bill->id]),
                    "vads_url_cancel" => route('panier', ['message' => 'Transaction annulée']),
                    "vads_url_error" => route('panier', ['message' => 'Erreur lors de la transaction']),
                    "vads_url_refused" => route('panier', ['message' => 'Transaction refusée']),
                    "vads_redirect_success_timeout" => "0",
                    "vads_redirect_error_timeout" => "0",
                    "vads_return_mode" => "GET",
                ];

                $signature = generateSignature($data, $key, "HMAC-SHA-256");

                return view('admin.payment_form')->with(compact('vads_site_id', 'nombre_virment', 'signature', 'utcDate', 'orderId', 'paiements', 'vads_trans_id', 'total', 'user', 'payment_config', 'bill'));
            }
        }
    }



    public function showFormFrais($nombre_virment, $total, $bill_id)
    {
        $userId = Auth::user()->user_id;
        $user = User::find($userId);
        $bill_id = $bill_id;

        $selectedBankId = DB::table('system')->where('name', 'selected_bank_id')->value('value');
        $bank = BankAccount::find($selectedBankId);

        $vads_site_id = $bank->site_id;
        $key = $bank->secret_key;

        $bill = bills::latest('id')->first();
        $year = date('Y');
        $billIdWithOffset = $bill_id + 10000;
        $userId = $user->user_id;
        $orderId = "{$year}-{$billIdWithOffset}-{$userId}";

        $paiements = calculerPaiements(1, $total, $nombre_virment);

        $utcDate = gmdate('YmdHis');
        $vads_trans_id = substr(uniqid(), -6);


        $payment_config = $nombre_virment > 1
            ? 'MULTI:first=' . $paiements[0] * 100 . ';count=' . $nombre_virment . ';period=30'
            : 'SINGLE';

        $data = [
            "vads_cust_id" => $user->user_id,
            "vads_cust_email" => $user->email,
            "vads_cust_first_name" => remove_accents($user->name),
            "vads_cust_last_name" => remove_accents($user->lastname),
            "vads_cust_phone" => $user->phone,
            "vads_cust_address" => remove_accents($user->address),
            "vads_cust_zip" => remove_accents($user->zip),
            "vads_cust_city" => remove_accents($user->city),
            "vads_cust_country" => remove_accents($user->country),
            "vads_action_mode" => "INTERACTIVE",
            "vads_amount" => $total * 100,
            "vads_currency" => "978",
            "vads_ctx_mode" => "PRODUCTION",
            "vads_order_id" => $orderId,
            "vads_page_action" => "PAYMENT",
            "vads_payment_cards" => "VISA;MASTERCARD",
            "vads_payment_config" => $payment_config,
            "vads_site_id" => $vads_site_id,
            "vads_trans_date" => $utcDate,
            "vads_trans_id" => $vads_trans_id,
            "vads_version" => "V2",
            "vads_url_success" => route('frais_paye'),
            "vads_url_cancel" => route('mesfactures', ['message' => 'Transaction annulée']),
            "vads_url_error" => route('mesfactures', ['message' => 'Erreur lors de la transaction']),
            "vads_url_refused" => route('mesfactures', ['message' => 'Transaction refusée']),
            "vads_redirect_success_timeout" => "0",
            "vads_redirect_error_timeout" => "0",
            "vads_return_mode" => "GET",
        ];

        $signature = generateSignature($data, $key, "HMAC-SHA-256");
        return view('admin.payment_formFrais')->with(compact('vads_site_id', 'nombre_virment', 'signature', 'utcDate', 'orderId', 'paiements', 'vads_trans_id', 'total', 'user', 'payment_config', 'bill_id'));
    }


    public function frais_paye(Request $request)
    {
        $paymentDetails = "Votre paiement de " . ($request->vads_amount / 100) . " € a été effectué avec succès.";
        $userFamilyId = auth()->user()->family_id;
        $billId = AdditionalCharge::where('family_id', $userFamilyId)->where('amount', $request->vads_amount / 100)->first()->bill_id;

        ShopMessage::create([
            'message' => $paymentDetails,
            'date' => now(),
            'id_bill' => $billId,
            'id_customer' => auth()->user()->user_id,
            'id_admin' => 140,
            'state' => 'Public',
            'somme_payé' => ($request->vads_amount / 100) * (-1),
        ]);

        AdditionalCharge::where('family_id', $userFamilyId)->where('bill_id', $billId)->delete();

        $bill = bills::where('id', $billId)->first();
        if ($bill) {
            $bill->status = 100;
            $bill->save();
        }

        return redirect()->route('mafacture', ['id' => $billId])->with('success', 'Votre paiement a été effectué avec succès.');
    }






    public function editdata()
    {
        ini_set('max_execution_time', 300); // 300 seconds = 5 minutes
        // Retrieve all unique email addresses from the "users" table
        $emails = DB::table('users')
            ->select('email')
            ->distinct()
            ->get();
        // Loop through each email address
        foreach ($emails as $email) {
            // Retrieve the user with that email
            $user = DB::table('users')
                ->where('email', $email->email)
                ->where('role', '<', 20)
                ->first();
            // Check if the user meets the condition
            if ($user) {
                // Update the user's username to their email
                DB::table('users')
                    ->where('user_id', $user->user_id)
                    ->update(['username' => $user->email]);
            }
        }
        dd('done');
    }

    public function passwordd()
    {
        DB::table('system')->insert([
            'name' => 'password_maintenance',
            'Message' => bcrypt('Mick67mickmath')
        ]);
    }

    public function uploadProfileImage(Request $request)
    {
        if ($request->hasFile('profile_image')) {
            $user_id = $request->input('user_id');
            $image = $request->file('profile_image');
            $filename = $user_id . '.jpg';

            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $image_resize->save(public_path('uploads/users/' . $filename));

            // Récupérer l'utilisateur à l'aide de l'ID utilisateur
            $user = User::find($user_id);

            // Mettre à jour l'attribut image de l'utilisateur avec la nouvelle URL de l'image
            $user->image = asset('uploads/users/' . $filename);

            // Enregistrer les modifications dans la base de données
            $user->save();

            // Retourner l'URL de la nouvelle image de profil
            return response()->json([
                'image_url' => asset('uploads/users/' . $filename),
            ]);
        }
    }





    public function panier()
    {
        if (Auth::check()) {

            $paniers = DB::table('basket')
                ->join('users', 'users.user_id', '=', 'basket.pour_user_id')
                ->join('shop_article', 'shop_article.id_shop_article', '=', 'basket.ref')
                ->leftJoin('shop_article_2', 'shop_article_2.id_shop_article', '=', 'basket.ref')
                ->where('basket.user_id', '=', auth()->user()->user_id)
                ->groupBy('basket.pour_user_id', 'shop_article_2.declinaison', 'basket.declinaison', 'basket.user_id', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'basket.prix', 'shop_article.ref', 'users.name', 'users.lastname', 'basket.reduction')
                ->orderBy('basket.pour_user_id')
                ->orderBy('basket.ref')
                ->select('basket.user_id', 'basket.declinaison', 'basket.ref', 'basket.qte', 'shop_article.title', 'shop_article.image', 'basket.prix', 'shop_article.ref as reff', 'users.name', 'users.lastname', DB::raw('SUM(basket.qte) as total_qte'), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(shop_article_2.declinaison, '$[0].libelle')) as declinaison_libelle"), 'basket.reduction')
                ->get();

            $total = 0;
            foreach ($paniers as $panier) {
                $total += $panier->total_qte * $panier->prix;
            }
            // Retourner la vue avec les données récupérées
            return view('users.panier', compact('paniers', 'total'))->with('user', auth()->user());
        } else {
            return redirect()->route('login');
        }
    }

    public function detail_paiement($id)
    {
        $bill = bills::where('id', $id)->first();
        if ($bill) {
            $bill->status = 100;
            $bill->save();
        }
        $payment = DB::table('bills_payment_method')->where('id', '=', 1)->first()->payment_method;

        $total = $bill->payment_total_amount;

        $text = DB::table('bills_payment_method')->where('payment_method', 'Carte Bancaire')->first();

        $nombre_cheques = $bill->number;

        $nb_paiment = calculerPaiements(1, $total, $nombre_cheques);


        return view('users.detail_paiement', compact('total', 'payment', 'nb_paiment', 'bill', 'text'))->with('user', auth()->user());
    }


    public function Vider_panier()
    {
        DB::table('basket')->where('user_id', auth()->user()->user_id)->delete();
        return redirect()->route('panier');
    }

    public function payer_article()
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
            ->join('shop_article', 'shop_article.id_shop_article', '=', 'basket.ref')
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
            $shop = Shop_article::find($panier->ref);
            $quantite = $panier->total_qte;
            if (!verifierStockUnArticlePanier($shop, $quantite)) {
                $can_purchase = false;
                $unavailable_articles[] = $shop->title;
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
            return view('users.payer_article', compact('paniers', 'total', 'adresse', 'Mpaiement', 'Espece', 'Bons', 'Cheques', 'Virement', "cb"))->with('user', auth()->user());
        }
    }







    /*Update users*/
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
            'lastname' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->user()->user_id],
            'phone' =>  ['required', 'regex:/^0[0-9]{9}$/'],
            'profession' => 'alpha|max:191',
            'birthdate' => 'date',
            'nationality' => 'required',
        ]);

        auth()->user()->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'profession' => $request->profession,
            'birthdate' => $request->birthdate,
            'nationality' => $request->nationality,
        ]);

        return redirect()->route('users.edit-profil')->with('success', 'Votre profil a été mis à jour avec succès');
    }

    public function edit()
    {
        return view('users.edit-profil')->with('user', auth()->user());
    }

    public function authortze()
    {
        return true;
    }


    public function family()
    {

        $n_users = User::where('family_id', Auth::user()->family_id)->orderBy('family_level', 'desc')->get();
        if (is_null($n_users)) {
            return view('users.family')->with('user', auth()->user());
        } else {
            return view('users.family', compact('n_users'))->with('user', auth()->user());
        }
    }


    public function addMember(AddMemberform $request)
    {
        $validateData = $request->validated();
        $addMember = new User();

        $addMember->name = strtoupper($validateData['nameMem']);
        $addMember->lastname = ucfirst($validateData['lastnameMem']);
        $addMember->email = $validateData['emailMem'];
        $addMember->password = bcrypt($validateData['passwordMem']);
        $addMember->phone = $validateData['phoneMem'];
        $addMember->profession = $validateData['professionMem'];
        $addMember->gender = $validateData['genderMem'];
        $addMember->birthdate = $validateData['birthdateMem'];
        $addMember->nationality = $validateData['nationalityMem'];
        $addMember->address = $validateData['addressMem'];
        $addMember->zip = $validateData['zipMem'];
        $addMember->city = $validateData['cityMem'];
        $addMember->country = $validateData['countryMem'];
        $addMember->family_id = auth()->user()->family_id;
        $addMember->family_level = "parent";
        $addMember->save();
        return redirect()->route('users.family')->with('success', 'Le parent a été ajouté avec succès');
    }



    public function addEnfant(AddEnfantform $request)
    {

        $validateData = $request->validated();
        $addMember = new User();

        $addMember->name = strtoupper($validateData['name']);
        $addMember->lastname = ucfirst($validateData['lastname']);
        $addMember->email = $validateData['email'];
        $addMember->password = bcrypt($validateData['password']);
        $addMember->phone = $validateData['phone'];
        $addMember->profession = $validateData['profession'];
        $addMember->gender = $validateData['gender'];
        $addMember->birthdate = $validateData['birthdate'];
        $addMember->nationality = $validateData['nationality'];
        $addMember->address = $validateData['address'];
        $addMember->zip = $validateData['zip'];
        $addMember->city = $validateData['city'];
        $addMember->country = $validateData['country'];
        $addMember->family_id = auth()->user()->family_id;
        $addMember->family_level = "child";
        $addMember->save();

        return redirect()->route('users.family')->with('success', 'L\'enfant a été ajouté avec succès');
    }


    public function editFamille(Request $request, $user_id)
    {
        $user = User::find($user_id);
        try {
            $validatedData = $request->validate([
                'name' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
                'lastname' => ['required', 'regex:/^[\pL\s\-]+$/u', 'max:255'],
                'email' => ['nullable', 'string', 'email', 'max:255'],
                'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
                'profession' => 'string|max:191',
                'birthdate' => 'required|date|before:today',
                'address' => 'required',
                'zip' => 'required|numeric',
                'city' => 'required',
                'nationality' => 'required',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], $messages = [
                'name.required' => "Le champ nom est requis.",
                'lastname.required' => "Le champ prénom est requis.",
                'phone.required' => "Le champ numéro de téléphone  est requis.",
                'phone.regex' => "Le format du numéro de téléphone est invalide.",
                'profile_image.image' => "L'image de profil doit être une image.",
                'profile_image.mimes' => "L'image de profil doit être un fichier de type jpeg, png ou jpg.",
                'profile_image.max' => "L'image de profil ne doit pas dépasser 2 Mo.",
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors()) // On garde les erreurs détaillées
                ->with('error', 'Un problème dans la saisie des données est survenu, vérifier les erreurs en dessous des champs.'); // On ajoute un message global
        }

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request->password)]);
        } else {
            unset($request['password']);
        }

        $emissionDate = now();

        // Valider la date d'expiration si elle est saisie, qu'il y ait un certificat ou non
        if ($request->input('crt_emission')) {
            $emissionDate = $request->input('crt_emission');

            // Valider la date d'expiration
            try {
                $validatedData = $request->validate([
                    'crt_emission' => 'required|date|date_format:Y-m-d',
                ], [
                    'crt_emission.required' => 'La date d\'expiration du certificat médical est requise quand un certificat est saisi.',
                    'crt_emission.date' => 'La date d\'expiration du certificat médical doit être une date valide.',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->back()
                    ->withErrors($e->errors()) // On garde les erreurs détaillées
                    ->with('error', 'Un problème dans la saisie des données est survenu, vérifier les erreurs en dessous des champs.'); // On ajoute un message global
            }

            // Si une date d'expiration est saisie mais aucun certificat médical n'est téléchargé,
            // on vérifie si l'utilisateur a déjà un certificat existant. Si ce n'est pas le cas,
            // on exige un nouveau certificat.
            if (!$request->hasFile('crt') && (!$user->medicalCertificate || !$user->medicalCertificate->file_path)) {
                try {
                    $validatedData = $request->validate([
                        'crt' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                    ], [
                        'crt.required' => 'Le certificat médical est requis quand une date d\'expiration est saisie.',
                        'crt.image' => "Le certificat médical doit être une image.",
                        'crt.mimes' => "Le certificat médical doit être un fichier de type jpeg, png, jpg ou gif.",
                        'crt.max' => "Le certificat médical ne doit pas dépasser 2 Mo.",
                    ]);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    return redirect()->back()
                        ->withErrors($e->errors()) // On garde les erreurs détaillées
                        ->with('error', 'Un problème dans la saisie des données est survenu, vérifier les erreurs en dessous des champs.'); // On ajoute un message global
                }
            }
        }

        // Si un certificat est téléchargé ou existe déjà, la date d'expiration est obligatoire
        if ($request->hasFile('crt') || ($user->medicalCertificate && $user->medicalCertificate->file_path !== "")) {
            // Vérifier que la date d'expiration est bien après aujourd'hui
            try {
                $validatedData = $request->validate([
                    'crt_emission' => 'required|date|date_format:Y-m-d',
                ], [
                    'crt_emission.required' => 'La date d\'expiration du certificat médical est requise quand un certificat est saisi.',
                    'crt_emission.date' => 'La date d\'expiration du certificat médical doit être une date valide.',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->back()
                    ->withErrors($e->errors()) // On garde les erreurs détaillées
                    ->with('error', 'Un problème dans la saisie des données est survenu, vérifier les erreurs en dessous des champs.'); // On ajoute un message global
            }
        }

        // Si un certificat médical est téléchargé
        if ($request->hasFile('crt')) {
            // Appeler la méthode de sauvegarde du certificat avec redimensionnement si nécessaire
            \App\Http\Controllers\n_AdminController::saveCertificateAndUpdateDatabase($request, $user_id, $user, $emissionDate, 0);
        } elseif ($request->has('crt_emission')) {
            // Mettre à jour la date d'expiration si elle a été saisie sans certificat
            if ($user->medicalCertificate) {
                $user->medicalCertificate->update([
                    'emission_date' => $emissionDate,
                    'validated' => 0
                ]);
            }
        }

        if ($request->input('crt_delete')) {
            // Vérifier si un certificat médical existe pour cet utilisateur
            if ($user->medicalCertificate && $user->medicalCertificate->file_path) {
                // Vérifier si le fichier existe physiquement sur le serveur
                $filePath = public_path($user->medicalCertificate->file_path);
                if (file_exists($filePath)) {
                    // Supprimer le fichier du serveur
                    unlink($filePath);
                }

                // Supprimer l'entrée dans la base de données
                $user->medicalCertificate->delete();
            }
        }

        $profilePicturePath = 'uploads/users/';
        $frozenPath = $profilePicturePath . 'frozen/';

        // Assurez-vous que les dossiers existent
        if (!is_dir(public_path($profilePicturePath))) {
            mkdir(public_path($profilePicturePath), 0777, true);
        }

        if (!is_dir(public_path($frozenPath))) {
            mkdir(public_path($frozenPath), 0777, true);
        }

        $freeze = 0;
        if ($request->input('freeze_image')) {
            $freeze = 1;
        }

        // Si une photo de profil est téléchargé
        if ($request->hasFile('profile_image')) {
            // Appeler la méthode de sauvegarde du certificat avec redimensionnement si nécessaire
            \App\Http\Controllers\n_AdminController::saveProfilePictureAndUpdateDatabase($request, $user, $user_id, 1, $freeze);
        }

        // Gérer les cas où les deux options sont activées
        else if ($request->hasFile('profile_image') && $request->input('delete_image')) {
            // Si une nouvelle photo est téléchargée et la suppression est cochée, ignorer la suppression
            \App\Http\Controllers\n_AdminController::saveProfilePictureAndUpdateDatabase($request, $user, $user_id, 1, $freeze);
        } else if (!$request->hasFile('profile_image') && file_exists(public_path($user->image)) && $freeze && !$request->input('delete_image')) {
            // Extraire le nom du fichier à partir du chemin complet
            $imageName = basename($user->image);  // Cela va extraire le nom du fichier, par exemple "16592.jpg"

            // Définir les anciens et nouveaux chemins complets
            $oldPath = public_path($profilePicturePath . $imageName);  // Ancien chemin complet
            $newPath = public_path($frozenPath . $imageName);  // Nouveau chemin dans le répertoire "frozen"

            // Vérifier si le fichier existe avant de le déplacer
            if (file_exists($oldPath) && $imageName != '') {
                // Déplacer le fichier vers le répertoire "frozen"
                rename($oldPath, $newPath);

                // Mettre à jour le chemin dans la base de données avec le nouveau chemin
                $user->update(['image' => $frozenPath . $imageName]);
            }
        }
        // Si "freeze_image" est décoché et l'image est dans le dossier "frozen"
        else if (!$freeze && str_contains($user->image ?? '', 'frozen')) {
            // Extraire le nom du fichier
            $imageName = basename($user->image);

            // Définir les anciens et nouveaux chemins complets
            $oldPath = public_path($frozenPath . $imageName);
            $newPath = public_path($profilePicturePath . $imageName);

            // Vérifier si le fichier existe avant de le déplacer
            if (file_exists($oldPath)) {
                rename($oldPath, $newPath);

                // Mettre à jour le chemin dans la base de données avec le nouveau chemin
                $user->update(['image' => $profilePicturePath . $imageName]);
            }
        } else if ($request->input('delete_image')) {
            // Supprimer la photo existante
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            // Mettre à jour l'utilisateur pour supprimer la référence à l'image
            $user->update(['image' => null]);
        }

        // Mettre à jour les autres informations de l'utilisateur
        $user->update($request->except(['crt', 'crt_emission', 'profile_image']));

        return redirect()->route('users.family')->with('success', $request->name . " " . $request->lastname . ' a été mis à jour avec succès');
    }


    public function detailsUser($user_id)
    {
        $user = User::find($user_id);
        return view('users.modals.detailsUser', compact('user'));
    }



    public function facture()
    {
        $user = Auth::user();

        $bill = DB::table('bills')
            ->join('bills_status', 'bills.status', '=', 'bills_status.id')
            ->join('bills_payment_method', 'bills.payment_method', '=', 'bills_payment_method.id')
            ->where('bills.user_id', $user->user_id)
            ->select('bills.*', 'bills_status.image_status as image_status', 'bills_status.row_color as row_color', 'bills_payment_method.image as image')
            ->union(
                DB::table('old_bills')
                    ->join('bills_status', 'old_bills.status', '=', 'bills_status.id')
                    ->join('bills_payment_method', 'old_bills.payment_method', '=', 'bills_payment_method.id')
                    ->where('old_bills.user_id', $user->user_id)
                    ->select('old_bills.*', 'bills_status.image_status as image_status', 'bills_status.row_color as row_color', 'bills_payment_method.icon as image')
            )->orderBy('date_bill', 'desc')
            ->get();

        $family_id = auth()->user()->family_id;

        if (PaiementImmediat::where('family_id', $family_id)->exists()) {
            // Afficher le bouton de paiement
            return view('users.facture', compact('bill'))->with('user', auth()->user())->with('showPaymentButton', true);
        } else {
            // Masquer le bouton de paiement
            return view('users.facture', compact('bill'))->with('user', auth()->user())->with('showPaymentButton', false);
        }
    }

    public function deleteFacture($id)
    {
        $bills = bills::find($id);
        if ($bills == null) {
            $bills = old_bills::find($id);
        }
        $bills->delete();
        return redirect()->route('users.FactureUser')->with('success', 'La facture a été supprimée avec succès');
    }

    public function showBill($id)
    {
        $bill = $bill = DB::table('bills')
            ->join('users', 'bills.user_id', '=', 'users.user_id')
            ->where('bills.id', $id)
            ->first();
        if ($bill == null) {
            $bill = $bill = DB::table('old_bills')
                ->join('users', 'old_bills.user_id', '=', 'users.user_id')
                ->where('old_bills.id', $id)
                ->first();
        }
        return view('users.showBill', compact('bill'))->with('user', auth()->user());
    }
}

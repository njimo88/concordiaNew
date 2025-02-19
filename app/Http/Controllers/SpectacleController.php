<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Http\Requests\BlogFilterRequest;
use App\Models\Spectacle;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\File;

#showForm dependencies
use App\Models\BankAccount;
use App\Models\Shop_article;
use App\Models\bills;
use Illuminate\Support\Facades\Mail;
use App\Models\liaison_shop_articles_bills;
use App\Models\Reservation;

require_once(app_path() . '/fonction.php');



class SpectacleController  extends Controller
{

    public function seats($id)  {
        return view('seats', ['spectacletId' => $id]);
    }

    public function index()
    {
        $spectacles = Spectacle::all();
        return view('spectacles.index', compact('spectacles'));
    }

    public function create()
    {
        return view('spectacles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'total_seats' => 'required|integer|min:1',
            'state' => 'required|in:active,inactive',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        //store the image into /public/uploads/spectacles
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); // Rename file
            $image->move(public_path('uploads/spectacles'), $imageName); // Move to public/uploads/spectacles
            $imagePath = 'uploads/spectacles/' . $imageName; // Save relative path in DB
        }

        $Spectacle =Spectacle::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'total_seats' => $request->total_seats,
            'state' => $request->state,
            'image' => $imagePath,
        ]);

    // Load the SQL file
    $sqlFilePath = database_path('sql/seats_finale.sql'); // Path to your .sql file
    $sqlQuery = File::get($sqlFilePath);

    // Replace placeholders (if needed) with actual stage_id
    $sqlQuery = str_replace('?', $Spectacle->id_spectacle, $sqlQuery);
   
    // Execute the SQL query
    DB::unprepared($sqlQuery);


        return redirect()->route('spectacles.index')->with('success', 'Spectacle created successfully.');
    }

    public function edit(Spectacle $spectacle)
    {
        return view('spectacles.edit', compact('spectacle'));
    }

    public function update(Request $request, Spectacle $spectacle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'total_seats' => 'required|integer|min:1',
            'state' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        //delete old image if there is a new image 
        if ($request->hasFile('image')) {
        // Delete old image if it exists
            $oldImagePath = public_path($spectacle->image);
            if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/spectacles'), $imageName);

            // Save new image path
            $spectacle->image = 'uploads/spectacles/' . $imageName;
        }

        $spectacle->update($request->only('name', 'description', 'date', 'total_seats', 'state'));

        return redirect()->route('spectacles.index')->with('success', 'Spectacle updated successfully.');
    }

    public function destroy(Spectacle $spectacle)
    {
        //verify if the image exist before remove it 
        $imagePath = public_path($spectacle->image);
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }

        $spectacle->delete();

        Seat::where('id_spectacle', $spectacle->id_spectacle)->delete();

        return redirect()->route('spectacles.index')->with('success', 'Spectacle deleted successfully.');
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

        $nb_paiment=$nb_paiment = [$total];

        return view('users.detail_paiement', compact('total', 'payment', 'nb_paiment', 'bill', 'text'))->with('user', auth()->user());
    }

    public function showFormSpect()
    {
        $selectedBankId = DB::table('system')->where('name', 'selected_bank_id')->value('value');
        
        $bank = BankAccount::find($selectedBankId);
        
        $vads_site_id = $bank->site_id;
        $key = $bank->secret_key;


        $user_id= Auth::id();
        $total = Reservation::where('id_user', $user_id)->with('seat')->get()->count();
        if ($total==0)
        {
            return redirect()->route('spectacles.index')->with('success', 'you dont have any reservation ');
        }

        $bill = new bills;
        $bill->user_id = auth()->user()->user_id;
        $bill->date_bill = date('Y-m-d H:i:s');
        $bill->type = "seat";
        $bill->number = 1;
        $bill->payment_method = 1;
        $bill->status = 31;
        //$text = DB::table('bills_payment_method')->where('payment_method', 'Carte Bancaire')->first();

        $bill->payment_total_amount = $total;
        $bill->family_id = auth()->user()->family_id;
        $bill->ref = "0";
        $bill->save();

        $year = date('Y');
        $billIdWithOffset = $bill->id + 10000;
        $bill->ref = "{$year}-{$billIdWithOffset}";

        $bill->save();

        $userId = Auth::user()->user_id;
        $user = User::find($userId);

        $year = date('Y');
        $billIdWithOffset = $bill->id + 10000;
        $userId = $user->user_id;
        $orderId = "{$year}-{$billIdWithOffset}-{$userId}";

        //$paiements = calculerPaiements(1, $total, $nombre_virment);

        $utcDate = gmdate('YmdHis');
        $vads_trans_id = substr(uniqid(), -6);

        $payment_config = 'SINGLE';

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
            "vads_url_success" => route('spectacle.detail_paiement', ['id' => $bill->id]),
            "vads_url_cancel" => route('panier', ['message' => 'Transaction annulée']),
            "vads_url_error" => route('panier', ['message' => 'Erreur lors de la transaction']),
            "vads_url_refused" => route('panier', ['message' => 'Transaction refusée']),
            "vads_redirect_success_timeout" => "0",
            "vads_redirect_error_timeout" => "0",
            "vads_return_mode" => "GET",
        ];

        $signature = generateSignature($data, $key, "HMAC-SHA-256");

        return view('spectacles.payment_formspect')->with(compact('vads_site_id', 'signature', 'utcDate', 'orderId', 'vads_trans_id', 'total', 'user', 'payment_config', 'bill'));
    }
        
    

}

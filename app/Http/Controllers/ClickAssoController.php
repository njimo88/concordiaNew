<?php

namespace App\Http\Controllers;

use App\Jobs\ClickAsso;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
require_once(app_path().'/fonction.php');

class ClickAssoController extends Controller
{

    const BASE_URL = 'https://application.clickasso.fr/api/';
    
    private function getRoles()
    {
        $cookie = $this->getLogin();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            '.ASPXAUTH' => $cookie,
        ])->get(self::BASE_URL . "Membership/GetRoles");
        return $response->json();
    }

    public function createFiscalYear($label, $startDate, $endDate)
    {
        $cookie = $this->getLogin();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            '.ASPXAUTH' => $cookie,
        ])->post(self::BASE_URL . "common/createfiscal", [
            'Association' => 'FR67OG0001',
            'Label' => $label,
            'StartDate' => $startDate,
            'EndDate' => $endDate,
            'Active' => true,
            'OpenRegistration' => false
        ]);
        return $response->json();
    }

    
    private function getLogin()
    {

        if (session()->has('clickasso_cookie')) {
            return session('clickasso_cookie');
        }


        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://application.clickasso.fr/api/Common/Login', [
            'Association' => 'gym-concordia',
            'Login' => 'concordia-admin',
            'Password' => '#ClickAsso4Concordia',
            'RememberMe' => false
        ]);

        $data = $response->json();
        // Store the cookie in session for subsequent use
        session(['clickasso_cookie' => $data['Cookie'] ?? null]);


        return $data['Cookie'] ?? null;
    }

    private function getActiveFiscalYear()
{
    $cookie = $this->getLogin();
    
    // Use the 'Cookie' header for the authentication cookie
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Cookie' => '.ASPXAUTH=' . $cookie,
    ])->get('https://application.clickasso.fr/api/common/getfiscals');
    $fiscals = $response->json();
    foreach ($fiscals as $fiscal) {
        if ($fiscal['Active']) {
            return substr($fiscal['Label'], -4);  
        }
    }
    return null;
}


private function transformToInterfaceMember($member)
{
    $memberFromDb = DB::table('users')
    ->where('users.user_id', $member->id_user)
    ->first();

    if ($memberFromDb) {

        $member = $memberFromDb;

        $gender = ($member->gender == 'male') ? 0 : 1;

        $today = now();
        $medicalCertificateDate = $today->startOfMonth()->toDateString();

        $articles = DB::table('liaison_shop_articles_bills')
            ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
            ->where('shop_article.type_article', 1)
            ->where('liaison_shop_articles_bills.id_user', $member->user_id)
            ->select('shop_article.title')
            ->get();

        $group = $articles->isEmpty() ? [0] : $articles->pluck('title')->toArray();

        $groupJson = json_encode($group);

        return [
            'Id' => '', 
            'ExternalId' => $member->user_id,
            'FirstName' => $member->lastname,
            'LastName' => $member->name,
            'Gender' => $gender,
            'BirthDate' => $member->birthdate,
            'BirthCountry' => $member->nationality,
            'Role' => 5365, 
            'Address1' => $member->address,
            'Address2' => null, 
            'Address3' => null, 
            'ZipCode' => $member->zip,
            'City' => $member->city,
            'Country' => $member->country,
            'MedicalCertificate' => $medicalCertificateDate,
            'Email' => $member->role > 15 ? 'contact@gym-concordia.com' : $member->email,
            'Phone' => $member->role > 15 ? '0783664250' : $member->phone,
            'Group' => $groupJson  
        ];
    }
}





    private function getMembersForClickAsso()
    {

        $saison = saison_active();
        
        $members = DB::table('liaison_shop_articles_bills')
            ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
            ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
            ->where('shop_article.type_article', 0)
            ->where ('shop_article.saison', $saison)
            ->where('bills.status', '>', 9)
            ->where('shop_article.saison ', $this->getActiveFiscalYear())
            ->select(DB::raw('DISTINCT liaison_shop_articles_bills.id_user'))
            ->get();


        return $members->map(function($member) {
            return $this->transformToInterfaceMember($member);
        });
    }

    public function syncWithClickAsso()
    {
        $year = $this->getActiveFiscalYear();
        if(!$year) {
            return response()->json(['status' => 'Failed', 'message' => 'Failed to get the active fiscal year.']);
        }
        $members = $this->getMembersForClickAsso();
        dd ($members);
        $cookie = $this->getLogin();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Cookie' => '.ASPXAUTH=' . $cookie,
            ])->post(self::BASE_URL . "interface/updatemembers?year={$year}", $members);
        return $response->json();

    }

    public function deleteAllMembersForYear($year)
{
    try {
        $cookie = $this->getLogin();

        $response = Http::timeout(180)  
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Cookie' => '.ASPXAUTH=' . $cookie,
        ])->delete(self::BASE_URL . "interface/deletemembers?year={$year}");


        if (!$response->successful()) {
            return response()->json(['status' => 'Failed', 'message' => $response->body()], $response->status());
        }

        return $response->json();
    } catch (\Exception $e) {
        return response()->json(['status' => 'Failed', 'message' => $e->getMessage()]);
    }
}


    public function index()
    {
        $cookie = $this->getLogin();

        return view('admin.clickasso', ['cookie' => $cookie]);
    }

}

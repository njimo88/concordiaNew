<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SyncWithClickAssoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const BASE_URL = 'https://application.clickasso.fr/api/';

    public function handle()
    {
        $year = $this->getActiveFiscalYear();
        if (!$year) {
            \Log::error('Failed to get the active fiscal year.');
            return;
        }
        $members = $this->getMembersForClickAsso();
        \Log::info('Number of members: ' . count($members));

        $cookie = $this->getLogin();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Cookie' => '.ASPXAUTH=' . $cookie,
        ])->post(self::BASE_URL . "interface/updatemembers?year={$year}", $members);

        \Log::info('Sync Response:', $response->json());
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
        session(['clickasso_cookie' => $data['Cookie'] ?? null]);

        return $data['Cookie'] ?? null;
    }

    private function getActiveFiscalYear()
    {
        $cookie = $this->getLogin();
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
                'Phone' => $member->role > 15 ? '0805659999' : $member->phone,
                'Group' => $groupJson
            ];
        }
    }

    private function getMembersForClickAsso()
    {
        $members = DB::table('liaison_shop_articles_bills')
            ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
            ->join('shop_article', 'shop_article.id_shop_article', '=', 'liaison_shop_articles_bills.id_shop_article')
            ->where('shop_article.type_article', 0)
            ->where('bills.status', '>', 9)
            ->select(DB::raw('DISTINCT liaison_shop_articles_bills.id_user'))
            ->get();

        return $members->map(function ($member) {
            return $this->transformToInterfaceMember($member);
        });
    }
}

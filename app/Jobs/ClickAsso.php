<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Shop_article;
use App\Models\LiaisonShopArticlesBill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
require_once(app_path().'/fonction.php');

class ClickAsso implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $sevenDaysAgo = Carbon::now()->subDays(7)->toDateString();

        $users = User::with(['bills', 'liaisonShopArticlesBill.shopArticle' => function($query) {
            $query->where('type_article', 0);
        }])
        ->whereHas('bills', function($query) use ($sevenDaysAgo) {
            $query->where('status', '>', 9)
                ->whereDate('date_bill', '<', $sevenDaysAgo);
        })
        ->whereHas('liaisonShopArticlesBill.shopArticle', function($query) {
            $query->where('type_article', 0);
        })
        ->get();

        $saison = saison_active();

        $jsonToSend = '';
        $compteur = 0;

        foreach ($users as $user) {
            if ($user->role >= 30) {
                $phone = "07 83 66 42 50";
                $email = 'contact@gym-concordia.com';
            } else {
                $phone = $user->phone;
                $email = $user->email;
            }

            $gender = $user->gender == 'Male' ? 0 : 1;

            $shopArticles = '';

            foreach ($user->liaisonShopArticlesBill as $liaison) {
                $shopArticle = Shop_article::find($liaison->id_shop_article);

                if ($shopArticle !== null) {
                    $shopArticles = $shopArticles . '"' . $shopArticle->title . '",';
                }
            }

            // enlever la dernière virgule
            $shopArticles = rtrim($shopArticles, ',');
            $shopArticles = '[' . $shopArticles . ']';

            //make certificat the date of today
            $today = date("Y-m-d");

            $jsonToSend .= '{
                "ExternalId" : ' . $user->id . ',
                "FirstName" : "' . $user->firstname . '",
                "LastName" : "' . $user->lastname . '",
                "Gender" : ' . $gender . ',
                "BirthDate" : "' . $user->birthdate . '",
                "BirthCountry" : "' . $user->nationality . '",
                "Role" : 5365,
                "Address1" : "' . $user->address . '",
                "Address2" : null,
                "Address3" : null,
                "ZipCode" : "' . $user->zip . '",
                "City" : "' . $user->city . '",
                "Country" : "' . $user->country . '",
                "MedicalCertificate": "' . $today . '",
                "Email" : "' . $email . '",
                "Phone" : "' . $phone . '",
                "Group" : ' . $shopArticles . '
            },';
            $compteur++;
        }
        error_log("Data sent for {$compteur} users.");
        $jsonToSend = rtrim($jsonToSend, ',');
        $jsonToSend = '[' . $jsonToSend . ']';

        $url = 'https://application.clickasso.fr/api/Interface/UpdateMembers?year=' . $saison;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Cookie' => '.ASPXAUTH=COOKIE',
        ])->post($url, [
            'body' => $jsonToSend
        ]);
        
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Shop_article extends Model
{
    use HasFactory;

    protected $table = 'shop_article';
    protected $primaryKey = 'id_shop_article';
    public $incrementing = true;


    protected $fillable = [

        'title',
        'ref',

        'saison',
        'type_article',
        'stock_ini',
        'stock_actuel',
        'price',
        'totalprice',

        'price_indicative',
        'description',
        'short_description',
        'image',
        'url_shop_article',
        'startvalidity',
        'endvalidity',
        'agemin',
        'agemax',
        'max_per_user',
        'need_member',
        'alert_stock',
        'afiscale',
        'sex_limit',
        'nouveaute',
        'selected_limit',
        'buyers',
        'categories',
        'created_at',
        'updated_at'
    ];


    public function usersActiveCount()
    {
        return DB::table('liaison_shop_articles_bills')
            ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
            ->where('bills.status', '>', 9)
            ->where('liaison_shop_articles_bills.id_shop_article', $this->id_shop_article)
            ->count();
    }

    public function users_cours()
    {
        return $this->belongsToMany(User::class, 'liaison_shop_articles_bills', 'id_shop_article', 'id_user')
            ->join('bills', 'bills.id', '=', 'liaison_shop_articles_bills.bill_id')
            ->join('bills_status', 'bills.status', '=', 'bills_status.id')
            ->leftJoin('medical_certificates', 'medical_certificates.user_id', '=', 'users.user_id') // Jointure pour certificats
            ->where('bills.status', '>', 9)
            ->select(
                'users.user_id',
                'users.name',
                'users.lastname',
                'users.phone',
                'users.birthdate',
                'users.email',
                'liaison_shop_articles_bills.id_shop_article',
                'bills_status.row_color',
                'bills.id',
                'medical_certificates.emission_date'
            )
            ->orderBy('users.name', 'asc')
            ->orderBy('users.lastname', 'asc');
    }

    public function totalBillsCount()
    {
        return $this->users_cours->sum(function ($user) {
            return $user->bills->count();
        });
    }

    public function liaisonShopArticlesBill()
    {
        return $this->hasMany(LiaisonShopArticlesBill::class, 'id_shop_article');
    }


    public static function getArticlesByCategories($categoryIds, $saison_active)
    {
        $categoryIds = (int) $categoryIds;
        $n_var = DB::table('shop_article')
            ->leftJoin('shop_article_1', function ($join) {
                $join->on('shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')
                    ->where('shop_article.type_article', '=', 1);
            })
            ->select('shop_article.*', 'shop_article_1.lesson', 'shop_article.image as image')
            ->where('shop_article.saison', '=', $saison_active)
            ->whereJsonContains('shop_article.categories', [$categoryIds])
            ->distinct('shop_article.id_shop_article')
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
            return getFilteredArticles($n_var);
        }

        return $n_var;
    }

    public function declinaisons()
    {
        return $this->hasMany(Declinaison::class, 'shop_article_id');
    }

    public function updateInitialStock()
    {
        if ($this->type_article != 2 || !$this->declinaisons()->exists()) {
            return;
        }

        $totalInitialStock = $this->declinaisons()->sum('stock_ini_d');
        $totalActualStock = $this->declinaisons()->sum('stock_actuel_d');

        $this->stock_ini = $totalInitialStock;
        $this->stock_actuel = $totalActualStock;
        $this->save();
    }


    public function getDisplayNameAttribute()
    {
        if ($this->type_article == 2 && $this->declinaisons()->count() > 0) {
            return $this->title . ' [' . $this->declinaisons->first()->libelle . ']';
        }

        return $this->title;
    }
}

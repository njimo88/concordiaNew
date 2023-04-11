<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop_article;
use App\Models\shop_article_1;
use Illuminate\Support\Facades\DB;


class Controller_Stat extends Controller
{
    //

    public function index()

    {

        $saison_actu = saison_active() ;

        $shop_article_lesson =  shop_article_1::select('shop_article_1.teacher', 'shop_article.title','shop_article_1.id_shop_article','shop_article.stock_actuel','shop_article.stock_ini')
        ->join('shop_article', 'shop_article.id_shop_article', '=', 'shop_article_1.id_shop_article')->where('saison', $saison_actu)->get();
     
        return view('Statistiques/Home_stat_teacher',compact('shop_article_lesson'));
    }
    







}

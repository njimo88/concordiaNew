<?php

namespace App\Http\Controllers;

use App\Models\A_Blog_Post;
use App\Models\Shop_article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Controller_mention_legales extends Controller
{
    //
    function index(){

       
        $parametre = DB::table('parametre')->where('activate',1)->get();
      
        return view('mentions_legales',compact('parametre'))->with('user', auth()->user()) ; 

    }

    function  index_politique(){

        $shop_article = A_Blog_Post::where('id_blog_post_primaire',13577)->get();

        return view('Politique_de_Conf',compact('shop_article'))->with('user', auth()->user()) ; 

    }

   
}

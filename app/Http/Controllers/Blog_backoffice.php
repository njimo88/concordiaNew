<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Blog_backoffice extends Controller
{
    //

    public function index_article_blog(){
            return view('Blog_backoffice/index_article_blog')->with('user', auth()->user());;
    }


    public function index_categorie_article_blog(){
        return view('Blog_backoffice/index_categorie_article_blog') ;
    }
    

    
    public function index_rediger_article_blog(){
        return view('Blog_backoffice/index_rediger_article_blog') ;
    }

    



}

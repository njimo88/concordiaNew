<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop_article;
use App\Models\Shop_category;
use App\Models\Shop_service;

class Controller_Communication extends Controller
{


    //
    public function index()
    {
        $saison_list = Shop_article::select('saison')->distinct('name')->get();
        $requete_article = Shop_article::select('*')->where('saison',2016)->get();
        return view('Communication/email_communication',compact('saison_list','requete_article'));
       
    }
    public function saison_choix(Request $request)
    {
       
        $saison_choisie = $request->saison ;
        $saison_list = Shop_article::select('saison')->distinct('name')->get();
        $requete_article = Shop_article::select('*')->where('saison',$saison_choisie)->get();
        
        return view('Communication/envoi_mail',compact('requete_article','saison_list'));
       
    }


/*
    public function upload(Request $request)
    {

        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
           
            $request->file('upload')->move(public_path('storage/uploads'), $fileName);
       
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('storage/uploads/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
                  
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
            exit;
        }

}

*/


public function Send_mail(Request $request){

    $title = $request->title ;
    $contenu = $request->editor1 ;
    $article = $request->article ;

    return $article;
    
  
   // return redirect()->route('index_mail')->with('success', ' email envoyé avec succès');
       
}


}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use App\Models\Shop_article;
use App\Models\User;
use App\Models\Shop_category;
use App\Models\Shop_service;
use App\Mail\UserEmail;
use Illuminate\Support\Facades\Mail;

class Controller_Communication extends Controller
{
    //
    public function index()
    {
        $saison_list = Shop_article::select('saison')->distinct('name')->get();
        $requete_article = Shop_article::select('*')->where('saison',2016)->get();
        
        $user = User::select('user_id','name','lastname')->get();
        $user2 = User::paginate(20);


        return view('Communication/email_communication',compact('saison_list','requete_article','user','user2'));
       
    }

    public function test(Request $request)
    {
        $mytab_user = $request['user'] ;

        return view('Communication/form_email',compact('mytab_user')) ;
       
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


  
        $this->validate($request, [
                        'title' => 'required',
                        'editor1' => 'required'
                ]);

        Mail::send('email', [
                'name' => $request->get('title'),
                'comment' => $request->get('editor1'),
                'comment' => $request->get('comment') ],
                function ($message) {
                      //  $message->from('youremail@your_domain');
                        $message->to('nkpericksen@gmail.com', 'NKP')
                        ->subject('Your Website Contact Form');
        });

        return back()->with('success', 'Thanks for contacting me, I will get back to you soon!');

    }








 /*   
$title =  $request->title ;
   
  //$request->editor1;

//The email sending is done using the to method on the Mail facade
  //  Mail::to('nkpericksen@gmail.com')->send(new UserEmail($name));
  Mail::send('Communication/userEmail', array('key' => 'value'), function($message)
{
    $message->to('nkpericksen@gmail.com', 'John Smith')->subject('$title');
});
   
   // return redirect()->route('index_mail')->with('success', ' email envoyé avec succès');
       
}

*/

//---------------------------- email---------------------------------



public function sendmail(Request $get)
{
    $validatedData = $get->validate([
        'tasks' => 'required|array',
        "subject" => "required",
        "message" => "required",
    ]);

    $tasks = $validatedData["tasks"];
    
    foreach ($tasks as $task) {
        $mail = ($task->mail);
        $subject = $get->subject;
        $message = $get->message;

        Mail::to($mail)->send(new UserEmail($subject, $message) );
}
    











}


}


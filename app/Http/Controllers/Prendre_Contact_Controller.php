<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
require_once('../app/fonction.php');

class Prendre_Contact_Controller extends Controller
{
    //

    function traitement_prendre_contact(Request $request){

        $email =   $request->input('email') ;
        $message = $request->input('message') ;
        $nom     = $request->input('name') ;


        

            if ($request->input('send_me') == 1){

              // receiveEmailFromUser($request,'bureau@gym-concordia.com');
              envoiEmail($email, $message,'bureau@gym-concordia.com',$nom) ;
            


            }elseif($request->input('send_me') == 2){

         //receiveEmailFromUser($request,'tresorier@gym-concordia.com');
            envoiEmail($email, $message,'tresorier@gym-concordia.com',$nom) ;
            
              

            }elseif($request->input('send_me') == 3){

               // receiveEmailFromUser($request,'president@gym-concordia.com');
              // receiveEmailFromUser($request,'nnkp066@gmail.com');

             envoiEmail($email, $message,'president@gym-concordia.com',$nom) ;
            
               
              
            }

          

           return redirect()->back()->with('success', 'votre message a été envoyé avec succès!')->with('sent', true);


           //return 'ok';






    }


    








}

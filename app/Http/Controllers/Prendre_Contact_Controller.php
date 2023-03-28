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


        

            if ($request->input('bureau') == 1){

               receiveEmailFromUser($request,'bureau@gym-concordia.com');
              // envoiEmail($email, $message,'nnkp066@gmail.com',$nom) ;
            


            }elseif($request->input('tresorier') == 2){

         receiveEmailFromUser($request,'tresorier@gym-concordia.com');
             //envoiEmail($email, $message,'nnkp066@gmail.com',$nom) ;
            
              

            }elseif($request->input('president') == 3){

               // receiveEmailFromUser($request,'president@gym-concordia.com');
               receiveEmailFromUser($request,'nnkp066@gmail.com');

            // envoiEmail($email, $message,'nnkp066@gmail.com',$nom) ;
            
               
              
            }

            return redirect()->back()->with('success', 'Your message has been sent successfully!');






    }


    








}

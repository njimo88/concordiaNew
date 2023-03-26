<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use App\Models\User;
use App\Models\appels;

use PDF;


class PDF_Controller extends Controller
{
    //


public function preview()
{
    return view('preview');
}

/**
 * Write code on Method
 *
 * @return response()
 */
public function generate($id)
{
    $id_cours = $id ;
    $tab = [] ;
    $tab2 = [] ;
    $present = [] ;
    $appel = appels::where('id_cours',$id)->get();

    foreach($appel as $data){

        foreach ((array)json_decode($data->presents) as $key => $value) {
            $tab[] = $key ;
            $tab2[] = $value ;
           
          
       }
       $present [] = array( $data->date => $tab2) ;
        $tab2 = [] ;


    }

    $users = User::select("*")->whereIn('user_id', $tab)->get();

    $data = [
        'id_cours' => $id_cours,
        'user'     => auth()->user(),
        'users'    => $users,

        'present'  => $present,
        'appel'   => $appel
    ];

    $html = view('club/historique_view', $data)->render();
    $pdf = PDF::loadHTML($html);
    return $pdf->download('demo.pdf');
 
  
    
}












    }





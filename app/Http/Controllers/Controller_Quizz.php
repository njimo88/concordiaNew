<?php

namespace App\Http\Controllers;

use App\Models\Shop_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Controller_Quizz extends Controller
{
    //

    public function index_quizz(Request $request){
        $the_sexe    = $request->input('sexe');
        $tranche_age   = $request->input('tranche_age');

        return view('Test_personnalite/Index_test_personnalite',compact('the_sexe','tranche_age')) ;
    }


    public function handle_questionnaire(Request $request){


        $the_sexe        =   $request->input('sexe');
        $tranche_age     =  $request->input('tranche_age'); 
       
        return redirect()->route('index_quizz', ['the_sexe ' => $the_sexe , 'tranche_age' => $tranche_age ])->with('submitted', true);

     


    }




   public function handle_questionnaire_baby(Request $request){

            $shop_category = Shop_category::get() ;
                $val = 0 ;
            if ($request->input('agebaby') == 1){
                
                $val = 1 ;
                $shop_category = DB::table('shop_category')
                ->where('id_shop_category', 1001)
                ->get();

                return view('Test_personnalite/result_questionnaire_baby',compact('val','shop_category')) ;
                   // dd($shop_category);
                
            }elseif($request->input('agebaby') == 2){
                $val = 2 ;
                $shop_category = DB::table('shop_category')
                 ->where('id_shop_category', 1002)
                 ->get();
              
              
                return view('Test_personnalite/result_questionnaire_baby',compact('val','shop_category')) ;
                
            }elseif($request->input('agebaby') == 3){
                $val = 3 ;
                $shop_category = DB::table('shop_category')
                ->where('id_shop_category', 1003)
                ->get();
             
                return view('Test_personnalite/result_questionnaire_baby',compact('val','shop_category')) ;
                
            }elseif($request->input('agebaby') == 4){
               
             
                $val = 4 ;
                $shop_category = DB::table('shop_category')
                ->where('id_shop_category', 1004)
                ->get();
                return view('Test_personnalite/result_questionnaire_baby',compact('val','shop_category')) ;
                
            }elseif($request->input('agebaby') == 5){
                $val = 5 ;
                $shop_category = DB::table('shop_category')
                ->where('id_shop_category', 1004)
                ->get();
              
                return view('Test_personnalite/result_questionnaire_baby',compact('val','shop_category')) ;
                
            }else{
                return 'nope';
            }

           
            

   }

   

   public function handle_questionnaire_25_et_40(Request $request){

            return $request->input('sport_calm') ;
   
 
    }

    public function handle_questionnaire_6_et_14(Request $request){

        //return $request->input('pers') ;
        return 'nkp';


}







}

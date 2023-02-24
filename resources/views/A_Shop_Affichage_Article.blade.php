<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Shop_CSS/css/style2.css" rel="stylesheet">
    
</head>
<body>
            @php
           
            function printValues($arr) {
                             global $count;
                             global $values;
                                                                                        
                             // Check input is an array
                                 if(!is_array($arr)){
                                   die("ERROR: Input is not an array");
                                    }
                                                                                        
                             /*
                             Loop through array, if value is itself an array recursively call the
                             function else add the value found to the output items array,
                            and increment counter by 1 for each value found                                                     
                                 */
                                  foreach($arr as $key=>$value){
                                              if(is_array($value)){
                                                printValues($value);
                                                        } else{
                                               $values[] = $value;
                                               $count++;

                                                 break;
                                                    }
                                                                                           
                                                        }
                                                                                        
                                       // Return total count and values found in array
                                 return array('total' => $count, 'values' => $values);
                                                                }


                                function fetchDay($date)
                                                            {

                                                    $lejour = ( new DateTime($date) )->format('l');

                                                              $jour_semaine = array(
                                                         "lundi" => "Monday",
                                                         "Mardi" => "Tuesday",
                                                     "Mercredi" => "Wednesday",
                                                         "Jeudi" => "Thursday",
                                                         "Vendredi" => "Friday",
                                                         "Samedi" => "Saturday",
                                                         "Dimanche" => "Sunday"

                                         );
                                                                                                        

                               foreach($jour_semaine as $key=>$j){

                                 if ($j == $lejour){
                                 return $key ;
                                                }
                                   }
                                                                                                        
                                                                                        

                                                                    
                                                                                        
                                                                              
        }
           
                @endphp




<br>
<div class="row mt">

       <div class="row mt">
            <div class="container mt-5 mb-5">
                 <div class="d-flex justify-content-center row">
                 <div class="col-md-10">
                
           @foreach($requete as $data)
         
         
            @if($data->id_shop_category == $indice)

            <div class="row p-2 bg-white border rounded mt-2">

                    
                                <div class="col-md-6 mt-1">
                                    <h3 style="color:blue">  {{ $data->title}} </h3>
                                    <hr>
                                    <div class="d-flex flex-row">
                                        
                                    </div>
                                   
                                    <p class="text-justify text-truncate para mb-0">
                                    
                                @if ($data->short_description  == 'sans')

                                            @foreach($shopService as $data1)

                                                    @if ($data->id_shop_article == $data1->id_shop_article)

                                                        @php


                                                      
 
                                                     $Data_lesson = (array) json_decode($data1->lesson,true);

                                                     $Data_teacher = json_decode($data1->teacher,true);

                                                     $Data_stock_actuel = json_decode($data1->stock_actuel,true);

                                                     $Data_stock_ini = json_decode($data1->stock_ini,true);

                                                     

                                                  /*                                      
                                                    print($Data_teacher[0]);

                                                    foreach($Data_stock_actuel['stock_actuel'] as $dt){
                                                        echo $dt ;
                                                    };
                                                        */

                                                        $norepeat = TRUE ; // eviter de repeter les redondances d'informations a l'affichage
                                                    foreach($Data_lesson['start_date'] as $dt){
                                                        $date = new DateTime($dt);
                                                       

                                                        echo"</p>";

                                                        echo "Cette séance est dispensée le ".fetchDay($dt)." ".$date->format('G:i');
                                                       
                                                    };

                                                    foreach($Data_lesson['end_date'] as $dt){

                                                        
                                                        $date = new DateTime($dt);
                                                      
                                                     
                                                        echo " à ".$date->format('G:i');

                                                       

                                                        };

                                                    
                                                    
                                                    
                                                        foreach($rooms as $room){


                                                            foreach($Data_lesson['room'] as $r){

                                                                if($r == $room->id_room and $norepeat == TRUE){
                                                                        echo"</p>";
                                                                        echo " <b>lieu: </b>".$room->name.' '.$room->address ;
                                                                        $norepeat = FALSE ;
                                                                        echo"</br>";


                                                                      }


                                                                };

                                                        }


                                                        foreach($a_user as $users){


                                                                            foreach($Data_teacher as $t){
                                                                               
                                                                            if($t == $users->user_id){
                                                                                echo"<p>";  echo " <b>  Professeur </b>";
                                                                                echo " ".$users->name." ".$users->lastname;
                                                                                echo"</p>";

                                                                                echo"<p>";

                                                        @endphp                    
                                                       
                                                        
                                                                               
                                                        @php                   

                                                                            }

                                                                                };

                                                                            }

                                    
                                                       @endphp
                                           


                                                    @endif

                                            @endforeach    

                                                    Tous les détails sur la fiche descriptive

                                    @else

                                    {!! $data->short_description !!}
                                        

                                @endif
                                        <br><br></p>
                                    
                         </div>
                         <div class="col-md-3 mt-1">
                        <img class="img-fluid img-responsive rounded product-image" src="{{ $data->image }}">
                    </div>
                     
                       <div class="align-items-center align-content-center col-md-3 border-left mt-1">
                           <div class="d-flex flex-row align-items-center">
                               <h1 class="mr-1">{{$data->totalprice}}€</h1><span class="strike-text"></span>
                           </div>
                           <h4 style="color: green;">Disponible</h4>
                           <div class="d-flex flex-column mt-4">  <a href="{{ route('details_article', ['id' =>  $data->id_shop_article]) }}"><button class="button button2" type="button">Details</button> </a><button class="button button3" type="button">Commander</button></div> <h3>Saison: {{$data->saison}}</h3>
                       </div>
    
                   
            </div> 
                   <br>     
           @endif
          
           @endforeach

          
       </div>
   </div>
</div>

         <!-- col-lg-4 -->
      
                 
         <!-- col-lg-4 -->
       </div>



       </div>
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>
        
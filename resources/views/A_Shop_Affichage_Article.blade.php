


    <link href="../Shop_CSS/css/style2.css" rel="stylesheet">

    

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

                                                 k;
                                                    }
                                                                                           
                                                        }
                                                                                        
                                       // Return total count and values found in array
                                 return array('total' => $count, 'values' => $values);
                                                                }


                        
           
                @endphp




<br>
<div class="container">


        @foreach($requete as $data)
            @if($data->id_shop_category == $indice)
                <div class="row p-2 bg-white border border-dark  d-flex justify-content-center">
                    <div class="col-md-6 mt-1">
                        <a style="font-weight: bold;" class="a2" cl href="{{ route('details_article', ['id' =>  $data->id_shop_article]) }}">{{ $data->title}}</a><br>
                        <div class="row">
                            <div class="col-md-3">
                        @if ($data->type_article != 1)
                                <img  style="max-height: 120px" src="{{ $data->image }}" alt="">
                              @endif
                            </div>
                            <div class="col-md-9">

                                <style>
                                    .description {
                                        display: -webkit-box;
                                        -webkit-line-clamp: 2; 
                                        -webkit-box-orient: vertical;
                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                    }
                                    </style>
                        <p class="text-justify p-1 para mb-0 description">
                        
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
                                                        echo"<b>Jour: </b>";
                                                        echo "Cette séance est dispensée le ".fetchDayy($dt)." ".$date->format('G:i');
                                                       
                                                    };

                                                    foreach($Data_lesson['end_date'] as $dt){

                                                        
                                                        $date = new DateTime($dt);
                                                      
                                                     
                                                        echo " à ".$date->format('G:i');

                                                       

                                                        };

                                                    
                                                    
                                                    
                                                        foreach($rooms as $room){


                                                            foreach($Data_lesson['room'] as $r){

                                                                if($r == $room->id_room and $norepeat == TRUE){
                                                                        echo"<br>";
                                                                        echo "<b>Lieu:</b> <small  style='font-size: 13px;  '><a style=' color: darkblue;' href='https://www.google.com/maps?q=" . urlencode($room->name . " " . $room->address) . "' target='_blank'>" . $room->name . " - " . $room->address . "</a></small>";

                                                                        
                                                                        $norepeat = FALSE ;
                                                                        echo"</br>";
                                                                        echo"</p>";

                                                                      }


                                                                };

                                                        }


                                                      

                                    
                                                       @endphp
                                           


                                                    @endif

                                            @endforeach    


                                    @else

                                    {!! $data->short_description !!}
                                        

                                @endif
                        </p></div></div>
                            
                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                              
                              @foreach($shopService as $data1)
                              
                                @if ($data->id_shop_article == $data1->id_shop_article)
                                  @php
                                    $aff = 0;
                                    $Data_lesson = (array) json_decode($data1->lesson, true);
                                    $Data_teacher = json_decode($data1->teacher, true);
                                    $Data_stock_actuel = json_decode($data1->stock_actuel, true);
                                    $Data_stock_ini = json_decode($data1->stock_ini, true);
                                  @endphp
                                  @foreach($Data_teacher as $t)
                                    @foreach($a_user as $users)
                                      @if($users->user_id == $t)
                                        <div class="d-flex flex-column align-items-center">
                                          <img id="prof" class="mx-auto" style="max-height: 90px;" src="{{ $users->image }}">
                                          <label  style="margin-top:-4px !important; font-size: 10px !important;color : black; " for="prof" class="text-center">{{ $users->lastname }} {{ $users->name }}</label>
                                        </div>
                                        @php $aff = 1; @endphp
                                      @endif
                                    @endforeach
                                  @endforeach
                                @endif
                              @endforeach
                            </div>
                          
                    
                                    
                         </div>
                        
                        
                     
                        <div class="col-md-3 row my-3">
                          <div style="background-color: #ededed; position: relative;" class="col-12 border border-dark p-3">
                              <h3 style="font-size: 1.25rem !important" class="card-title mb-3">Prix :</h3>
                              @if ($data->nouveaute == 1)
                                  <img style="max-height:40px;position: absolute; top: 10px; right: 30px;" src="{{ asset("/assets/images/nouveau.webp") }}" alt="">
                              @endif
                              @php
                                  $reducedPrice = isset($getReducedPriceGuest) ? $getReducedPriceGuest($data->id_shop_article, $data->totalprice) : null;
                                  $priceToDisplay = $reducedPrice ? $reducedPrice : $data->totalprice;
                                  $DescReduc = getFirstReductionDescriptionGuest($data->id_shop_article);
                              @endphp
                              @if ($reducedPrice && $reducedPrice != $data->totalprice)
                                <span style="text-decoration: line-through;">{{ number_format($data->totalprice, 2, ',', ' ') }} €</span>
                                <span style="color: red; font-size: x-large; font-weight: bold;">{{ number_format($priceToDisplay, 2, ',', ' ') }} €</span> <br>
                              @else
                                  <span style="font-size: x-large; font-weight: bold;">{{ number_format($priceToDisplay, 2, ',', ' ') }} €</span>
                              @endif
                              @if ($DescReduc != null)
                                  <span class="p-4" style="font-size: x-small; color: red;">{{ $DescReduc }}</span>
                              @endif
                              <div style="position: absolute; bottom: 5px; right: 2px;">
                                  <span style="font-size: medium; text-decoration: underline;">Saison:</span> <span style="font-size: small">{{$data->saison}}/{{$data->saison+1}}</span> 
                              </div>
                            </div>
                        </div>
                      
                                  
                                  
                                  
                                  
                                  

                          
                          

                          <div class="col-md-3 row my-3">
                                <div style="position: relative;" class="col-12">
                                <div class="row justify-content-end">
                                    <div class="col-5 d-flex justify-content-end p-0">
                                        @if ($data->stock_actuel > $data->alert_stock)
                                            <a href="{{ route('details_article', ['id' =>  $data->id_shop_article]) }}" style="background-color: #28a745 !important;" class="btn  btn-success col-12" type="button">{{ $data->type_article == 1 ? "S'inscrire" : "Commander" }}</a>
                                        @elseif ($data->stock_actuel > 0 && $data->stock_actuel <= $data->alert_stock)
                                            <a href="{{ route('details_article', ['id' =>  $data->id_shop_article]) }}" class="btn  btn-warning col-12" type="button">{{ $data->type_article == 1 ? "S'inscrire" : "Commander" }}</a>
                                        @endif
                                    </div>
                                    
                                    <div class="col-5 d-flex justify-content-end p-0">
                                        <a href="{{ route('details_article', ['id' =>  $data->id_shop_article]) }}" class="btn  btn-primary col-11" type="button">Details</a>
                                        </div>
                                </div>
                                <br> &nbsp; <br>
                                <div class="col-12" style="position: absolute; bottom: 5px; left: 20px;">
                                    @if ($data->stock_actuel > $data->alert_stock)
                                    @if ($data->type_article == 0)
                                        <span style="color:green;"><i class="fas fa-check-circle" style="color:green;"></i> Places Disponibles</span>
                                    @elseif ($data->type_article == 1)
                                        <span style="color:green;"><i class="fas fa-check-circle" style="color:green;"></i> Places Disponibles</span>
                                    @elseif ($data->type_article == 2)
                                        <span style="color:green;"><i class="fas fa-check-circle" style="color:green;"></i> Disponibles</span>
                                    @endif
                                    @elseif ($data->stock_actuel > 0 && $data->stock_actuel <= $data->alert_stock)
                                    @if ($data->type_article == 0)
                                        <span style="color:orange;"><i class="fas fa-exclamation-triangle" style="color:orange;"></i> Il reste {{$data->stock_actuel}} disponibilités</span>
                                    @elseif ($data->type_article == 1)
                                        <span style="color:orange;"><i class="fas fa-exclamation-triangle" style="color:orange;"></i> Il reste {{$data->stock_actuel}} places</span>
                                    @elseif ($data->type_article == 2)
                                        <span style="color:orange;"><i class="fas fa-exclamation-triangle" style="color:orange;"></i> Il reste {{$data->stock_actuel}} disponibilités</span>
                                    @endif
                                    @elseif ($data->stock_actuel <= 0)
                                    @if ($data->type_article == 0)
                                        <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Indisponible/Complet</span>
                                    @elseif ($data->type_article == 1)
                                        <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Séance complète</span>
                                    @elseif ($data->type_article == 2)
                                        <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Indisponible/Complet</span>
                                    @endif
                                    @endif
                                </div>
                                </div>
                          </div>
                        
                        
    
                   
            </div> 
                   <br>     
           @endif
          
           @endforeach

          
</div>

@extends('layouts.app')

@section('content')


@php
                    function fetchDay($date){

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


                       function fetchMonth($date) {

                           $lemois = ( new DateTime($date) )->format('n');

                          $months = array(
                                            1 =>  'Janvier',
                                            2 => 'Fevrier',
                                            3 =>  'Mars',
                                            4 => 'Avril',
                                             5 => 'Mai',
                                             6 =>  'Juin',
                                             7 => 'Juillet ', 
                                             8 => 'Aout',
                                             9 => 'Septembre',
                                             10 => 'Octobre',
                                             11 => 'Novembre', 
                                            12 =>  'Decembre',);


                               foreach($months as $key=>$j){

                                    if ($key == $lemois){
                                    return $j ;
                                      }
                              }                                                                        
                                                                                  
                       }
                       
                       function fetchan($date) {

                         $an = ( new DateTime($date) )->format('Y');

                        return $an ;                                                             
                                                                                  
                       }

                       function fetchjour($date)   {

                         $jour = ( new DateTime($date) )->format('d');

                        return $jour ;
                                      
                                                                                                    
                          }



                       $aff = 0 ; // initailisation de ma variable pour gerer l'affichage du bloc avec ou sans professeur
@endphp



{{-- * pour verifier si l'article est lie a un professeur (si c'est le cas $aff prend 1) */ --}}

              @foreach($shopService as $data1)

                       @if ($indice == $data1->id_shop_article)

                                @php
                                          $Data_teacher = json_decode($data1->teacher,true);

                                                foreach($Data_teacher as $t){

                                                        foreach($a_user as $users){
                                                              if($users->user_id == $t){
                                                    
                                                                      $aff = 1;
                                                      
                                                                    }
                                                            };
                                                   } ;
                                @endphp
                          @endif

              @endforeach

<br>

       

{{--premier affichage concernant les produits avec professeur --}}

@foreach($article as $data)

@if($data->id_shop_article == $indice and $aff == 1)  
<main style="height: 100vh; background-image: url('{{asset("/assets/images/background.png")}}');">

<div style="background-color:white;"  class="container mt-5 mb-2">
          <div  class="d-flex">
            <div class="p-2 bg-light flex-fill">
            <div class="card">
            {{--  Affichage bloc professeur (Nom et photo) --}}
                      <div class="card-body"  >
                        <h4 class="card-title">professeurs</h4>
                        
                        
                            @foreach($shopService as $data1)

                                @if ($data->id_shop_article == $data1->id_shop_article)

                                    @php
                                          $aff = 0 ;
                                          $Data_lesson = (array) json_decode($data1->lesson,true);

                                          $Data_teacher = json_decode($data1->teacher,true);

                                          $Data_stock_actuel = json_decode($data1->stock_actuel,true);

                                          $Data_stock_ini = json_decode($data1->stock_ini,true);


                                          foreach($Data_teacher as $t){

                                                foreach($a_user as $users){
                                                  if($users->user_id == $t){
                                                        echo '<img src="../assets/images/user - Copy (Custom) (Custom).jpg">';
                                                        echo "    ".$users->name." ".$users->lastname ;
                                                       
                                                        echo '<p class="card-text"><h6></h6></p>' ;
                                                        $aff = 1;
                                                        
                                                  }
                                                }
                                            ;
                                          } ;
                                              
                                          
                                        

                                          
                                    @endphp
                                  

                      
                      </div>
          
                      </div>
          

                            





            </div>
            <div class="p-2 bg-light flex-fill" >
            {{--  Affichage bloc prix  --}}
            <div class="card" >
                      <div class="card-body "style="height: 10rem;">
                        <h4 class="card-title">Le prix</h4>
                        
                        <p class="card-text"><h6> {{$data->totalprice}}€</h6></p>
                      
                      </div>

                  </div>



            </div>

            <div class="p-2 bg-secondary flex-fill" style="width:11rem;">
            {{--  Affichage bloc inscription  --}}
          <div class="card"  >
          <div class="card-body">
            <h4 class="card-title">Inscription</h4>
            <p class="card-text">Se connecter pour s'inscrire</p>
            <a href="#" class="card-link"><button type="button" class="btn btn-primary">Se connecter</button></a>
            
          </div>

                </div>



          </div>
            <div class="p-2 flex-fill"> 
              
                                            
          
          
          </div>
          </div>

          <br>

                                <div class="d-flex">
                                  <div class=" flex-fill">
                                    
                                  <div class="card .bg-info.bg-gradient">
                                            <div class="card-body" >
                                            {{--  Affichage bloc reprise  --}}
                                              <h4 class="card-title">Date de reprise</h4>
                                              @php
                                              foreach($Data_lesson['start_date'] as $dt){
                                               
                                                         
                                                            $date = new DateTime($dt);
                                                            
                                                            echo "<p>" ;
                                                            echo fetchDay($dt)." ".fetchjour($dt)." ".fetchMonth($dt)." ".fetchan($dt);
                                                            echo "</p>" ;
                                                            echo "\n";
                                                        

                                                            };
                                              @endphp
                                              <p class="card-text"><h5>Saison: {{$data->saison}}</h5></p>
                                            
                                            </div>

                                            

                                        </div>


                                  </div>
                                  
                                  
                                </div>

                                <div class=" flex-fill">     
                                    <div class="card" >
                                            <div class="card-body" >

                                            {{--  Affichage bloc horaires  --}}
                                              <h4 class="card-title">horaires</h4>
                                              
                                            
                                        @php
                                            $norepeat = TRUE ; // eviter de repeter les redondances d'informations a l'affichage
                                            $aff_heure = 0 ; // pour permettre l'affichage des heures de debut et de fin sur la meme ligne 
                                              if (count($Data_lesson['start_date'])>1) {  

                                                foreach($Data_lesson['start_date'] as $dt){

                                               
                                                $date = new DateTime($dt);
                                                echo "<p>" ; echo fetchDay($dt)." ".$date->format('G:i'); 
                                             

                                               $dt1 = $Data_lesson['end_date'][$aff_heure] ;

                                                      
                                                            $date = new DateTime($dt1);

                                                            echo " - ".$date->format('G:i');
                                                            echo "</p>" ; 
                                      
                                                            
                                                            $aff_heure = $aff_heure + 1 ;
                                                }


                              
                                                  
                                         }
                                                
                                           else{

                                                     
                                                      $date = new DateTime($dt); // recupere date timestamp de la database
                                                    
                                                      echo "<p>" ; echo fetchDay($dt)." ".$date->format('G:i') ; // use method format pour afficher les heures sans les secondes

                                                      echo " à ";
                                                      foreach($Data_lesson['end_date'] as $dt){

                                                       
                                                        $date = new DateTime($dt);
                                                      
                                                        echo " ".$date->format('G:i');
                                                        echo "</p>" ; 

                                                    
                                                      };

                                                      
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


                                        @endphp
                                        </div>

                                        </div>
                                </div>    

                                
                                    

                                        @endif
                                      @endforeach
                                      <div class="row d-flex justify-content-center">
                                        <h1> Descriptif de l'article</h1>
                                        <div class="card">
                                          <div class="card-body">
                                    
                                            @foreach($article as $at)
                                                    @if ($at->id_shop_article == $indice )
                                    
                                                    {!! $at->description !!}
                                    
                                                  
                                    
                                                    @endif
                                    
                                            @endforeach
                                    
                                          </div>
                                        </div>
                                      </div>
                                      

                                      </div>
                                    

                            @endif
                            @endforeach


    
                            {{--premier affichage concernant les produits sans professeur --}}

<br>

@foreach($article as $data)

@if($data->id_shop_article == $indice and $aff == 0) 

<div class="container mt-4 mb-5">
          <div class="d-flex">
            <div class="p-2 bg-light flex-fill">
            {{--  Affichage bloc produits (image) --}}
            <div class="card">
          
                      <div class="card-body"  >
                        <h4 class="card-title">produits</h4>
                        
                        
                            @foreach($shopService as $data1)

                                @if ($data->id_shop_article == $data1->id_shop_article)

                                    @php
                                          $aff = 0 ;
                                          $Data_lesson = (array) json_decode($data1->lesson,true);

                                          $Data_teacher = json_decode($data1->teacher,true);

                                          $Data_stock_actuel = json_decode($data1->stock_actuel,true);

                                          $Data_stock_ini = json_decode($data1->stock_ini,true);


                                         

                                          
                                    @endphp
                                    {{ $data->image }}
                                              
                                  

                      
                      </div>
          
                      </div>
          

                            





            </div>

            {{--  Affichage bloc prix  --}}
            <div class="p-2 bg-light flex-fill" >

            <div class="card" >
                      <div class="card-body "style="height: 10rem;">
                        <h4 class="card-title">Le prix</h4>
                        
                        <p class="card-text"><h6> {{$data->totalprice}}€</h6></p>
                      
                      </div>

            </div>



            </div>

            {{--  Affichage bloc inscription  --}}
            <div class="p-2 bg-secondary flex-fill" style="width:11rem;">

          <div class="card"  >
          <div class="card-body">
            <h4 class="card-title">Inscription</h4>
            <p class="card-text">Se connecter pour s'inscrire</p>
            <a href="#" class="card-link"><button type="button" class="btn btn-primary">Se connecter</button></a>
            
          </div>

                </div>



          </div>
            <div class="p-2 flex-fill"> 
              
                                            
          
          
          </div>
          </div>

  

                                        @endif
                                      @endforeach
                                      <div class="row d-flex justify-content-center">
                                        <h1> Descriptif de l'article</h1>
                                        <div class="card">
                                          <div class="card-body">
                                    
                                            @foreach($article as $at)
                                                    @if ($at->id_shop_article == $indice )
                                    
                                                    {!! $at->description !!}
                                    
                                                  
                                    
                                                    @endif
                                    
                                            @endforeach
                                    
                                          </div>
                                        </div>
                                      </div>
                                      

                                      </div>
                            @endif
                            @endforeach


<br>



{{--  Affichage Description avec plus de details --}}





</div>
</main>
@endsection
@extends('layouts.app')

@section('content')
<script src="./src/bootstrap-input-spinner.js"></script>

<div class="modal fade " id="commanderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-notify modal-info" role="document">
      <!--Content-->
      <div class="modal-content text-center" id="commanderModalContainer">
          
      </div>
      <!--/.Content-->
    </div>
</div>

@php
                    


                       



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

{{--premier affichage concernant les produits avec professeur --}}

@foreach($article as $data)

@if($data->id_shop_article == $indice and $aff == 1)  

<main id="main" class="main pt-3 vw-100 vh-100" style="background-image: url('{{asset("/assets/images/background.png")}}');">
  @if($messageContent)
      <div style="background-color: #fefefe" class="container mb-3 p-3 border rounded">
          <div class="row">
              <div class="col-12">
                  <!-- Affichez le contenu du champ Message -->
                  {!! $messageContent !!}
              </div>
          </div>
      </div>
  @endif
  <div class="container">
    @if (session('success'))
    <div class="alert alert-success m-3">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger m-3">
        {{ session('error') }}
    </div>
@endif
</div>
<div style="background-color:white;"  class="container rounded" >


          <div  class="row">
            <div class="widget-title col-12 d-flex justify-content-between align-items-center">
              <span>{{ $data->title }}</span>
              <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="fas fa-angle-left mr-2"></i> Retour
              </a>
            </div>
            <div class="col-md-2">
            <div class="card">
            {{--  Affichage bloc professeur (Nom et photo) --}}
                      <div class="card-body"  >
                        <h4 class="card-title">Professeurs :</h4>
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
                                                        echo '<img style="max-height: 100px" src='.$users->image.'>';
                                                        echo '<p class="card-text">'.$users->name." ".$users->lastname.'</p>';
                                                        $aff = 1;
                                                        
                                                  }
                                                }
                                            ;
                                          } ;
                                    @endphp
                                  

                      
                      </div>
          
                      </div>
          

                            





            </div>
            <div class="col-md-2" >
            {{--  Affichage bloc prix  --}}
            <div class="card" style="border:0px; box-shadow: none;" >
              <div  class="card-body"  style="background-color: white;     display: flow-root !important;  position: relative;" >
                <h4 class="card-title">Prix :</h4>
                <div id="priceToDisplay">
                @if($priceToDisplay != $data->totalprice)
                    <span style="text-decoration: line-through;">{{ number_format($data->totalprice, 2, ',', ' ') }} €</span>
                    <span  style="color: red; font-size: x-large; font-weight: bold;">{{ number_format($priceToDisplay, 2, ',', ' ') }} €</span>
                    <br>
                  @else
                    <span style="color: red; font-size: x-large; font-weight: bold;">{{ number_format($data->totalprice, 2, ',', ' ') }} €</span>
                @endif
                @if ($DescReduc != null)
                  <span class="px-2" style="color: red; font-size: small;">{{ $DescReduc }}</span>
                @endif
              </div>
                @if ($data->nouveaute == 1)
                  <img style="position: absolute;
                  top: 20;
                  right: 0;max-height:40px;" src="{{ asset("/assets/images/New_Admin.png") }}" alt="">
                @endif
                <br>
                <div style=" position: absolute;
                bottom: 0;
                right: 0;">
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
            

              <div class=" col-md-3">
                
              <div class="card"   >
                <div class="card-body " style="display: block !important; position :relative" >
                  {{--  Affichage bloc reprise  --}}
                  <h4 class="card-title">Date de reprise</h4>
                  @php
                      if (!empty($Data_lesson['start_date'])) {
                          // Trouver la date la plus tôt
                          $earliestDate = min($Data_lesson['start_date']);
                          
                          $date = new DateTime($earliestDate);
              
                          echo "<p style='align-self: flex-start !important;'>" ;
                          echo fetcchDayy($earliestDate)." ".fetchjour($earliestDate)." ".fetchMonth($earliestDate)." ".fetchan($earliestDate);
                          echo "</p>" ;
                      }
                  @endphp
                  <div style="position: absolute; bottom: 5px; right: 2px;">
                      <span style="font-size: medium; text-decoration: underline;">Saison:</span> <span style="font-size: small">{{$data->saison}}/{{$data->saison+1}}</span> 
                  </div>
              </div>
              

                        

                    </div>


              </div>

              <div class=" col-md-3">     
                <div class="card" style="border:0px; box-shadow: none;">
                        <div class="card-body" style="background-color: white; display: flow-root !important; " >

                        {{--  Affichage bloc horaires  --}}
                          <h4 class="card-title">Horaires</h4>
                          
                        
                    @php
                        $norepeat = TRUE ; // eviter de repeter les redondances d'informations a l'affichage
                        $aff_heure = 0 ; // pour permettre l'affichage des heures de debut et de fin sur la meme ligne 
                          if (count($Data_lesson['start_date'])>1) {  

                            foreach($Data_lesson['start_date'] as $dt){

                           
                            $date = new DateTime($dt);
                            echo "<p style='align-self: flex-start !important; font-weight:bold;'>" ; echo fetcchDayy($dt)." de ".$date->format('G:i'); 
                         

                           $dt1 = $Data_lesson['end_date'][$aff_heure] ;

                                  
                                        $date = new DateTime($dt1);

                                        echo " - ".$date->format('G:i');
                                        echo "</p>" ; 
                  
                                        
                                        $aff_heure = $aff_heure + 1 ;
                            }


          
                              
                     }
                            
                       else{
                        foreach($Data_lesson['start_date'] as $dt){
                                 
                                  $date = new DateTime($dt); // recupere date timestamp de la database
                                
                                  echo "<p style='align-self: flex-start !important; font-weight:bold;'>" ; echo fetcchDayy($dt)." de ".$date->format('G:i') ; // use method format pour afficher les heures sans les secondes

                                  echo " à ";
                                  foreach($Data_lesson['end_date'] as $dt){

                                   
                                    $date = new DateTime($dt);
                                  
                                    echo " ".$date->format('G:i');
                                    echo "</p>" ; 

                                
                                  };

                                  
                                  };

                                }
                                  $counter = 1;
foreach($rooms as $room){
    foreach($Data_lesson['room'] as $r){
        if($r == $room->id_room){
            echo"</p>";
            echo " <b style='align-self: flex-start !important;'>lieu ";
            if(count($Data_lesson['room']) > 1) {
                echo $counter;
                $counter++;
            }
            echo ": </b>" ;
            echo "<a class='a' style='font-size: small' href='$room->map' target='_blank'>" . $room->name .  "</a>";
        }
    }
}




                    @endphp
                    </div>

                    </div>
            </div>    
            @if (Auth::check())
            <div class="col-md-2" >
              {{--  Affichage bloc prix  --}}
              <div class="card" >
                <div class="card-body"   style="display: block !important;">
                  <h4 class="card-title">Inscrire</h4>
                  @if ($data->stock_actuel <= 0 )
                    @if ($data->type_article == 0)
                        <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Indisponible/Complet</span>
                    @elseif ($data->type_article == 1)
                        <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Séance complète</span>
                    @elseif ($data->type_article == 2)
                        <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Indisponible/Complet</span>
                    @endif
                  @else
                      @csrf
                      <select onchange="updatePriceToDisplay()" class="border mb-4 col-12 col-md-11 select-form @error('buyers') is-invalid @enderror" name="buyers" id="buyers" autocomplete="buyers" autofocus role="listbox" data-style='btn-info'>

                        @foreach ($selectedUsers as $user)
                            <option value="{{ $user->user_id }}">{{ $user->lastname }} {{ $user->name }}</option>
                          @endforeach
                      </select>
                      <script>
                        function updatePriceToDisplay() {
                    var select = document.getElementById("buyers");
                    var selectedUserId = select.options[select.selectedIndex].value;
                    var url = "/get-reduced-price/" + selectedUserId + "/" +  {{ $id }} ;
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                    var reducedPrice = JSON.parse(this.responseText);
                    var priceHTML = "";
                    
                    if ({{ $data->totalprice }} != reducedPrice) {
                    priceHTML = "<span style=\"text-decoration: line-through;\">";
                    priceHTML += ({{ $data->totalprice }}).toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
                    priceHTML += " </span>";
                    priceHTML += "<span style=\"color: red; font-size: x-large; font-weight: bold;\">";
                    priceHTML += reducedPrice.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
                    priceHTML += " </span>";
                    priceHTML += "<span class='px-2' style='color: red; font-size: small;'>";
                    priceHTML += "{{ $DescReduc }}";
                    priceHTML += " </span>";
                    } else {
                    priceHTML += priceHTML += '<span style="color: red; font-size: x-large; font-weight: bold;">' + ({{ $data->totalprice }}).toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
                    priceHTML += " </span>";
                    
                    }
                    
                    
                    document.getElementById("priceToDisplay").innerHTML = priceHTML;
                    }
                    };
                    xhttp.open("GET", url, true);
                    xhttp.send();
                    }
                    
                    
                    
                    
                         </script>
                        
                      @if ($coursVente->value == 0 && Auth::user()->role < 90 )
                        <p style="font-weight:bold">Inscriptions inaccessibles actuellement</p>
                      @else
                        <button data-shop-id="{{ $data->id_shop_article }}" class="commanderModal btn btn-primary">Commander</button>
                      @endif
                  @endif
                  </span>
                </div>
  
              </div>
              </div>
              @else
              <div class="col-md-2 " >
                <div class="card" >
                  <div class="card-body"   style="display: block !important;">
                <h5><u>Inscription :</u></h5>Se connecter pour s'inscrire<p><a href="{{ route("login") }}" class="btn btn-primary "><span >&nbsp;Se connecter</span></a><br>&nbsp;</p>
              </div>
            </div>
            </div>
              @endif
          </div>
     

                                        @endif
                                      @endforeach
                                      <div class="row d-flex justify-content-center">
                                        <h1> Descriptif de l'article</h1>
                                        <div class="card">
                                          <div style="align-items: start !important;" class="card-body">
                                    
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

</main>

@foreach($article as $data)

@if($data->id_shop_article == $indice and $aff == 0) 

<main id="main" class="main pt-3" style="min-height:100vh; background-image: url('{{asset("/assets/images/background.png")}}');">
  @if($messageContent)
      <div style="background-color: #fefefe" class="container mb-3 p-3 border rounded">
          <div class="row">
              <div class="col-12">
                  <!-- Affichez le contenu du champ Message -->
                  {!! $messageContent !!}
              </div>
          </div>
      </div>
      @endif
      <div class="container">
      @if (session('success'))
      <div class="alert alert-success m-3">
          {{ session('success') }}
      </div>
  @endif
  @if (session('error'))
      <div class="alert alert-danger m-3">
          {{ session('error') }}
      </div>
  @endif
</div>
  <div style="background-color:white;"  class="container rounded" >
    
  
            <div  class="row ">
              <div class="widget-title col-12 d-flex justify-content-between align-items-center">
                <span>{{ $data->title }}</span>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                  <i class="fas fa-angle-left mr-2"></i> Retour
                </a>
              </div>
              <div class="col-md-3">
              <div class="card">
              {{--  Affichage bloc professeur (Nom et photo) --}}
                        <div class="card-body"  >
                          <h4 class="card-title mb-2-5">Produit :</h4>
                          <img style="max-height: 120px" src="{{ $data->image }}" alt="">
                              
  
                        
                        </div>
            
                        </div>
            
  
                              
  
  
  
  
  
              </div>
              <div class="col-md-4" >
              {{--  Affichage bloc prix  --}}
              <div class="card" style="border:0px; box-shadow: none;" >
                <div class="card-body"  style="background-color: white;     display: flow-root !important; position :relative " >
                  <h4 class="card-title">Prix :</h4>
                  @if($priceToDisplay != $data->totalprice)
                    <span style="font-size: large;">Prix d'origine:</span><br>
                    <span style="text-decoration: line-through;">{{ number_format($data->totalprice, 2, ',', ' ') }} €</span><br>
                    <span id="priceToDisplay" style="color: red; font-size: x-large; font-weight: bold;">{{ number_format($priceToDisplay, 2, ',', ' ') }} €</span>
                  @else
                    <span style="color: red; font-size: x-large; font-weight: bold;">{{ number_format($data->totalprice, 2, ',', ' ') }} €</span>
                  @endif
                  @if ($DescReduc != null)
                  <span class="px-2" style="color: red; font-size: small;">{{ $DescReduc }}</span>
                  @endif
                  @if ($data->nouveaute == 1)
                    <img style="position: absolute;
                    top: 20;
                    right: 0;max-height:40px;" src="{{ asset("/assets/images/New_Admin.png") }}" alt="">
                  @endif
                  <br>
                  <div style=" position: absolute;
                bottom: 0;
                right: 0;">
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
  
              
  
                {{-- <div class=" col-md-3">
                  <div class="card" >
                    <div class="card-body"   style="display: block !important;">
                  <h4 class="card-title">Choix</h4>
                    @if ($data->stock_actuel <= 0)
                      @if ($data->type_article == 0)
                          <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Indisponible/Complet</span>
                      @elseif ($data->type_article == 1)
                          <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Séance complète</span>
                      @elseif ($data->type_article == 2)
                          <span style="color:red;"><i class="fas fa-times-circle" style="color:red;"></i> Indisponible/Complet</span>
                      @endif
                    @else
                    @if ($data->type_article == 2)
                      <div class="form-group mb-1">
                        <label for="typeNumber">Quantité</label>
                        <input class="border border-dark col-3 select-form p-1  my-1" type="number" id="qte" name="qte" value="1"/>
                      </div>
                    @endif
                    @if ($data->type_article == 2 && !empty($declinaisons))
                        <div class="form-group col-10  mb-3">
                            <label class=" form-label" for="declinaison">Déclinaison :</label>
                            <select class="p-1"  id="declinaison" name="declinaison">
                                @foreach ($declinaisons as $index => $declinaison)
                                    @foreach ($declinaison as $id => $info)
                                          @if ($info['stock_actuel_d'] > 0)
                                            <option style=" font-size:15px;" class="text-dark p-2" value="{{ $id }}">{{ $info['libelle'] }} </option>
                                        @else
                                            <option style="font-size:15px;" class="text-muted p-2"  value="{{ $id }}" disabled>{{ $info['libelle'] }} </option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div></div></div>
  @endif --}}
                    
              @guest
                <div class="col-md-5" >
                  {{--  Affichage bloc prix  --}}
                  <div class="card" >
                    <div class="card-body"   style="display: block !important;">
                      @if ($data->type_article == 2)
                        <h4 class="card-title">Commander</h4>
                      @else
                        <h4 class="card-title">Inscrire</h4>
                      @endif
                          <p>Se connecter pour commander</p>
                          <a  href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                      </span>
                    </div>
      
                  </div>
                  </div>
              @else
                <div class="col-md-5" >
                {{--  Affichage bloc prix  --}}
                <div class="card" >
                  <div class="card-body"   style="display: block !important;">
                    @if ($data->type_article == 2)
                      <h4 class="card-title">Commander</h4>
                    @else
                      <h4 class="card-title">Inscrire</h4>
                    @endif
                    @if (count($selectedUsers) > 0)
                    <select onchange="updatePriceToDisplay()" class="border mb-4 col-12 col-md-11 select-form @error('buyers') is-invalid @enderror" name="buyers" id="buyers" autocomplete="buyers" autofocus role="listbox" data-style='btn-info'>

                      @foreach ($selectedUsers as $user)
                          <option value="{{ $user->user_id }}">{{ $user->lastname }} {{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button data-shop-id="{{ $data->id_shop_article }}" class="commanderModal btn btn-primary">Commander</button>
                @else
                    <p>Votre famille ne correspond pas à cet article.</p>
                @endif
                        <script>
                          function updatePriceToDisplay() {
                      var select = document.getElementById("buyers");
                      var selectedUserId = select.options[select.selectedIndex].value;
                      var url = "/get-reduced-price/" + selectedUserId + "/" +  {{ $id }} ;
                      var xhttp = new XMLHttpRequest();
                      xhttp.onreadystatechange = function() {
                      if (this.readyState == 4 && this.status == 200) {
                      var reducedPrice = JSON.parse(this.responseText);
                      var priceHTML = "";
                      
                      if ({{ $data->totalprice }} != reducedPrice) {
                      priceHTML = "<span style=\"text-decoration: line-through;\">";
                      priceHTML += ({{ $data->totalprice }}).toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
                      priceHTML += " </span>";
                      priceHTML += "<span style=\"color: red; font-size: x-large; font-weight: bold;\">";
                      priceHTML += reducedPrice.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
                      priceHTML += " </span>";
                      priceHTML = "<span class='px-2' style='color: red; font-size: small;'>";
                      priceHTML += "{{ $DescReduc }}";
                      priceHTML += " </span>";
                      } else {
                      priceHTML += priceHTML += '<span style="color: red; font-size: x-large; font-weight: bold;">' + ({{ $data->totalprice }}).toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
                      priceHTML += " </span>";
                      
                      }
                      
                      
                      document.getElementById("priceToDisplay").innerHTML = priceHTML;
                      }
                      };
                      xhttp.open("GET", url, true);
                      xhttp.send();
                      }
                      
                      
                      
                      
                           </script>
                          
                          
                    </span>
                  </div>
    
                </div>
                </div>
              @endguest
              


            </div>
       
  
                                          
                                        <div class="row d-flex justify-content-center">
                                          <h1> Descriptif de l'article</h1>
                                          <div class="card">
                                            <div style="align-items: start !important;" class="card-body">
                                      
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
  </main>
  

    

@endsection
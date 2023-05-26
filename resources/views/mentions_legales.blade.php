@extends('layouts.app')

@section('content')
<main style="min-height:100vh; background-image: url('{{asset("/assets/images/background.png")}}" class="main" id="main">

<div class="container">
<div  class="row">

  <div style="    border: solid !important;
  border-width: 1px !important;
  border-color: grey !important;
  margin-bottom: 10px !important;
  box-shadow: 3px 3px 3px #5c5c5c !important;
  " class="card shadow my-4 p-0">
                      <div style="background-color: #A9BCF5 !important" class="card-header py-2  d-flex justify-content-between">
                        <div class="col-9 d-flex align-items-center">
                          <h6 class="m-0 font-weight-bold text-primary"> Mentions légales </h6></div>
                <span x style="margin-right: 5px; vertical-align: sub;">
                    <a style="text-decoration:none" href="https://www.gym-concordia.com/index.php/category//1">
                        <img style="height:26px" src="">
                    </a>

                 
                </span>
               
    </div>

    <div class="row p-3" style="margin: 0px;"><div class="row titleml " style="margin: 0px;"><u><strong>Documents Administratifs</strong></u></div>
    @foreach($parametre as $pt)
<div class="row containtiles" style="margin: 0px;">
  <div class="col-md-3 col-sm-6">
    <a target="_blank" href="{{ $pt->fichier_inscription3 }}">
      <div class="tile bg-darkBlue fg-white">
      <button type="button" class="btn btn-primary btn-block btn-lg" style="min-height: 70px;">Statut de l'association</button>
     
      </div>
    </a>
  </div>
  <div class="col-md-3 col-sm-6">
    <a target="_blank" href="{{ $pt->fichier_inscription2 }}">
      <div class="tile bg-darkBlue fg-white">
       
        <button type="button" class="btn btn-primary btn-block btn-lg" style="min-height: 70px;">Règlement Intérieur</button>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-sm-6">
    <a target="_blank" target="_blank" href="{{ $pt->fichier_inscription1 }}">
      <div class="tile bg-darkBlue fg-white">
      
        <button type="button" class="btn btn-primary btn-block btn-lg" style="min-height: 70px;">Notice d'assurance</button>
      </div>
    </a>
  </div>
  <div class="column medium-3 small-6">
  </div>
  @endforeach
</div>

<br>

<div class="row titleml mt-3" style="margin: 0px;"><u><strong>Documents Santé</strong></u></div>
<div class="row containtiles" style="margin: 0px;">
  <div class="col-md-3 col-sm-6">
    <a target="_blank" href="{{ asset('uploads/Fichiers/FFGym-Quest-Mineurs-2022-2023.pdf') }}">
      <div class="tile bg-darkBlue fg-white">
      
        <button type="button" class="btn btn-primary btn-block btn-lg" style="min-height: 70px;">Questionnaire pour les Mineurs</button>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-sm-6">
    <a  href="{{ asset('uploads/Fichiers/FFGym-Quest-Majeurs-2022-2023.pdf') }}" target="_blank">
      <div class="tile bg-darkBlue fg-white">
       
        <button type="button" class="btn btn-primary btn-block btn-lg" style="min-height: 70px;">Questionnaire pour les Majeurs</button>
      </div>
    </a>
  </div>
  <div class="column medium-3 small-6">
  </div>
</div>

<br>

<div class="row titleml mt-3" style="margin: 0px;"><u><strong>Informations Juridiques</strong></u></div>
<div class="row containtiles" style="margin: 0px;">
  <div class="col-md-3 col-sm-6">
    <a target="_blank" href="{{route('index_politique')}}">
      <div class="tile bg-darkBlue fg-white">
      <button type="button" class="btn btn-primary btn-block btn-lg" style="min-height: 70px;">Politique de Confidentialit&eacute;</button>
       
      </div>
    </a>
  </div>
  <div class="col-md-3 col-sm-6">
    <a target="_blank" href="">
      <div class="tile bg-darkBlue fg-white">
        
        <button type="button" class="btn btn-primary btn-block btn-lg" style="min-height: 70px;">Conditions d'Utilisation</button>
      </div>
    </a>
  </div>
</div>

<br>

<div class="row titleml mt-3" style="margin: 0px;" ><u><strong>Valeurs et Chartes</strong></u></div>

<div class="row containtiles" style="margin: 0px;border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;">
  <div class="col-md-3 col-sm-6">
    <a target="_blank" href="{{ asset('uploads/Fichiers/Valeurs%20et%20Chartes%20-%20FFGym.pdf') }}"> 
      <div class="tile bg-darkBlue fg-white">
        
        <button type="button" class="btn btn-primary btn-block btn-lg" style="min-height: 70px;">Valeurs et Chartes de la FFGym</button>
        
      </div>
    </a>
  </div>
 
  <div class="col-md-3 col-sm-6">
    <a target="_blank" href="{{ asset('uploads/Fichiers/Eco%20Gym.pdf') }}">
      <div class="tile bg-darkBlue fg-white">
        
        <button type="button" class="btn     btn-primary btn-block btn-lg"  style="min-height: 70px;">Charte EcoGym FFGym</button>
        
      </div>
    </a>
  </div>
  <div class="col-md-3 col-sm-6">
    <a target="_blank" href="{{ asset('uploads/Fichiers/Charte%20Ethique%20et%20D%C3%A9ontologie%20ffgym.pdf') }}">
      <div class="tile bg-darkBlue fg-white">
        <span class="tile-label"></span>
        <button type="button" class="btn  btn-primary btn-block btn-lg" style="min-height: 70px;">Charte de l&#39;Ethique et&nbsp;la D&eacute;ontologie FFGym</button>
      </div>
    </a>
  </div>
  <div class="col-md-3 col-sm-6">
    <a target="_blank" href="{{ asset('uploads/Fichiers/Developpement%20durable.pdf') }}">
      <div class="tile bg-darkBlue fg-white">
        <span class="tile-label"></span>
        <button type="button" class="btn   btn-primary btn-block btn-lg"  style="min-height: 70px;">Valeurs et Chartes du D&eacute;veloppement Durable</button>
      </div>
    </a>
  </div>
</div>




 </div>      </div>  </div>   </div>    
</main>
@endsection
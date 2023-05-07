@extends('layouts.template')

@section('content')

@php

require_once(app_path().'/fonction.php');

$saison_active = saison_active() ;

@endphp
<main id="main" class="main">
        @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif



<div class="container">

            <div class="row">

                <div class="col-md-4 d-flex justify-content-end"><label> Saison </label></div>
                <div class="col-md-8">  
                    
       
                        <form class="row" action="{{ route('save_history') }}" method="POST" >
                            @csrf
                            <div class="col-6">
                            <select class="form-control" name="saison" id="saison">
                            
                                    @foreach($saison_list as $data)

                                                    <option value="{{$data->saison}}">{{$data->saison}} - {{$data->saison + 1 }}</option>
                                    
                                    
                                    @endforeach

                            </select>
                        
                        </div> 
                       
                      
   </div>
   <br>
   

<hr>
<i> cliquez sur le bouton afin de pouvoir effectuer une sauvegarde des membres de l'association sur l'année selectionnée</i>
<br>
<br>
   
   </div>
   <div class="row">

   

   <div class="col-md-4">
   </div>
   
   <div class="col-md-4">
   <button  type="submit" class="btn btn-primary btn-lg">Sauvegarder des membres</button>
 
   </div>
             
   <div class="col-md-4">
   </div>

    </div>

    </form>

    <div class="row">

   

<div class="col-md-4">
<a href="{{route('consult_historique')}}"><button  type="button" class="btn btn-primary ">   Consulter les historiques</button> </a>

</div>

<div class="col-md-4">

</div>
          
<div class="col-md-4">
</div>

 </div>

</div>













</main>


    


@endsection

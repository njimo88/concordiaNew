@extends('layouts.app')

@section('content')
<div class="wave-header">
    <div class="contenu ">
        <h1>DE VRAIES PERSONNES
            VOUS <br> AIDENT À FAIRE 
            DES PROGRÈS RÉELS.</h1>
        <p>Bonjour et bienvenue sur Gym Concordia
        </p>
            <img src="{{ asset("assets/images/logo.png") }}" class="laptop">
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#fff" fill-opacity="1" d="M0,64L120,90.7C240,117,480,171,720,202.7C960,235,1200,245,1320,250.7L1440,256L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path></svg>
</div>
  <div class="container">
 
        <div class="row">

       

        @foreach($a_requete1 as $i)
                
          
            @php

            /* we use json_decode to make this transformation : JSON arrays become PHP numeric arrays */

                $a_monjson = json_decode($i->categorie2) ;
               
              
            @endphp 
   



                    @foreach($a_monjson as $j)
                    
                            
                        @if(in_array($j,[$a_result]))
                    
                               
                                @foreach($a_categorie2 as $c)   

                                    @if($c->Id_categorie2 == $a_result)
                                            <div class="container">

                                            <a href="{{route('A_blog_par_categorie2', ['id' => $a_result])}}"> <img src='{{ $c->image }}'>  </a>

                                           </div> 
                                    @endif

                                @endforeach
                     
                   
                        <div class="container"> 
                                <div class="page-header">
                                 <h1>  {{$i->titre}}</h1>      
                                </div>
                                                
                        </div> 
     
                         <div class="well" >{!! html_entity_decode($i->contenu) !!}
                              
                        </div>
                        <hr>
                        <div class="container">
                        <p>{{$i->date_post}}</p>
                        </div>
                @endif

                
                
            @endforeach 
           
           
               
        @endforeach
      
		
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  
@endsection
@extends('layouts.app')

@section('content')

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
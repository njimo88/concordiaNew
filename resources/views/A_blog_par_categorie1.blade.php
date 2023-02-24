<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Laravel 9 load more page scroll</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
  
<body>
  
  <div class="container">
 
        <div class="row">

       

        @foreach($a_requete1 as $i)
                
          
            @php

            /* we use json_decode to make this transformation : JSON arrays become PHP numeric arrays */

                $a_monjson = json_decode($i->categorie1) ;
               
              
            @endphp 
   



                    @foreach($a_monjson as $j)
                    
                            
                        @if(in_array($j,[$a_result]))
                    
                               
                                @foreach($a_categorie1 as $c)   

                                    @if($c->Id_categorie1 == $a_result)
                                            <div class="container">

                                            <a href="{{route('A_blog_par_categorie1', ['id' => $a_result])}}"> <img src='{{ $c->image }}'>  </a>

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

</body>


</html>
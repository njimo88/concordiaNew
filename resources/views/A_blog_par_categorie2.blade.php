@extends('layouts.app')

@section('content')


<main id="main" class="main">

   

                
                                    @foreach($a_requete1 as $i)

                                    <div class="container">

                                    <div class="row"> 

                                    <div class="card">
                                          <div class="card-header">

                                                            @php

                                            /* we use json_decode to make this transformation : JSON arrays become PHP numeric arrays */

                                                
                                                $a_monjson = json_decode($i->categorie2) ;


                                            @endphp


                                            
                                @foreach($a_monjson as $j)
                                    
                                    @if(in_array($j,[$a_result]))
                                
                                    
                                            @foreach($a_categorie2 as $c)   

                                                @if($c->Id_categorie2 == $a_result)
                                                
                                        
                                                        <h6 class="m-0 font-weight-bold text-primary">
                                                                        
                                                                        <a href="{{route('A_blog_par_categorie2', ['id' => $a_result])}}"> <img src='{{ $c->image }}'>  </a>
                                                        </h6>
                                                @endif

                                            @endforeach


                                    @endif

                                @endforeach
                                          </div>


                                          <div class="card-body">

                                            <h5 class="card-title"> {{$i->titre}} </h5>
                                           
                                            <div class="well"> {!! html_entity_decode($i->contenu) !!} </div>

                                            </div>          

                                            <p class="card-title">{{$i->date_post}}</p> 

                                </div>




                </div>


    </div>

    @endforeach

   

{!!   $a_requete1->links(pagination::bootstrap-4) !!}




</main>


@endsection



    


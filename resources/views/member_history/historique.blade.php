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

              
                           
                                
                
                            <form class="row" action="{{ route('consult_historique_post') }}" method="POST" >
           @csrf
           <div class="col-4">
       
        </div> 
           <div class="col-4">
         <select class="form-control" name="saison" id="saison">
         
                  @foreach($saison_list as $data)

                                  <option value="{{$data->saison}}">{{$data->saison}} - {{$data->saison + 1 }}</option>
                  
                  
                   @endforeach

         </select> 
        </div> 
        <div class="col-4">
           
        <div class="row">
             <div class="col-2"></div>
            <div class="col-10"> <button class=" mt-1 btn btn-sm btn-primary" type="submit" id="hide-row-btn" >Accéder</button></div>
        </div>
        </div> 
       
       
       </form>
       <div><a href="{{route('index')}}"> <button class=" mt-1 btn btn-sm btn-primary" id="hide-row-btn">retour</button> </a></div>
       
<hr>

<br>


@if(session('submitted'))


        <div class="row">


               
<div class="col-md-12">


<br>

    
                                <table class="table table-hover">
                                    <thead style="color:black, background-color:black">
                                        <tr>

                                        
                                        

                                        <th scope="col">Nom</th>
                                        <th scope="col">Prenom</th>
                                        <th scope="col">date_naissance</th>
                                        <th scope="col">Saison</th>
                                        

    
                                  
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($history as $r)
                
                                        <tr>

                                      
                                        
                                        
                                                <td> {{$r->nom}}</td>
                                                <td> {{$r->prenom}}</td>
                                                <td> {{$r->date_naissance}}</td>
                                                <td>{{$r->saison}} </td>


                          
                          
                                         </tr>

                                      
                        
                                        @endforeach
                                       
                                      
                                    </tbody>


                                    </table>


</div>


@endif






        </div>
  

   

  

</div>













</main>


    


@endsection

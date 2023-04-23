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



   
@if (auth()->user()->role == 40 || auth()->user()->role == 30 )


@php 
        $id_teacher = auth()->user()->user_id ;
        $my_articles = [] ;
        $add = [] ;
@endphp



@if(session('submitted'))

<div class="row">
<div class="col-md-4 d-flex justify-content-end"><label> Saison </label></div>
<div class="col-md-8">  
       
       
       <form class="row" action="{{ route('include-page') }}" method="POST" >
           @csrf
           <div class="col-6">
         <select class="form-control" name="saison" id="saison">
         
                  @foreach($saison_list as $data)

                                  <option value="{{$data->saison}}" {{ $data->saison == $saison ? 'selected' : '' }} >{{$data->saison}} - {{$data->saison + 1 }}</option>
                  
                  
                   @endforeach

         </select> </div> <div class="col-6"> <button class=" mt-1 btn btn-sm btn-primary" type="submit" id="hide-row-btn" >Accéder</button></div>
       
       
       </form>
   </div>
<hr>
   
  
   </div>





                <div class="d-grid gap-2">
            @foreach($shop_article_lesson_choisie as $data)


            @php $add [] = (array)json_decode($data->teacher) ; 

                  if (isset($add)) {
                          foreach ($add as $teacherArray) {
                                    foreach($teacherArray as  $t){
                                    
                                      if ($id_teacher === $t){
                                                $my_articles [] = $data->id_shop_article ;

            @endphp
            <input readonly  onclick="toggleElement('{{ $data->id_shop_article }}')"  class="btn btn-secondary"  value="{{$data->title}}   {{(int)$data->stock_ini - (int)$data->stock_actuel }}/ {{$data->stock_ini}}" >
                          
            <div id="my-element-{{ $data->id_shop_article }}" style="display: none;">



           
                                
                              
                 <div id="content" >


                
                                          
            <div class="row">  
           
                <div class="col-4 ">  

                  <form action="{{route('enregistrer_appel',['id'=>$data->id_shop_article ])}}" method="POST">
                                                      @csrf
                  <button type="submit" class="btn btn-success">Valider l'appel</button> 

                </div>
                <div class="col-4 ">
               
               
                                                      <input type="date" class="form-control m-0" name="date_appel" value="<?php echo date('Y-m-d'); ?>">
                                                     
                                          
                </div>
               
                <div class="col-4 d-flex justify-content-center" ><button type="button" class="btn btn-secondary">  <a  href="{{route('historique_appel',$data->id_shop_article)}}">Historique des appels</a></button></div>

            </div>
<br>

                                      <table class="table table-hover" style="background-color:green;"> 
                                        <tbody>
                                      @foreach($users_saison_choisie as $dt)

                                                  @if($data->id_shop_article == $dt->id_shop_article)

                                                                 
                                                 
                                                        
                                                <tr>
                                                    
                                                    <td>
                                                    <div class="form-check">
                                                                                        <input name="user_id[]" value="{{$dt->user_id}}" hidden>
                                                                                         
                                                                                       
                                                                                         
                                                                                          <input class="form-check-input" type="checkbox" name="marque_presence[{{$dt->user_id}}]" value=1 id="myCheckbox">
           
                                                                                      


                                                                                          <label class="form-check-label" for="flexCheckDefault">
                                                                                                {{ $dt->name}}  {{$dt->lastname}}
                                                                                        </label>
                                                                                </div>
                                                    
                                                    </td>
                                                    <td>
                                                    <i class="fas fa-eye openmodal" style="color:blue;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-user-id="{{$dt->user_id}}"></i> 
                                                    </td>
                                                    <td>
                                                        <div class="col-6 col-md-2"> {{ $dt->phone}} </div>
                                                    </td>
                                                    <td>
                                                    {{ $dt->birthdate}} 
                                                    </td>
                                                    <td><i class="fas fa-certificate"></i><i class="fas fa-graduation-cap"></i></td>
                                            


                                                  </tr>
                                                


                                                  @endif

                                      @endforeach
                                      </form>
                                      </tbody>
                                                    </table>

                                    
                                    </div>
                                
                               
   
            </div>
                
  @php
                  break;
              }
                
                  }

                  }

          }
                
                  $add = [] ;
  @endphp


            
           
           

            @endforeach
            </div>


<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="display_info_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informations</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="just_display">
        ...

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      
      </div>
    </div>
  </div>
</div>



</div>

@else



<div class="row">
<div class="col-md-4 d-flex justify-content-end"><label> Saison </label></div>
<div class="col-md-8">  
       
       
       <form class="row" action="{{ route('include-page') }}" method="POST" >
           @csrf
           <div class="col-6">
         <select class="form-control" name="saison" id="saison">
         
                  @foreach($saison_list as $data)

                                  <option value="{{$data->saison}}" {{ $data->saison == $saison_active ? 'selected' : '' }} >{{$data->saison}} - {{$data->saison + 1 }}</option>
                  
                  
                   @endforeach

         </select> </div> <div class="col-6"> <button class=" mt-1 btn btn-sm btn-primary" type="submit" id="hide-row-btn" >Accéder</button></div>
       
       
       </form>
   </div>
<hr>
   
  
   </div>



                <div class="d-grid gap-2">
                @foreach($shop_article_lesson as $data)


                        @php $add [] = (array)json_decode($data->teacher) ; 

                              if (isset($add)) {
                                      foreach ($add as $teacherArray) {
                                                foreach($teacherArray as  $t){
                                                
                                                  if ($id_teacher === $t){
                                                            $my_articles [] = $data->id_shop_article ;

                        @endphp
                        <input readonly  onclick="toggleElement('{{ $data->id_shop_article }}')"  class="btn btn-secondary"  value="{{$data->title}}   {{(int)$data->stock_ini - (int)$data->stock_actuel }}/ {{$data->stock_ini}}" >
                                       <div id="my-element-{{ $data->id_shop_article }}" style="display: none;">



                        



                            <div id="content">


                                          
<div class="row">  

          @php $var = $data->id_shop_article  ;  @endphp
         

          <div class="col-4 ">
              <form action="{{route('enregistrer_appel',$var)}}" method="POST">
                          
                          @csrf
                <button type="submit" class="btn btn-success">Valider l'appel</button> 
          
          </div>
            <div class="col-4"> 
                    
                                                  <input type="date" class="form-control m-0" name="date_appel" value="<?php echo date('Y-m-d'); ?>">
                                                  
                                      
            </div>
            
            <div class="col-4 d-flex justify-content-center"><button type="button" class="btn btn-secondary">  <a  href="{{route('historique_appel',$data->id_shop_article)}}">Historique des appels</a></button></div>

</div>
<br>

                                      <table class="table table-hover" style="background-color:green;"> 
                                        <tbody>
                                        @php $i= 0 ; @endphp
                                      @foreach($users_saison_active as $dt)

                                                  @if($data->id_shop_article == $dt->id_shop_article)

                                                       
                                                 
                                                        
                                                <tr>
                                                    
                                                    <td>
                                                    <div class="form-check">
                                                                                        <input name="user_id[]" value="{{$dt->user_id}}" hidden>
                                                                                         
                                                                                       
                                                                                         
                                                                                          <input class="form-check-input" type="checkbox" name="marque_presence[{{$dt->user_id}}]" value=1 id="myCheckbox">
           
                                                                                      


                                                                                          <label class="form-check-label" for="flexCheckDefault">
                                                                                                {{ $dt->name}}  {{$dt->lastname}}
                                                                                        </label>
                                                                                </div>
                                                    
                                                    </td>
                                                    <td>
                                                    <i class="fas fa-eye openmodal" style="color:blue;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-user-id="{{$dt->user_id}}"></i> 
                                                    </td>
                                                    <td>
                                                        <div class="col-6 col-md-2"> {{ $dt->phone}} </div>
                                                    </td>
                                                    <td>
                                                    {{ $dt->birthdate}} 
                                                    </td>
                                                    <td><i class="fas fa-certificate"></i><i class="fas fa-graduation-cap"></i></td>
                                            


                                                  </tr>
                                                


                                                  @endif

                                      @endforeach

                                      </form>

                                      </tbody>
                                                    </table>

                                    
                                    </div>
                                














                        </div>
                            
                        @php
                              break;
                          }
                            
                              }

                              }

                        }
                            
                              $add = [] ;
                        @endphp






            @endforeach
            </div>
          




<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="display_info_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informations</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="just_display">
        ...

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      
      </div>
    </div>
  </div>
</div>









    @endif
    

</div>

<script>
    function toggleElement(id) {
    var element = document.getElementById('my-element-' + id);
    if (element.style.display === 'none') {
        element.style.display = 'block';
    } else {
        element.style.display = 'none';
    }
}

</script>



@elseif (auth()->user()->role == 90 || auth()->user()->role == 100)


@if(session('submitted'))

`     <div class="row">
<div class="col-md-4 d-flex justify-content-end"><label> Saison </label></div>
<div class="col-md-8">  
       
       
       <form class="row" action="{{ route('include-page') }}" method="POST" >
           @csrf
           <div class="col-6">
         <select class="form-control" name="saison" id="saison">
         
                  @foreach($saison_list as $data)

                                  <option value="{{$data->saison}}" {{ $data->saison == $saison ? 'selected' : '' }} >{{$data->saison}} - {{$data->saison + 1 }}</option>
                  
                  
                   @endforeach

         </select> </div> <div class="col-6"> <button class=" mt-1 btn btn-sm btn-primary" type="submit" id="hide-row-btn" >Accéder</button></div>
       
       
       </form>
   </div>
<hr>
   
  
   </div>










      
      <div class="d-grid gap-2">
  @foreach($shop_article as $data)
 
  <input readonly  onclick="toggleElement('{{ $data->id_shop_article }}')"  class="btn btn-secondary"  value="{{$data->title}}   {{(int)$data->stock_ini - (int)$data->stock_actuel }}/ {{$data->stock_ini}}" >
                              <div id="my-element-{{ $data->id_shop_article }}" style="display: none;">

                                      <div id="content" >


                                          
<div class="row">  

    <div class="col-4">  
    <form action="{{route('enregistrer_appel',['id'=>$data->id_shop_article])}}" method="POST">
                                      @csrf
      <button type="submit" class="btn btn-success">Valider l'appel</button> 
    
    </div>
    <div class="col-4"> 
             
                                          <input type="date" class="form-control m-0" name="date_appel" value="<?php echo date('Y-m-d'); ?>">
                                         
                              
    </div>
   
    <div class="col-4 d-flex justify-content-center"><button type="button" class="btn btn-secondary">  <a  href="{{route('historique_appel',$data->id_shop_article)}}">Historique des appels</a></button></div>

</div>
<br>

                                      <table class="table table-hover" style="background-color:green;"> 
                                        <tbody>
                                      @foreach($users_saison_choisie as $dt)

                                                  @if($data->id_shop_article == $dt->id_shop_article)


                                                 
                                                        
                                                <tr>
                                                    
                                                    <td>
                                                    <div class="form-check">
                                                                                        <input name="user_id[]" value="{{$dt->user_id}}" hidden>
                                                                                         
                                                                                       
                                                                                         
                                                                                          <input class="form-check-input" type="checkbox" name="marque_presence[{{$dt->user_id}}]" value=1 id="myCheckbox">
           
                                                                                      


                                                                                          <label class="form-check-label" for="flexCheckDefault">
                                                                                                {{ $dt->name}}  {{$dt->lastname}}
                                                                                        </label>
                                                                                </div>
                                                    
                                                    </td>
                                                    <td>
                                                    <i class="fas fa-eye openmodal" style="color:blue;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-user-id="{{$dt->user_id}}"></i> 
                                                    </td>
                                                    <td>
                                                        <div class="col-6 col-md-2"> {{ $dt->phone}} </div>
                                                    </td>
                                                    <td>
                                                    {{ $dt->birthdate}} 
                                                    </td>
                                                    <td><i class="fas fa-certificate"></i><i class="fas fa-graduation-cap"></i></td>
                                            


                                                  </tr>
                                                


                                                  @endif

                                      @endforeach
                                      </form>
                                      </tbody>
                                                    </table>

                                    
                                    </div>
                                
                              
  
            </div>

  @endforeach
  </div>

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="display_info_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informations</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="just_display">
        ...

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      
      </div>
    </div>
  </div>
</div>



      </div>
      
          @else
      
          
<div class="row">
          <div class="col-md-4 d-flex justify-content-end"><label> Saison </label></div>
          <div class="col-md-8">  
                
                
                <form class="row" action="{{ route('include-page') }}" method="POST" >
                    @csrf
                    <div class="col-6">
                  <select class="form-control" name="saison" id="saison">
                  
                            @foreach($saison_list as $data)

                                            <option value="{{$data->saison}}" {{ $data->saison == $saison_active ? 'selected' : '' }} >{{$data->saison}} - {{$data->saison + 1 }}</option>
                            
                            
                            @endforeach

                  </select> </div> <div class="col-6"> <button class=" mt-1 btn btn-sm btn-primary" type="submit" id="hide-row-btn" >Accéder</button></div>
                
                
                </form>
            </div>
          <hr>
   
  
   </div>

 
      
                      <div class="d-grid gap-2">
                  @foreach($shop_article_first as $data)

                                    
                            <input readonly  onclick="toggleElement('{{ $data->id_shop_article }}')"  class="btn btn-secondary"  value="{{$data->title}}   {{(int)$data->stock_ini - (int)$data->stock_actuel }}/ {{$data->stock_ini}}" >
                            <div id="my-element-{{ $data->id_shop_article }}" style="display: none;">
      
      
      
                                      <!-- Button trigger modal
                                     <a  id="load-content" href="{{route('form_appel',$data->id_shop_article)}}">Faire l'appel</a>
                                    
                                    -->
                          

                                                      <div id="content">


                                                          
            <div class="row">  
           
            <div class="col-4"> 
            <form action="{{route('enregistrer_appel',['id'=>$data->id_shop_article])}}" method="POST">
                                                      @csrf
              <button type="submit" class="btn btn-success">Valider l'appel</button>
            
            </div>
                    <div class="col-4"> 
                            
                                                          <input type="date" class="form-control m-0" name="date_appel" value="<?php echo date('Y-m-d'); ?>">
                                                          
                                              
                    </div>
                   
                    <div class="col-4 d-flex justify-content-center"><button type="button" class="btn btn-secondary">  <a  href="{{route('historique_appel',$data->id_shop_article)}}">Historique des appels</a></button></div>
                
            </div>
                <br>

                                                      <table class="table table-hover" style="background-color:green;"> 
                                                        <tbody>
                                                      @foreach($users_saison_active as $dt)

                                                                  @if($data->id_shop_article == $dt->id_shop_article)


                                                                 
                                                                        
                                                                <tr>
                                                                    
                                                                    <td>
                                                                                <div class="form-check">
                                                                                        <input name="user_id[]" value="{{$dt->user_id}}" hidden>
                                                                                         
                                                                                       
                                                                                         
                                                                                          <input class="form-check-input" type="checkbox" name="marque_presence[{{$dt->user_id}}]" value=1 id="myCheckbox">
           
                                                                                      


                                                                                          <label class="form-check-label" for="flexCheckDefault">
                                                                                                {{ $dt->name}}  {{$dt->lastname}}
                                                                                        </label>
                                                                                </div>
                                                                       
                                                                    
                                                                    </td>
                                                                    <td>
                                                                    <i class="fas fa-eye openmodal" style="color:blue;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-user-id="{{$dt->user_id}}"></i> 
                                                                    </td>
                                                                    <td>
                                                                        <div class="col-6 col-md-2"> {{ $dt->phone}} </div>
                                                                    </td>
                                                                    <td>
                                                                    {{ $dt->birthdate}} 
                                                                    </td>
                                                                    <td><i class="fas fa-certificate"></i><i class="fas fa-graduation-cap"></i></td>
                                                            


                                                                  </tr>
                                                                


                                                                  @endif

                                                      @endforeach

                                                      </form>

                                                      </tbody>
                                                                    </table>

                                                    
                                                    </div>
                                      
                            </div>
      
                  @endforeach
                  </div>
           
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="display_info_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informations</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="just_display">
        ...

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      
      </div>
    </div>
  </div>
</div>



      
          @endif
          

      </div>
      
      <script>
          function toggleElement(id) {
          var element = document.getElementById('my-element-' + id);
          if (element.style.display === 'none') {
              element.style.display = 'block';
          } else {
              element.style.display = 'none';
          }
      }
      
      </script>
      


      @endif


  

</main>

    


@endsection




















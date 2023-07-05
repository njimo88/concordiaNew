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


            <div class="row">
              <div class="col-md-4 d-flex justify-content-end mb-3">
                  <label> Saison </label>
              </div>
              <div class="col-md-8 mb-3">
                  <div class="col-6">
                      <form  id="seasonForm">
                          @csrf
                          <select class="form-control" name="saison" id="saison">
                              @foreach($saison_list as $data)
                                  <option value="{{$data->saison}}" {{ $data->saison == $saison_actu ? 'selected' : '' }}>
                                      {{$data->saison}} - {{$data->saison + 1 }}
                                  </option>
                              @endforeach
                          </select>
                      </form>
                  </div>
              </div>
              <hr>
          </div>
          

 
   <style>
    .input-container {
      display: inline-flex;
      align-items: center;
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      background-color: #e3e3e3;
      
    }
  
    .input-container img {
    }
  
    .input-container input {
      border: none;
      outline: none;
      padding: 0.375rem;
      background-color: #e3e3e3;
    }
  </style>
                      <div class="d-grid gap-4">
                  @foreach($shop_article_first as $data)
                                    
                  <div class="input-container">
                    <img class="px-2" style="height: 30px " src="{{ asset('assets/images/logo-admin-list.png') }}" alt="vvvvvvvvvvvvvvv">
                    <input readonly onclick="toggleElement('{{ $data->id_shop_article }}')" class="btn m-0" style="font-weight: bold; text-align: left;" value="{{$data->title}}   ({{ $data->usersActiveCount() }} / {{$data->stock_ini}})">
                  </div>
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

                                                      <table class="table table-hover" style="background-color:lime; color:black"> 
                                                        <tbody>
                                                      @foreach($users_saison_active as $dt)

                                                                  @if($data->id_shop_article == $dt->id_shop_article)

                                                                
                                                      
                                                       
                                               <tr>
                                                                        
                                                             
                                                                    
                                                                    <td>
                                                                                <div class="form-check">
                                                                                        <input name="user_id[]" value="{{$dt->user_id}}" hidden>
                                                                                         
                                                                                       
                                                                                         
                                                                                          <input class="form-check-input" type="checkbox" name="marque_presence[{{$dt->user_id}}]" value=1 id="myCheckbox">
           
                                                                                      


                                                                                          <label class="form-check-label" for="flexCheckDefault" style="color:black">
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
      
      <script>
          function toggleElement(id) {
          var element = document.getElementById('my-element-' + id);
          if (element.style.display === 'none') {
              element.style.display = 'block';
          } else {
              element.style.display = 'none';
          }
      }

      document.getElementById('saison').addEventListener('change', function() {
          document.getElementById('seasonForm').submit();
      });

      
      </script>
</main>

    


@endsection




















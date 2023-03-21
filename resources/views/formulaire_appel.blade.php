
@extends('layouts.template')

@section('content')

@php

require_once('../app/fonction.php');
$saison_active = saison_active() ;

@endphp
<main id="main" class="main">


<div class="container">

  

          

<div class="row">  

    <div class="col-6 col-md-4"> 
        <form action="{{route('enregistrer_appel',['id'=>$the_id])}}" method="POST">
            @csrf
                 <button type="submit" class="btn btn-success">Valider l'appel</button> <input type="date" class="form-control" name="date_appel" value="<?php echo date('Y-m-d'); ?>">
       </form>
    </div>
    <div class="col-6 col-md-4"> </div>
    <div class="col-6 col-md-4"><button type="button" class="btn btn-secondary">Historique des appels</button></div>
  
</div>
<br>
   <table class="table table-hover" style="background-color:green;">
 
  <tbody>
  @foreach($users as $data)
    <tr>
     
      <td>
                <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                        {{ $data->name}}  {{$data->lastname}}
                        </label>
             </div>
     
     </td>
     <td>
     <i class="fas fa-eye" style="color:blue;" data-bs-toggle="modal" data-bs-target="#exampleModal" ></i> 
     </td>
      <td>
        <div class="col-6 col-md-2"> {{ $data->phone}} </div>
     </td>
     <td>
      {{ $data->birthdate}} 
     </td>
      <td><i class="fas fa-certificate"></i><i class="fas fa-graduation-cap"></i></td>
    </tr>
  @endforeach
    
  </tbody>
</table>

</div>




<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>













</main>
    


@endsection



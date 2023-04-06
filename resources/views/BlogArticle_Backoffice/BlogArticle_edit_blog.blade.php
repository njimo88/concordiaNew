@extends('layouts.template')

@section('content')






<main id="main" class="main">

@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif



            <?php

use Carbon\Carbon;

$date = Carbon::now();

//echo date("d/m/Y", strtotime($date));
 
 ?>
              <div class="row d-flex justify-content-between pt-5">
                <div class="col-md-6">
                  <h1>Modification d'un billet de blog</h1>
                </div>
                <div class="col-6 d-flex justify-content-end px-4">
                        <a href="{{route('index')}}"><button class="btn btn-warning">Retour</button></a>
                </div>
              </div>

@foreach($blog as $data1)



            

                  
            <div class="row">
                <div class="row">
            <div data-alert class="alert alert-info">{{$data1->status}}</div>
                 <div class="row">

            <div>

<div class="container mt-5">

<form  method="POST" action="{{route('edit_blog',$Id)}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
        @csrf
<div class="row">
    
    <div class="col-sm-12">
     

    <div class="row" style="background-color:pink; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

          
<div class="row pb-3">

    <div>
              <br>
      
                  
      
                    <label>Titre </label>
                      <textarea type="text" name="titre" class="form-control" required> {{$data1->titre}}</textarea>
                      <br>
                    <label></label>
                      <textarea name="editor1"  id="ckeditor" class="form-control" required>{{$data1->contenu}}</textarea>
                      
                   
      

          
    </div>



</div>


</div>


</div>
 
</div>


    </div>
   
  </div>

<br>

  <div class="row pt-5" >
    <div class="col-sm-4" style="background-color:LightGreen; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;">

      <div class="col-md-12">
    <div style="height: 250px;  overflow: scroll; ">
<table class="table">
  <thead>
    <tr>
      
      <th scope="col">Choix des catégories 1</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>



 

  @php
                                $json_cate = json_decode($data1->categorie1) ;  
                                
                            @endphp

                            
                                
                                @foreach($Categorie1 as $data)
                                
                                <tr>
                            
                                    <td>{{$data->nom_categorie}}</td>
                                    
                                    <td><input style="vertical-align:center;" for="catenvoi" type="checkbox" name="category1[]"  value="{{$data->Id_categorie1 }}" {{ in_array($data->Id_categorie1 ,$json_cate) ? 'checked ': " "}}></td>
                                    
                                

                                </tr>

                            @endforeach


  </tbody>
</table>
</div>
    </div>

    </div>
    <div class="col-sm-4" style="background-color:MediumSeaGreen; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;">
      
    <div class="col-md-12">
    <div style="height: 250px;  overflow: scroll; ">
<table class="table">
  <thead>
    <tr>
      
      <th scope="col">Choix des catégories 2</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  
 

  @php
                                $json_cate = json_decode($data1->categorie2) ;  
                                
                            @endphp

                            
                                
                                @foreach($Categorie2 as $data)
                                
                                <tr>
                            
                                    <td>{{$data->nom_categorie}}</td>
                                    
                                    <td><input style="vertical-align:center;" for="catenvoi" type="checkbox" name="category2[]"  value="{{$data->Id_categorie2 }}" {{ in_array($data->Id_categorie2 ,$json_cate) ? 'checked ': " "}}></td>
                                    
                                

                                </tr>

                            @endforeach
   
  </tbody>
</table>
</div>
    </div>



    </div>
    <div class="col-sm-4" style="background-color:Cyan; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;">
    <div class="col-md-12">
    <div class="col">
        <label class="text-dark p-2">Publier en différé (si nul, publication immédiate)</label>
      <input type="datetime-local" class="form-control" placeholder="" name="date_post" value="<?php echo date('Y-m-d\TH:i'); ?>">

    </div>
    <div class="row">
            <div class="col-md"> <input class="btn btn-secondary" type="submit" name="valider" value="Brouillon"> </div>
            <div class="col-md"> </div>
            <div class="col-md">  <input class="btn btn-success" type="submit" name="valider" value="publier"></div>
            
    </div>
    <div class="row">
          <p style="text-align: center;">  publication : <?php echo date("d/m/Y", strtotime($data1->date_post)) ; ?> </p>
    </div>


    </div>
  </div>
</div>





</form>

</main>

<script src="//cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script>


@endforeach









@endsection
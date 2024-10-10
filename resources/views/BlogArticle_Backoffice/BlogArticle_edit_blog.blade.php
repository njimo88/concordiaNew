@extends('layouts.template')

@section('content')
<style>
  .form-control {
    max-height: 20px;
  }

.input {
  margin:  0 !important;
}
</style>





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
              <div class="row d-flex justify-content-between p-2">
                <div class="col-md-6">
                  <h1>Modification d'un billet de blog</h1>
                </div>
                
              </div>

@foreach($blog as $data1)



            

<div class="row d-flex justify-content-between">
  <div data-alert class="alert alert-info mb-3 col-6">{{$data1->status}}</div>
  <div class="text-end col-6">
    <a target="_blank" href="{{ route('Simple_Post', ['id' => $data1->id_blog_post_primaire]) }}" class="btn btn-danger rounded-pill px-4 py-2">
      Voir Article
    </a>
  </div>
</div>


                 <div class="row">



<form  method="POST" action="{{route('edit_blog',$Id)}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
        @csrf
<div class="row">
    
    <div class="col-sm-12">
     

    <div class="row" style="background-color:pink; border: 2px solid grey;justify-content: center">

          
<div class="row pb-3">

    <div>
              <br>
      
                  
      
                      <textarea type="text" name="titre" class="form-control" required> {{$data1->titre}}</textarea>
                      <br>
                    <label></label>
                      <textarea name="editor1" class="form-control" required>{{$data1->contenu}}</textarea>
                      
                   
      

          
    </div>



</div>


</div>


</div>
 
</div>


    </div>
   
  </div>

<br>

  <div class="row pt-3" >
    <div class="col-sm-4" style="background-color:LightGreen; border: 2px solid grey;">

      <div class="col-md-12">
    <div style="height: 334px;  overflow: scroll; ">
<table class="table">
  <thead>
    <tr>
      
      <th scope="col">Choix des catégories 1</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>



 

  @php
                                $json_cate = json_decode($data1->categorie) ;  
                                
                            @endphp

                            
                                
                                @foreach($Categorie1 as $data)
                                
                                <tr>
                            
                                    <td>{{$data->nom_categorie}}</td>
                                    
                                    <td><input style="vertical-align:center;" class="input" for="catenvoi" type="checkbox" name="category1[]"  value="{{$data->Id_categorie }}" {{ in_array($data->Id_categorie ,$json_cate) ? 'checked ': " "}}></td>
                                    
                                

                                </tr>

                            @endforeach


  </tbody>
</table>
</div>
    </div>

    </div>
    <div class="col-sm-4" style="background-color:MediumSeaGreen; border: 2px solid grey;">
      
    <div class="col-md-12">
    <div style="height: 334px;  overflow: scroll; ">
<table class="table">
  <thead>
    <tr>
      
      <th scope="col">Choix des catégories 2</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  
 

  @php
                                $json_cate = json_decode($data1->categorie) ;  
                                
                            @endphp

                            
                                
                                @foreach($Categorie2 as $data)
                                
                                <tr>
                            
                                    <td>{{$data->nom_categorie}}</td>
                                    
                                    <td><input class="input" style="vertical-align:center;" for="catenvoi" type="checkbox" name="category2[]"  value="{{$data->Id_categorie }}" {{ in_array($data->Id_categorie ,$json_cate) ? 'checked ': " "}}></td>
                                    
                                

                                </tr>

                            @endforeach
   
  </tbody>
</table>
</div>
    </div>



    </div>
    <div class="col-sm-4" style="background-color:Cyan; border: 2px solid grey;">
      <div style="background: #00f9f9;" class="card border-0">
        <div class="card-body">
          <h4 class="card-title text-center mb-2">Publication</h4>
          <form>
            <div class="mb-3">
              <label for="date_post" class="form-label">Date de publication</label>
              <input type="datetime-local" style="max-height: 100px !important;" class="form-control" id="date_post" name="date_post" value="<?php echo date("Y-m-d\TH:i", strtotime($data1->date_post)) ; ?>">


            </div>
            <div class="d-flex justify-content-between align-items-center">
              <button class="btn btn-secondary" type="submit" name="valider" value="Brouillon">Brouillon</button>
              <button class="btn btn-success" type="submit" name="valider" value="publier">Publier</button>
            </div>
          </form>
          <hr class="mt-4 mb-3">
          <div class="row">
            <div class="col text-center">
              <p>Publication : <?php echo date("d/m/Y", strtotime($data1->date_post)) ; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>





</form>

</main>

<script src="https://cdn.ckeditor.com/4.25.0-lts/standard/ckeditor.js"></script>






@endforeach









@endsection
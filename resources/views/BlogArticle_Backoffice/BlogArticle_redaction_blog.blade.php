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


  

<form  method="POST" action="{{route('creation_article_blog')}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
        @csrf
  <div class="d-flex justify-content-between p-2">
    <div class="col-md-6">
      <h1>Rédaction d'un billet de blog</h1>
    </div>
  </div> 
    <div class="col-sm-12">
     

    <div class="row" style="background-color:pink; border: 2px solid grey;justify-content: center">

      
<div class="row">
  
    <div>
              <br>
      
                  
      
                    <label class="text-dark">Titre </label>
                      <textarea type="text" name="titre" class="form-control" required></textarea>
                      <br>
                    <label></label>
                      <textarea name="editor1"  id="ckeditor" class="form-control" required></textarea>
                      
                   
      

          
    </div>



</div>


</div>


</div>
 


   
  </div>

<br>

  <div class="row pt-3" >
    <div class="col-sm-4" style="background-color:LightGreen; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;">

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
  
    @foreach($Categorie1 as $data)

    <tr>
    
      <td>{{$data->nom_categorie}}</td>
     
      <td><input style="vertical-align:center;" class="input" for="catenvoi" type="checkbox" name="category1[]" value="{{$data->Id_categorie1}}"></td>
     
    </tr>

    @endforeach
   
  </tbody>
</table>
</div>
    </div>

    </div>
    <div class="col-sm-4" style="background-color:MediumSeaGreen; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;">
      
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
  
    @foreach($Categorie2 as $data)

    <tr>
    
      <td>{{$data->nom_categorie}}</td>
     
      <td><input style="vertical-align:center;" class="input" for="catenvoi" type="checkbox" name="category2[]" value="{{$data->Id_categorie2}}" ></td>
     

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
              <input type="datetime-local" style="max-height: 100px !important;" class="form-control" id="date_post" name="date_post" value="<?php echo date('Y-m-d\TH:i'); ?>">
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <button class="btn btn-secondary" type="submit" name="valider" value="Brouillon">Brouillon</button>
              <button class="btn btn-success" type="submit" name="valider" value="publier">Publier</button>
            </div>
          </form>
          <hr class="mt-4 mb-3">
          <div class="row">
            <div class="col text-center">
              <p>Publication : <?php echo date('Y-m-d\TH:i'); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>





</form>
<script src="//cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script>

</main>













@endsection
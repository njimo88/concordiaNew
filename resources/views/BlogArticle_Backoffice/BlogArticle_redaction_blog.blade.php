@extends('layouts.template')

@section('content')


<?php
          setlocale(LC_ALL, 'fr_FR.UTF-8');
          setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');
          $date = strftime("%d %B %Y");
          
          // Define an array of English to French translations for month and day names
          $englishToFrench = [
              'January' => 'janvier',
              'February' => 'février',
              'March' => 'mars',
              'April' => 'avril',
              'May' => 'mai',
              'June' => 'juin',
              'July' => 'juillet',
              'August' => 'août',
              'September' => 'septembre',
              'October' => 'octobre',
              'November' => 'novembre',
              'December' => 'décembre',
              'Monday' => 'Lundi',
              'Tuesday' => 'Mardi',
              'Wednesday' => 'Mercredi',
              'Thursday' => 'Jeudi',
              'Friday' => 'Vendredi',
              'Saturday' => 'Samedi',
              'Sunday' => 'Dimanche',
          ];
          
          // Use the strtr function to replace the English month and day names with their French equivalents
          $formattedDate = strtr(\Carbon\Carbon::parse()->isoFormat('dddd D MMMM YYYY à HH:mm:ss'), $englishToFrench);
          
          // Output the formatted date
          echo '<span style="font-weight:bold">Date</span> '.$formattedDate;
          ?>






















<main id="main" class="main">

@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif


  
<div class="container mt-5">

<form  method="POST" action="{{route('creation_article_blog')}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
        @csrf
<div class="row">
    
    <div class="col-sm-12">
     

    <div class="row" style="background-color:pink; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

          
<div class="row">

    <div>
              <br>
      
                  
      
                    <label>Titre </label>
                      <textarea type="text" name="titre" class="form-control" required></textarea>
                      <br>
                    <label></label>
                      <textarea name="editor1"  id="ckeditor" class="form-control" required></textarea>
                      
                   
      

          
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
  
    @foreach($Categorie1 as $data)

    <tr>
    
      <td>{{$data->nom_categorie}}</td>
     
      <td><input style="vertical-align:center;" for="catenvoi" type="checkbox" name="category1[]" value="{{$data->Id_categorie1}}"></td>
     
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
  
    @foreach($Categorie2 as $data)

    <tr>
    
      <td>{{$data->nom_categorie}}</td>
     
      <td><input style="vertical-align:center;" for="catenvoi" type="checkbox" name="category2[]" value="{{$data->Id_categorie2}}" ></td>
     

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
        <label>Publier en différé (si nul, publication immédiate)</label>
      <input type="datetime-local" class="form-control" placeholder="" name="date_post" value="<?php echo date('Y-m-d\TH:i'); ?>">

    </div>
    <div class="row">
            <div class="col-md"> <input class="btn btn-secondary" type="submit" name="valider" value="Brouillon"> </div>
            <div class="col-md"> </div>
            <div class="col-md">  <input class="btn btn-success" type="submit" name="valider" value="publier"></div>
            
    </div>

    </div>
  </div>
</div>





</form>

</main>

<script src="//cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script>












@endsection
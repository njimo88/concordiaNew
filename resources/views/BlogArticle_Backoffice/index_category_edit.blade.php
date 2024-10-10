@extends('layouts.template')

@section('content')

@foreach($cate1 as $data)
<main id="main" class="main">

@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <div class="row pt-5">
                    <div class="col-md-2">
                        
                    </div>
                    <div class="col-md-12">
                           <a href="{{route('index_article_category')}}"><button class="btn btn-warning"> retour</button></a>
                    </div>
</div>


      
<div class="container border border-dark">
  
        <h1 style="text-align: center;">Ajouter une catégorie </h1>

    <div class="row">

    <div class="col-md-2"></div>

    <div class="col-md-8">
    
    <form action="{{route('edit_cate',['id' => $data->Id_categorie])}}" method="POST">

   
    
        @csrf
  <div class="mb-3 ">
    <label  class="form-label">Titre</label>
    <input type="text" class="form-control"  placeholder="Titre..." name="nom_categorie" value="{{$data->nom_categorie}}" required>
  </div>
  <div class="mb-3 ">
    <label  class="form-label">Logo</label>
    <input type="text" class="form-control"  placeholder="Logo..." name="image" value="{{$data->image}}" required>
  </div>
 
  <div class="mb-3 ">
  <label  class="form-label">Visibilité</label>
                        <select id="" name="visibilite"  class="form-control" require>
                        
                         <option value="Visible" {{ $data->visibilite == "Visible" ? 'selected' : '' }}>Visible</option>
                         <option value="Non visible"  {{ $data->visibilite == "Non visible" ? 'selected' : '' }}>Non Visible</option>
                    

                        </select>
  </div>

  <div class="mb-3 ">
  <label for="lname">Description</label>
        <textarea class="form-control"type="text" id="lname" name="description" placeholder="la description.." require> {{$data->description}}</textarea>
                        </div>
 
  <button type="submit" class="btn btn-primary">Valider</button>
</form>
<br>









    </div>
    <div class="col-md-2"></div>
   
</div>


</div>

@endforeach
</main>










@foreach($cate2 as $data)
<main id="main" class="main">

@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <div class="row pt-5">
                    <div class="col-md-2">
                        
                    </div>
                    <div class="col-md-12">
                           <a href="{{route('index_article_category')}}"><button class="btn btn-warning"> retour</button></a>
                    </div>
</div>


      
<div class="container border border-dark">
  
        <h1 style="text-align: center;">Ajouter une catégorie </h1>

    <div class="row">

    <div class="col-md-2"></div>

    <div class="col-md-8">
 
    
    <form action="{{route('edit_cate',['id' => $data->Id_categorie])}}" method="POST">
 
        @csrf
  <div class="mb-3 ">
    <label  class="form-label">Titre</label>
    <input type="text" class="form-control"  placeholder="Titre..." name="nom_categorie" value="{{$data->nom_categorie}}" require>
  </div>
  <div class="mb-3 ">
    <label  class="form-label">Logo</label>
    <input type="text" class="form-control"  placeholder="Logo..." name="image" value="{{$data->image}}" require>
  </div>
  


  <div class="mb-3 ">
  <label for="lname">Description</label>
        <textarea class="form-control"type="text" id="lname" name="description" placeholder="la description.." require> {{$data->description}}</textarea>
                        </div>
 
  <button type="submit" class="btn btn-primary">Valider</button>
</form>
<br>









    </div>
    <div class="col-md-2"></div>
   
</div>


</div>

@endforeach
</main>









@endsection
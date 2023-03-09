@extends('layouts.template')

@section('content')

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

    <form action="{{route('create_cate',$cate)}}" method="POST">
        @csrf
  <div class="mb-3 ">
    <label  class="form-label">Titre</label>
    <input type="text" class="form-control"  placeholder="Titre..." name="titre" require>
  </div>
  <div class="mb-3 ">
    <label  class="form-label">Logo</label>
    <input type="text" class="form-control"  placeholder="Logo..." name="image" require>
  </div>
  
  @if ($cate == 1)
  <div class="mb-3 ">
  <label  class="form-label">Visibilité</label>
                        <select id="" name="visibilite"  class="form-control" require>
                        
                         <option value="Visible">Visible</option>
                         <option value="Non visible">Non Visible</option>
                    

                        </select>
  </div>
  @endif
  <div class="mb-3 ">
  <label for="lname">Description</label>
        <textarea class="form-control"type="text" id="lname" name="description" placeholder="la description.." require></textarea>
                        </div>
 
  <button type="submit" class="btn btn-primary">Valider</button>
</form>
<br>









    </div>
    <div class="col-md-2"></div>
   
</div>


</div>


</main>











@endsection
@extends('layouts.template')

@section('content')

<main id="main" class="main">

@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
        

<br>
@if (auth()->user()->roles->supprimer_edit_ajout_categorie)
<!-- Button trigger modal pour choisir le type d'article-->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
Créer une catégorie
</button>
@endif
<!-- Modal -->
<div  class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div style="min-width: 27vw !important;" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Créer une catégorie </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <div class="row p-2 d-flex justify-content-between">
      <div class="col-md-4">
        
      <a href="{{route('index_creation_cate',1)}}"><button type="button" class="btn btn-primary">Catégorie 1</button></a>
      
      </div>
      <div class="col-md-4 d-flex justify-content-end">
        
      <a href="{{route('index_creation_cate',2)}}"><button type="button" class="btn btn-primary">Catégorie 2 </button></a>
      
      </div>


      </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>










    
<div class="container">
    <div class="row">
       
            
        <div class="table-responsive">

      
                
<table id="myTable" class="table table-bordred table-striped">
     
     <thead>
     
     
     <th>Image</th>
      <th>Nom</th>
       <th>Description</th>
       @if (auth()->user()->roles->supprimer_edit_ajout_categorie)
       <th>Modifier</th>
      <th>Supprimer</th>
       @endif
       
         
     </thead>
<tbody>




@foreach($requete_categorie1 as $data)
<tr>

<td><img src="{{$data->image}}" style="width: 3em"></td>

<td>{{$data->nom_categorie}}</td>

<td>{{$data->description}}</td>

@if (auth()->user()->roles->supprimer_edit_ajout_categorie)
<td><p data-placement="top" data-toggle="tooltip" title="Editer"><a href="{{ route('edit_index',['id' => $data->Id_categorie]) }}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" onclick="return confirm('êtes-vous sûr de vouloir modifier cette catégorie ?');"><i class="bi bi-pencil-fill"></i></button></a></p></td>
<td><p data-placement="top" data-toggle="tooltip" title="Effacer"><a href="{{route('delete_cate',['id' => $data->Id_categorie])}}"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer cette catégorie ?');" ><i class="bi bi-trash"></i></button></a></p></td>
@endif
@endforeach

</tbody>

</table>

<div class="clearfix"></div>
<div class="d-flex justify-content-center">
{!! $requete_categorie1->links() !!}
</div>          



 </div>

    <br>
    <br>
    </div>
            <div class="row pt-5">

            <div class="table-responsive">

      
                
<table id="myTableabb" class="table table-bordred table-striped">





     
     <thead>
     
     
     <th>Image</th>
      <th>Nom</th>
       <th>Description</th>
       @if (auth()->user()->roles->supprimer_edit_ajout_categorie)
       <th>Modifier</th>
      <th>Supprimer</th>
       @endif
       
         
     </thead>
<tbody>




@foreach($requete_categorie2 as $data)
<tr>

<td><img src="{{$data->image}}" style="width: 3em"></td>

<td>{{$data->nom_categorie}}</td>

<td>{{$data->description}}</td>

@if (auth()->user()->roles->supprimer_edit_ajout_categorie)
<td><p data-placement="top" data-toggle="tooltip" title="Edit"><a href="{{ route('edit_index',['id' => $data->Id_categorie]) }}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" onclick="return confirm('êtes-vous sûr de vouloir modifier cette catégorie ?');"><i class="bi bi-pencil-fill"></i></button></a></p></td>
<td><p data-placement="top" data-toggle="tooltip" title="Delete"><a href="{{route('delete_cate',['id' => $data->Id_categorie])}}"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer cette catégorie ?');" ><i class="bi bi-trash"></i></button></a></p></td>
@endif
</tr>
@endforeach

</tbody>

</table>

<div class="clearfix"></div>
<div class="d-flex justify-content-center">
{!! $requete_categorie2->links() !!}
</div>          
</div>


            </div>
    </div>
   
</div>


</main>











@endsection
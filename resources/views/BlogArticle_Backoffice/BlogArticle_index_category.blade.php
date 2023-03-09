@extends('layouts.template')

@section('content')

<main id="main" class="main">

@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
        

<br>
<!-- Button trigger modal pour choisir le type d'article-->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
Creer une catégorie
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Creer une catégorie </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <div class="row">
      <div class="col-md-6">
        
      <a href="{{route('index_creation_cate',1)}}"><button type="button" class="btn btn-primary">catégorie 1</button></a>
      
      </div>
      <div class="col-md-6">
        
      <a href="{{route('index_creation_cate',2)}}"><button type="button" class="btn btn-primary">catégorie 2 </button></a>
      
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
      <th>Name</th>
       <th>Description</th>
       
       
         
     </thead>
<tbody>




@foreach($requete_categorie1 as $data)
<tr>

<td><img src="{{$data->image}}" style="width: 3em"></td>

<td>{{$data->nom_categorie}}</td>

<td>{{$data->description}}</td>

<td><p data-placement="top" data-toggle="tooltip" title="Edit"><a href="{{ route('edit_index',['id' => $data->Id_categorie1]) }}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" onclick="return confirm('êtes-vous sûr de vouloir modifier?');"><i class="bi bi-pencil-fill"></i></button></a></p></td>
<td><p data-placement="top" data-toggle="tooltip" title="Delete"><a href="{{route('delete_cate',['id' => $data->Id_categorie1])}}"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer?');" ><i class="bi bi-trash"></i></button></a></p></td>

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

      
                
<table id="myTable" class="table table-bordred table-striped">
     
     <thead>
     
     
     <th>Image</th>
      <th>Name</th>
       <th>Description</th>
       
       
         
     </thead>
<tbody>




@foreach($requete_categorie2 as $data)
<tr>

<td><img src="{{$data->image}}" style="width: 3em"></td>

<td>{{$data->nom_categorie}}</td>

<td>{{$data->description}}</td>

<td><p data-placement="top" data-toggle="tooltip" title="Edit"><a href="{{ route('edit_index',['id' => $data->Id_categorie2]) }}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" onclick="return confirm('êtes-vous sûr de vouloir modifier?');"><i class="bi bi-pencil-fill"></i></button></a></p></td>
<td><p data-placement="top" data-toggle="tooltip" title="Delete"><a href="{{route('delete_cate',['id' => $data->Id_categorie2])}}"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer?');" ><i class="bi bi-trash"></i></button></a></p></td>

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
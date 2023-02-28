@extends('layouts.template')

@section('content')

<main id="main" class="main">

@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
        

<div class="container">
	<div class="row">
		
        
        <div class="col-md-12">
        <h4>Mes articles</h4>
        <button type="button" class="btn btn-primary">Article</button>
        <button type="button" class="btn btn-primary">Anciens articles</button>
      


<!-- Button trigger modal pour choisir le type d'article-->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
Créer un article
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Choisir le type d'article à créer </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <div class="row">
        
      <div class="col-md-4">
        
      <a href="{{ route('index_create_article_member') }}"><button type="button" class="btn btn-primary">Un article de type membre</button></a>
      
      </div>
      <div class="col-md-4">
        
      <a href="{{ route('index_create_article_produit') }}"><button type="button" class="btn btn-primary">Un article de type produit</button></a>
      
      </div>
      <div class="col-md-4">
        
      <a href="{{ route('index_create_article_lesson') }}"><button type="button" class="btn btn-primary">Un article de type cours  </button></a>
      
      </div>


      </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

        
    

        <div class="table-responsive">

      
                
              <table id="mytable" class="table table-bordred table-striped">
                   
                   <thead>
                   
                   
                   <th>Image</th>
                    <th>Reference</th>
                     <th>Titre</th>
                     <th>Prix TTC</th>
                     <th>Prix Cummulé</th>
                      <th>Stock</th>
                      <th>Modifié</th>
                      <th>supprimé</th>
                      <th>Dupliqué</th>
                      
                       
                   </thead>
    <tbody>
    @foreach($requete_article as $data)
    <tr>
      
      
        <td>{{$data->image}}</td>
        <td>{{$data->ref}}</td>
        <td>{{$data->title}}</td>
        <td>{{$data->price}}</td>
        <td>{{$data->totalprice}}</td>
        <td>{{$data->stock_actuel}}</td>
        <td><p data-placement="top" data-toggle="tooltip" title="Edit"><a href="{{route('edit_article', [ 'id' => $data->id_shop_article])}}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" onclick="return confirm('êtes-vous sûr de vouloir modifier?');"><i class="bi bi-pencil-fill"></i></button></a></p></td>
        <td><p data-placement="top" data-toggle="tooltip" title="Delete"><a href="{{route('delete_article',[ 'id' => $data->id_shop_article])}}"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer?');" ><i class="bi bi-trash"></i></button></a></p></td>
        <td><p data-placement="top" data-toggle="tooltip" title="Dupliquer"><a href="{{route('duplicate_article_index', [ 'id' => $data->id_shop_article])}}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal">Dupliquer  </button></a></p></td>
       
    </tr>
    @endforeach
        
    </tbody>
        
</table>

<div class="clearfix"></div>
<div class="d-flex justify-content-center">
    {!! $requete_article->links() !!}
</div>          
            </div>
            
        </div>
	</div>
</div>

</main>
    
@endsection
@extends('layouts.template')

@section('content')

@php

require_once(app_path().'/fonction.php');
$saison_active = saison_active() ;

@endphp
<main id="main" class="main">


<section>
	<div class="row d-flex justify-content-between">
		
        
        <div class="col-md-4">
        <h4>Mes articles</h4>
        <button type="button" class="btn btn-primary">Article</button>
        <button type="button" class="btn btn-primary">Anciens articles</button>

        @if (auth()->user()->roles->supprimer_edit_dupliquer_ajout_article)
        <!-- Button trigger modal pour choisir le type d'article-->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
Créer un article
</button>
<br>
       @endif
      
        </div>
      
        <div class="col-md-4">  
       
       <label> Saison </label>
       <form action="{{ route('include-tab_articles') }}" method="POST">
           @csrf
         <select class="form-control" name="saison" id="saison">
                  
                  @foreach($saison_list as $data)

                                  <option value="{{$data->saison}}" {{ $data->saison == $saison_active ? 'selected' : '' }} >{{$data->saison}} - {{$data->saison + 1 }}</option>
                  
                  
                                  @endforeach

         </select>
         <button type="submit" id="hide-row-btn" >Valider</button>
       
       </form>
   </div>
      
      

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
      
@if(session('submitted'))

<h5 style="text-align: center;">{{$saison}}</h5>
<div class="table-responsive" id="maTable">

      
                
<table id="myTableArticle" class="table table-bordred table-striped">
     
     <thead>
     
     
     <th>Image</th>
      <th>Référence</th>
       <th>Titre</th>
       <th>Prix TTC</th>
       <th>Prix Cumulé</th>
        <th>Stock</th>
        @if (auth()->user()->roles->supprimer_edit_dupliquer_ajout_article)
        <th>Modifier</th>
        <th>Supprimer</th>
        <th>Dupliquer</th>
        @endif
         
     </thead>
<tbody>
@foreach($requete_article_pick as $data)
<tr>


<td><img src="{{$data->image}}" style="height: 60px; width:60px"></td>
<td>{{$data->ref}}</td>
<td>{{$data->title}}</td>
<td>{{$data->price}}</td>
<td>{{$data->totalprice}}</td>
<td>{{$data->stock_actuel}}</td>
@if (auth()->user()->roles->supprimer_edit_dupliquer_ajout_article)
<td><p data-placement="top" data-toggle="tooltip" title="Editer"><a target="_blank" href="{{route('edit_article', [ 'id' => $data->id_shop_article])}}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><i class="bi bi-pencil-fill"></i></button></a></p></td>
<td><p data-placement="top" data-toggle="tooltip" title="Effacer"><a href="{{route('delete_article',[ 'id' => $data->id_shop_article])}}"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer?');" ><i class="bi bi-trash"></i></button></a></p></td>
<td><p data-placement="top" data-toggle="tooltip" title="Dupliquer"><a href="{{route('duplicate_article_index', [ 'id' => $data->id_shop_article])}}"><button class="btn btn-success btn-xs" data-title="Edit" data-toggle="modal"><i class="fa fa-clone " ></i> </button></a></p></td>
@endif
</tr>
@endforeach

</tbody>

</table>



@else
<div class="table-responsive" id="maTable">

      
                
<table id="myTable" class="table table-bordred table-striped">
     
     <thead>
     
     
     <th>Image</th>
      <th>Référence</th>
       <th>Titre</th>
       <th>Prix TTC</th>
       <th>Prix Cumulé</th>
        <th>Stock</th>
        @if (auth()->user()->roles->supprimer_edit_dupliquer_ajout_article)
        <th>Modifier</th>
        <th>Supprimer</th>
        <th>Dupliquer</th>
        @endif
         
     </thead>
<tbody>
@foreach($requete_article as $data)
<tr style="background-color: <?php echo ($data->stock_actuel <= 0) ? '#FB335B' : (($data->stock_actuel < $data->alert_stock) ? 'orange' : ''); ?>">
<td><img src="{{$data->image}}" style="height: 60px; width:60px"></td>
<td>{{$data->ref}}</td>
<td>{{$data->title}}</td>
<td>{{ number_format($data->price, 2, ',', ' ') }} <i class="fa-solid fa-euro-sign"></i></td>
<td>{{ number_format($data->totalprice, 2, ',', ' ') }} <i class="fa-solid fa-euro-sign"></i></td>
<td>{{$data->stock_actuel}}/{{ $data->stock_ini }}</td>
@if (auth()->user()->roles->supprimer_edit_dupliquer_ajout_article)
<td><p data-placement="top" data-toggle="tooltip" title="Editer"><a target="_blank" href="{{route('edit_article', [ 'id' => $data->id_shop_article])}}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><i class="bi bi-pencil-fill"></i></button></a></p></td>
<td><p data-placement="top" data-toggle="tooltip" title="Effacer"><a href="{{route('delete_article',[ 'id' => $data->id_shop_article])}}"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer?');" ><i class="bi bi-trash"></i></button></a></p></td>
<td><p data-placement="top" data-toggle="tooltip" title="Dupliquer"><a href="{{route('duplicate_article_index', [ 'id' => $data->id_shop_article])}}"><button class="btn btn-success btn-xs" data-title="Edit" data-toggle="modal"><i class="fa fa-clone " ></i> </button></a></p></td>
@endif
</tr>
@endforeach

</tbody>

</table>

@endif      
    

  
<div class="clearfix"></div>
<div class="d-flex justify-content-center">
    {!! $requete_article->links() !!}
</div> 
            
   




</section>

</main>
    



@endsection
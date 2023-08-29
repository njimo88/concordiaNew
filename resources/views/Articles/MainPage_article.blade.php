@extends('layouts.template')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
 
 .btn {
   font-family: 'Poppins', sans-serif;
   font-weight: 500;
   font-size: 1rem;
   letter-spacing: 1px;
   display: inline-block;
   padding: 10px 30px;
   color: #fff;
   
   border: none;
   position: relative;
   cursor: pointer;
   overflow: hidden;
   transition: .3s ease background-color;
 }
 
 .btn:hover {
   background-color: #ba0712;
   color: white;
 }
 
 .btn i {
   transition: .3s ease all;
 }
 
 .btn:hover i {
   transform: rotate(360deg);
 }
 
 </style>
@php

require_once(app_path().'/fonction.php');
$saison_active = saison_active() ;

@endphp
<main id="main" class="main">


<section>
	<div class="row d-flex justify-content-between">
		
        
        <div class="col-md-8">
        <h4>Mes articles</h4>
        <button style="background-color: #182983 !important;" type="button" class="btn btn-primary">Article</button>
        <button style="background-color: #182983 !important;" type="button" class="btn btn-primary">Anciens articles</button>

        @if (auth()->user()->roles->supprimer_edit_dupliquer_ajout_article)
        <!-- Button trigger modal pour choisir le type d'article-->
<button style="background-color: #182983 !important;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
Créer un article
</button>
  <button style="background-color: #182983 !important;" id="updateStock"  class="btn">
      <i class="fas fa-sync"></i> Mettre à jour le stock
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
       <th>Statut</th>
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
<td>
  {{$data->title}}
  @if($data->sex_limite === null)
      <!-- Mettez ici l'icône pour null -->
      {{ $data->sex_limite }}
      <img src="{{ asset('assets/images/genders.png') }}" alt="icône null" />
  @elseif($data->sex_limite === 'female')
      <!-- Mettez ici l'icône pour female -->
      {{ $data->sex_limite }}

      <img src="{{ asset('assets/images/femenine.png') }}" alt="icône female" />
  @elseif($data->sex_limite === 'male')
      <!-- Mettez ici l'icône pour male -->
      {{ $data->sex_limite }}

      <img src="{{ asset('assets/images/male.png') }}" alt="icône male" />
  @endif
</td>
<td>
  @php
    $now = \Carbon\Carbon::now();
    $startValidity = \Carbon\Carbon::parse($data->startvalidity);
    $endValidity = \Carbon\Carbon::parse($data->endvalidity);
  @endphp

  @if ($now->between($startValidity, $endValidity))
    <!-- If current date is between start and end dates, display green icon -->
    <i class="fa fa-circle text-success"></i>
  @else
    <!-- Else, display red icon -->
    <i class="fa fa-circle text-danger"></i>
  @endif
</td>
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

      
                
<table id="myTableArticle" class="table table-bordred table-striped">
     
     <thead>
     
     
     <th>Image</th>
      <th>Référence</th>
       <th>Titre</th>
       <th>Statut</th>
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
<td>
  {{$data->title}} &nbsp; &nbsp;
  @if($data->	sex_limit === null)
      <!-- Mettez ici l'icône pour null -->
      <img src="{{ asset('assets/images/genders.png') }}" alt="icône null" />
  @elseif($data->	sex_limit === 'female')
      <!-- Mettez ici l'icône pour female -->
      <img src="{{ asset('assets/images/femenine.png') }}" alt="icône female" />
  @elseif($data->	sex_limit === 'male')
      <!-- Mettez ici l'icône pour male -->
      <img src="{{ asset('assets/images/male.png') }}" alt="icône male" />
  @endif
</td>
<td>
  @php
    $now = \Carbon\Carbon::now();
    $startValidity = \Carbon\Carbon::parse($data->startvalidity);
    $endValidity = \Carbon\Carbon::parse($data->endvalidity);
  @endphp

  @if ($now->between($startValidity, $endValidity))
    <i class="fa fa-circle text-success"></i>
  @else
    <i class="fa fa-circle text-danger"></i>
  @endif
</td>
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
 
            
   




</section>

</main>
    



@endsection
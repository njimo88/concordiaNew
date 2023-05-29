@extends('layouts.template')

@section('content')
@php
    require_once(app_path().'/helpers.php');
@endphp
<main id="main" class="main">
@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            <div class="pagetitle">
                <h1>Liste des articles</h1>
                <nav>
                   <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                      <li class="breadcrumb-item">Blog</li>
                      <li class="breadcrumb-item active">Articles</li>
                   </ol>
                </nav>
             </div>     

<section>
	<div class="row">
		
        
    <div class="table-responsive">
    

                        <table style="width:100%;" id="ArticleTable" class="table table-bordred table-striped">
                              <thead>
                   
                   
                     <th>Titre</th>
                     <th>Auteur</th>
                     <th>Date de publication</th>
                     <th>Dernier éditeur </th>
                     <th>Date de modification</th>
                     <th>Statut</th>
                     <th></th>
                      <th></th>
                    
                     
                       
                  	 </thead>
                            <tbody>
                               
                                @foreach($requete_user as $data)
                                <tr style="background-color: <?php echo getAuthorColor($data->lastname, $data->name); ?>">
                                    <td>{{$data->titre}}</td>
                                    <td>{{$data->lastname}} {{$data->name}}</td>
                                    <td><?php echo date("d/m/Y à H:i", strtotime($data->date_post)); ?></td>
                                    <td>{{$data->lastname}} {{$data->name}}</td>
                                    <td><?php echo date("d/m/Y à H:i", strtotime($data->updated_at)); ?></td>
                                    <td>{{$data->status}}</td>
                                    <td >
                                      <p class="m-0" data-placement="top" data-toggle="tooltip" title="Edit">
                                        <a target="_blank" href="{{route('edit_blog_index',['id' => $data->id_blog_post_primaire])}}">
                                          <button  class="btn btn-sm btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit">
                                            <i class="bi bi-pencil-fill"></i>
                                          </button>
                                        </a>
                                      </p>
                                    </td>
                                    <td>
                                      <p class="m-0" data-placement="top" data-toggle="tooltip" title="Delete">
                                        <a href="{{route('delete_blog',['id' => $data->id_blog_post_primaire])}}" onclick="return confirm('êtes-vous sûr de vouloir supprimer ce billet de blog ?');">
                                          <button class="btn btn-sm btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete">
                                            <i class="bi bi-trash"></i>
                                          </button>
                                        </a>
                                      </p>
                                    </td>
                                  </tr>
                                  
    @endforeach
                    
                            </tbody>
                        </table>
                    </div>

<div class="clearfix"></div>
         
            </div>
            
        
        </section>
</main>


@endsection
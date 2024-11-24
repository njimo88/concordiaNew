@extends('layouts.template')

@section('content')
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
    <div class="d-flex justify-content-center align-items-center">
      <button type="button" class="text-center btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignColorModal">
        Attribuer des couleurs aux utilisateurs
      </button>
    </div>


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
                                <tr style="background-color: {{$data->color}}">
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
                                    @if (auth()->user()->roles->estautoriserDeSupprimerBlogArticle)
                                    <td>
                                      <p class="m-0" data-placement="top" data-toggle="tooltip" title="Delete">
                                        <a href="{{route('delete_blog',['id' => $data->id_blog_post_primaire])}}" onclick="return confirm('êtes-vous sûr de vouloir supprimer ce billet de blog ?');">
                                          <button class="btn btn-sm btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete">
                                            <i class="bi bi-trash"></i>
                                          </button>
                                        </a>
                                      </p>
                                    </td>
                                    @endif
                                  </tr>
                                  
    @endforeach
                    
                            </tbody>
                        </table>
                    </div>

<div class="clearfix"></div>
         
            </div>
                    
        </section>

            <!-- Include the Modal of assigning the colors -->

        <div class="modal fade" id="assignColorModal" tabindex="-1" aria-labelledby="assignColorModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="assignColorModalLabel">Attribuer des couleurs aux utilisateurs</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form id="assignColorForm" method="POST" action="{{route('edit_color_user')}}">
                          @csrf
                          <div class="mb-3">
                              <label for="user-select" class="form-label">Select User</label>
                              <select id="user-select" name="user_id" class="form-select w-75">
                                <option  value="null" data-color="#ffffff" >choisir l'utilisateur</option>
                                @foreach($userNamesAndIds as $data)
                                      <option  style="background-color: {{$data['color']}}" data-color="{{$data['color']}}" value="{{$data['user_id']}}">{{$data['name']}} {{$data['lastname']}} </option>
                                @endforeach
                              </select>
                          </div>
                          <div class="mb-3 ">
                              <label for="color-picker" class="form-label">Select Color</label>
                              <input type="color" id="color-picker" name="color" class="form-control form-control-color" value="#96B8ED">
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">fermer</button>
                      <button type="submit" form="assignColorForm" class="btn btn-success">Enregistrer les modifications</button>
                  </div>
              </div>
          </div>
      </div>
      

</main>

<script>
  // JavaScript to update color input based on selected user
  document.getElementById('user-select').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex]; // Get selected option
    var color = selectedOption.getAttribute('data-color'); // Get the color from data-color attribute
    document.getElementById('color-picker').value = color; // Set the color input value
  });
</script>

@endsection
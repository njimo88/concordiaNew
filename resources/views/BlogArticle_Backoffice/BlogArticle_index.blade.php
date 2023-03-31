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
		
        
    <div class="table-responsive">
    

                        <table style="width:100%;" id="myTable" class="table table-bordred table-striped">
                              <thead>
                   
                   
                     <th>Titre</th>
                     <th>Date de publication</th>
                     <th>Auteur</th>
                     <th>Dernier éditeur </th>
                     <th>Date de modification</th>
                     <th>Statut</th>
                     <th>Modifier</th>
                      <th>supprimer</th>
                    
                     
                       
                  	 </thead>
                            <tbody>
                               
                                @foreach($requete_user as $data)
    <tr >
      
        <td>{{$data->titre}}</td>

        <td><?php echo date("d/m/Y à H:i", strtotime($data->date_post)); ?></td>
      
        <td>{{$data->name}}</td>
           
        <td>{{$data->name}}</td>

    
        <td><?php echo date("d/m/Y à H:i", strtotime($data->updated_at)); ?></td>
        <td>{{$data->status}}</td>
        <td><p data-placement="top" data-toggle="tooltip" title="Edit"><a href="{{route('edit_blog_index',['id' => $data->id_blog_post_primaire])}}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" onclick="return confirm('êtes-vous sûr de vouloir modifier ce billet de blog ?');"><i class="bi bi-pencil-fill"></i></button></a></p></td>
        <td><p data-placement="top" data-toggle="tooltip" title="Delete"><a href="{{route('delete_blog',['id' => $data->id_blog_post_primaire])}}"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimerce billet de blog ?');" ><i class="bi bi-trash"></i></button></a></p></td>
    
    </tr>
    @endforeach
                    
                            </tbody>
                        </table>
                    </div>

<div class="clearfix"></div>
<div class="d-flex justify-content-center">
    {!! $requete_blog->links() !!}
</div>          
            </div>
            
        </div>

</main>


@endsection
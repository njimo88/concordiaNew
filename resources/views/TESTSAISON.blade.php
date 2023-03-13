
        
        <div class="table-responsive">

      
                
              <table id="tableID" class="table table-bordred table-striped">
                   
                   <thead>
                   
                   
                   <th>Image</th>
                    <th>Référence</th>
                     <th>Titre</th>
                     <th>Prix TTC</th>
                     <th>Prix Cumulé</th>
                      <th>Stock</th>
                      <th>Modifier</th>
                      <th>Supprimer</th>
                      <th>Dupliquer</th>
                      
                       
                   </thead>
    <tbody>
    @foreach($requete_article as $data)
    <tr>
      
      
        <td><img src="{{$data->image}}" style="height: 60px; width:60px"></td>
        <td>{{$data->ref}}</td>
        <td>{{$data->title}}</td>
        <td>{{$data->price}}</td>
        <td>{{$data->totalprice}}</td>
        <td>{{$data->stock_actuel}}</td>
        <td><p data-placement="top" data-toggle="tooltip" title="Editer"><a href="{{route('edit_article', [ 'id' => $data->id_shop_article])}}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" onclick="return confirm('êtes-vous sûr de vouloir modifier?');"><i class="bi bi-pencil-fill"></i></button></a></p></td>
        <td><p data-placement="top" data-toggle="tooltip" title="Effacer"><a href="{{route('delete_article',[ 'id' => $data->id_shop_article])}}"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer?');" ><i class="bi bi-trash"></i></button></a></p></td>
        <td><p data-placement="top" data-toggle="tooltip" title="Dupliquer"><a href="{{route('duplicate_article_index', [ 'id' => $data->id_shop_article])}}"><button class="btn btn-success btn-xs" data-title="Edit" data-toggle="modal"><i class="fa fa-clone " ></i> </button></a></p></td>
       
    </tr>
    @endforeach
        
    </tbody>
        
</table>



 



</div>






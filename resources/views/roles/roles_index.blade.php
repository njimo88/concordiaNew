@extends('layouts.template')

@section('content')
<style>
  .form-control {
    max-height: 20px;
  }
  table {
  border-collapse: collapse;
  border-spacing: 0;
  display:block;
  overflow:auto;
  height:600px;
  width:100%;
  border: 1px solid #ddd;
}

.input {
  margin:  0 !important;
}
</style>


<main id="main" class="main">

                    @if(session()->has('success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif


<div class="container">

                                <table class="table table-hover">
                                    <thead style="color:black, background-color:black">
                                        <tr>
                                        <th scope="col">modifier les droits</th>
                                        <th scope="col">name</th>
                                        <th scope="col">estAutoriserAVoirMembres</th>
                                        <th scope="col">estAutoriserDeVoirClickAsso</th>
                                        <th scope="col">estAutoriserDeRedigerArticle</th>
                                        <th scope="col">estAutoriserDeGererSlider</th>
                                        <th scope="col">estAutoriserDeVoirCategories</th>
                                        <th scope="col"> estAutoriserDeVoirFacture</th>
                                        <th scope="col"> estAutoriserDeVoirReduction</th>
                                        <th scope="col"> estAutoriserDeVoirArticleBoutique</th>
                                        <th scope="col"> estAutoriserDeVoirCategorieBoutique</th>
                                        <th scope="col"> estAutoriserDeVoirMessages</th>
                                        <th scope="col"> estAutoriserDeVoirHistorique</th>
                                        <th scope="col"> estAutoriserDeVoirCours</th>
                                        <th scope="col"> estAutoriserDeVoirAnimations</th>
                                        <th scope="col"> estAutoriserDeVoirStatsExports</th>
                                        <th scope="col"> estAutoriserDeVoirValiderCertificats</th>
                                        <th scope="col"> estAutoriserDeVoirGestionProfessionnels</th>
                                        <th scope="col"> estAutoriserDeVoirCalculDesSalaires</th>
                                        <th scope="col"> estAutoriserDeVoirValiderLesHeures</th>
                                        <th scope="col"> estAutoriserDeVoirGestionDesDroits</th>
                                        <th scope="col"> estAutoriserDeVoirParametresGeneraux</th>
                                        <th scope="col"> 	estAutoriserDeVoirSalles</th>
                                        <th scope="col"> 	estAutoriserDeVoirMessageGeneral</th>
                                        <th scope="col"> 	supprimer_edit_ajout_user</th>
                                        <th scope="col"> 	reinitialiser_mot_de_passe_user</th>
                                        <th scope="col"> 	supprimer_edit_facture</th>
                                        <th scope="col"> 	changer_status_facture</th>
                                        <th scope="col">    paiement_immediat</th>
                                        <th scope="col">    changer_designation_facture</th>
                                        <th scope="col">    supprimer_edit_ajout_categorie</th>
                                        <th scope="col">   supprimer_edit_dupliquer_ajout_article</th>
                                        <th scope="col">  edit_ajout_professionnel</th>
                                        <th scope="col">  declarer_heure_professionnel</th>
                                        <th scope="col">   voir_declaration_professionnel</th>
                                       

                                        


                                        

                                        
                                       
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($roles as $r)
                                        <tr>

                                        <td><p data-placement="top" data-toggle="tooltip" title="Editer"><a href="{{ route('edit_index',['id' => $r->id]) }}"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" onclick="return confirm('êtes-vous sûr de vouloir modifier ce role ?');"><i class="bi bi-pencil-fill"></i></button></a></p></td>
                                        <td data-bs-toggle="modal" data-bs-target="#exampleModal" data-user-id="{{$r->id}}">{{$r->name}} 
                                        </td>
                                        <td>{{$r->estAutoriserDeVoirMembres}} </td>
                                        <td>{{$r->estAutoriserDeVoirClickAsso}}</td>
                                        <td>{{$r->estAutoriserDeRedigerArticle}}</td>
                                        <td>{{$r->estAutoriserDeGererSlider}}</td>
                                        <td>{{$r->estAutoriserDeVoirCategories}}</td>
                                        <td>{{$r->estAutoriserDeVoirFacture}}</td>
                                        <td>{{$r->estAutoriserDeVoirReduction}}</td>
                                        <td>{{$r->estAutoriserDeVoirArticleBoutique}}</td>
                                        <td>{{$r->estAutoriserDeVoirCategorieBoutique}}</td>
                                        <td>{{$r->estAutoriserDeVoirMessages}}</td>
                                        <td>{{$r->estAutoriserDeVoirHistorique}}</td>
                                        <td>{{$r->estAutoriserDeVoirCours}}</td>
                                        <td>{{$r->estAutoriserDeVoirAnimations}}</td>
                                        <td>{{$r->estAutoriserDeVoirStatsExports}}</td>
                                        <td>{{$r->estAutoriserDeVoirValiderCertificats}}</td>
                                        <td>{{$r->estAutoriserDeVoirGestionProfessionnels}}</td>
                                        <td>{{$r->estAutoriserDeVoirCalculDesSalaires}}</td>
                                        <td>{{$r->estAutoriserDeVoirValiderLesHeures}}</td>
                                        <td>{{$r->estAutoriserDeVoirGestionDesDroits}}</td>
                                        <td>{{$r->estAutoriserDeVoirParametresGeneraux}}</td>
                                        <td>{{$r->estAutoriserDeVoirSalles}}</td>
                                        <td>{{$r->estAutoriserDeVoirMessageGeneral}}</td>
                                        <td>{{$r->supprimer_edit_ajout_user}}</td>
                                        <td>{{$r->reinitialiser_mot_de_passe_user}}</td>
                                        <td>{{$r->supprimer_edit_facture}}</td>
                                        <td>{{$r->changer_status_facture}}</td>
                                        <td>{{$r->paiement_immediat}}</td>
                                        <td>{{$r->changer_designation_facture}}</td>
                                        <td>{{$r->supprimer_edit_ajout_categorie}}</td>
                                        <td>{{$r->supprimer_edit_dupliquer_ajout_article}}</td>
                                        <td>{{$r->edit_ajout_professionnel}}</td>
                                        <td>{{$r->declarer_heure_professionnel}}</td>
                                        <td>{{$r-> voir_declaration_professionnel}}</td>
                                       
                                       
                                        
                                        
                                       
                                        
                                       


                                     
                                      
                                        </tr>
                                        @endforeach
                                       
                                      
                                    </tbody>


                                    </table>


</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


</main>





@endsection
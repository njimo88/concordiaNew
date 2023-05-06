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
.small-input {
  width: 50px;
}

.myinput {
    width: 170px;
}

</style>


<main id="main" class="main">

                    @if(session()->has('success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif


<div class="container">
<div class="row">
<h1> Gestion des rôles</h1>
<hr>
<br>

</div>
<div class="row">
<div class="col-md-4">

<button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#Modal_new_type">Créer un nouveau type d'utilisateurs </button>
</div>


    
<div class="col-md-12">


<br>

    
                                <table class="table table-hover">
                                    <thead style="color:black, background-color:black">
                                        <tr>

                                        <th scope="col"></th>
                                        

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
                                        <th> 	            supprimer_edit_ajout_user</th>
                                        <th> 	           reinitialiser_mot_de_passe_user</th>
                                        <th> 	          supprimer_edit_facture</th>
                                        <th>   paiement_immediat </th>
                                        <th>  changer_status_facture </th>
                                        <th>changer_designation_facture</th>
                                        <th> supprimer_edit_ajout_categorie</th>
                                        <th>supprimer_edit_dupliquer_ajout_article</th>
                                        <th>edit_ajout_professionnel</th>
                                        <th>declarer_heure_professionnel</th>
                                        <th>voir_declaration_professionnel</th>
                                       

    
                                  
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($roles as $r)
                
                                        <tr>

                         <form method="POST", action="{{route('modif_roles',['id' => $r->id]) }}">
                         {{ csrf_field() }}
                                        <td><button type="submit" class="btn-xs"> <i class="bi bi-pencil-fill"></i></button></td>
                                      
                                        <td>
                                        <input class="myinput" type="text" name="name" value="{{$r->name}}">
                                        
                                        </td>
                                        
                                        <td>  <input  type="number"  min="0" max="1" aria-label="First name" class="form-control small-input"  name="estAutoriserDeVoirMembres" value="{{$r->estAutoriserDeVoirMembres}}"></td>
                                        <td>  <input  type="number"  min="0" max="1" aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirClickAsso" value="{{$r->estAutoriserDeVoirClickAsso}}" ></td>
                                        <td>   <input  type="number"  min="0" max="1" aria-label="First name" class="form-control small-input" name="estAutoriserDeRedigerArticle" value="{{$r->estAutoriserDeRedigerArticle}}" ></td>
                                        <td>   <input  type="number"  min="0" max="1" aria-label="First name" class="form-control small-input" name="estAutoriserDeGererSlider" value="{{$r->estAutoriserDeGererSlider}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirCategories" value="{{$r->estAutoriserDeVoirCategories}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirFacture" value="{{$r->estAutoriserDeVoirFacture}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input"  name="estAutoriserDeVoirReduction" value="{{$r->estAutoriserDeVoirReduction}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirArticleBoutique"value="{{$r->estAutoriserDeVoirArticleBoutique}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirCategorieBoutique" value="{{$r->estAutoriserDeVoirCategorieBoutique}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirMessages" value="{{$r->estAutoriserDeVoirMessages}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirHistorique" value="{{$r->estAutoriserDeVoirHistorique}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirCours" value="{{$r->estAutoriserDeVoirCours}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirAnimations" value="{{$r->estAutoriserDeVoirAnimations}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirStatsExports" value="{{$r->estAutoriserDeVoirStatsExports}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirValiderCertificats" value="{{$r->estAutoriserDeVoirValiderCertificats}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirGestionProfessionnels" value="{{$r->estAutoriserDeVoirGestionProfessionnels}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirCalculDesSalaires" value="{{$r->estAutoriserDeVoirCalculDesSalaires}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirValiderLesHeures" value="{{$r->estAutoriserDeVoirValiderLesHeures}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirGestionDesDroits" value="{{$r->estAutoriserDeVoirGestionDesDroits}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirParametresGeneraux" value="{{$r->estAutoriserDeVoirParametresGeneraux}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirSalles" value="{{$r->estAutoriserDeVoirSalles}}" ></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="estAutoriserDeVoirMessageGeneral" value="{{$r->estAutoriserDeVoirMessageGeneral}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="supprimer_edit_ajout_user" value="{{$r->supprimer_edit_ajout_user}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="reinitialiser_mot_de_passe_user" value="{{$r->reinitialiser_mot_de_passe_user}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="supprimer_edit_facture" value="{{$r->supprimer_edit_facture}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="paiement_immediat" value="{{$r->paiement_immediat}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="changer_status_facture" value="{{$r->changer_status_facture}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="changer_designation_facture" value="{{$r->changer_designation_facture}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="supprimer_edit_ajout_categorie" value="{{$r->supprimer_edit_ajout_categorie}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="supprimer_edit_dupliquer_ajout_article" value="{{$r->supprimer_edit_dupliquer_ajout_article}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="edit_ajout_professionnel" value="{{$r->edit_ajout_professionnel}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="declarer_heure_professionnel" value="{{$r->declarer_heure_professionnel}}"></td>
                                        <td><input type="number"  min="0" max="1"  aria-label="First name" class="form-control small-input" name="voir_declaration_professionnel" value="{{$r->voir_declaration_professionnel}}"></td>
                                           
                                     
                            </form>  
                                        </tr>

                                      
                        
                                        @endforeach
                                       
                                      
                                    </tbody>


                                    </table>

<div class="col-md-4">


</div>

</div>
    



</div>


</div>

<!-- Modal -->
<div class="modal fade" id="Modal_new_type" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <div id="container">

        <form method="POST", action="{{route('creation_roles')}}">
                         {{ csrf_field() }}


                         <div class="input-group mb-3">

  <input type="number" name="id" placeholder="ID" aria-label="Recipient's username" aria-describedby="button-addon2">
  <input type="text" name="nom_role"  placeholder="intitulé du role" aria-label="Recipient's username" aria-describedby="button-addon2">
  
  <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Valider</button>

                        </div>

                

                        



        </form>



        </div>
    

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        
      </div>
    </div>
  </div>
</div>




</main>




@endsection
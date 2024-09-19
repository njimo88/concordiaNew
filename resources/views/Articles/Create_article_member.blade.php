@extends('layouts.template')

@section('content')
<style>
        input{
            margin: 0 !important;
                margin-bottom: 10px !important;
        }
        .form-switch {
         padding-left: 0 !important;
    }
    </style>
<main id="main" class="main">
        @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
        @endif

        <div class="row py-4 justify-content-end">
                <div class="col-md-12">
                        <a href="{{route('index_article')}}"><button style="float: right" class="btn btn-danger"> Retour</button></a>
                </div>
        </div>

          
<form   method="POST" action="{{route('create_article_member')}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
        @csrf
                <div class="mb-4 text-center">
                        <h3 class="bg-success text-white p-2">Paramètres Généraux</h3>  
                    </div>
                
                <form class="p-3 border border-secondary rounded-bottom">
                    <div class="row ">
                        <div class="col-md-2 col-6 mb-3">
                            <label for="saison">Saison</label>
                            <select id="saison" class="form-control" name="saison">
                                @foreach($saison_list as $data)
                                    <option value="{{$data->saison}}">{{$data->saison}} - {{$data->saison + 1 }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-6">
                                <label for="title">Titre</label>
                                    
                                    <input required id="title" class="form-control" name="title" for="title" type="text" value="">
                        </div>
                <div class="col-md-2 col-6">
                <label for="image">Image</label>
                
                <input class="imageUpload form-control" id="image" required for="image" name="image" type="upload" placeholder="Image">
                </div>
                <div class="col-md-2 col-6">
                <label for="ref">Référence</label>
                
                        <input id="ref" class="form-control" for="ref" name="ref" type="text" placeholder="Référence">
                </div>
                <div class="col-md-2 col-6">
                <label for="img">Couleur</label>
                    <input id="img" type="color" class="form-control" class="color" name="color" value="">
                </div>
                
                <div class="col-md-2 col-6 ">
            <label> Début de validité : </label>
                    <input required type="date" class="form-control" name="startvalidity" value="">
                </div>

                <div class="col-md-2 col-6">
                <label> Fin de validité :</label>
                    <input required type="date" class="form-control" name="endvalidity" value="">
                </div>

                <div class="col-md-2 col-6">

                    <label>  Statut :   </label> 
                        
                            <select value="0" name="need_member" class="form-control" id="require">
                                <option value="0">Non membre</option>
                                <option value="1">membre loisir</option>
                                <option value="3">membre compétition</option>
                            
                    </label>       
                            </select>

                </div>

                <div class="col-md-2 col-6">

                
                        <label>Age Minimal</label><input type="number" class="form-control" name="agemin" step="0.01" value="" required>

                </div>

                <div class="col-md-2 col-6">

                <label>Age Maximal</label><input type="number" class="form-control" name="agemax" step="0.01" value="" required>

                </div>

                <div class="col-md-2 col-6">

                     <label> Prix TTC :</label>
                    
                    <input step="0.01" class="form-control" name="price" for="TTC" type="number" value='' required>

                </div>

            <div class="col-md-2 col-6">

            <label> Prix indicatif :</label>
                    
                    <input step="0.01" class="form-control" name="price_indicative" for="TTC" type="number" value='' required>

            </div>

            <div class="col-md-2 col-6">

            <label> Quantité initale:</label>
                    
                    <input step="0.01" class="form-control" name="stock_ini" for="TTC" type="number" value='' required>

            </div>

            <div class="col-md-2 col-6">

            <label>  Quantité restante: </label>
                    <input step="0.01" class="form-control" name="stock_actuel" for="TTC" type="number" value='' required>

            </div>

            <div class="col-md-2 col-6">
            <label>  Quantité alerte:</label> 
                    <input step="0.01" class="form-control" name="alert_stock" for="TTC" type="number" value='' required>

            </div>

            <div class="col-md-2 col-6">
            <label> type article  :</label>
                    <input step="1" class="form-control" name="type_article" for="" type="number" value='0' required readonly>

            </div>

            <div class="col-md-2 col-6">
            <label>  Max utilisateur:</label>
                    <input  class="form-control" name="max_per_user" for="" type="number" value='' required>

            </div>
            <div class="col-md-6">
                <div style="height: 250px; overflow: auto;" class="border rounded p-3 bg-light">
                    <table class="table table-striped">
                        <thead>
                            <tr>        
                                <th scope="col">Choix des catégories</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($requete_cate as $data)
                            <tr>    
                                <td>{{$data->name}}</td>     
                                <td class="text-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input mb-0" type="checkbox" id="category{{$data->id_shop_category}}" name="category[]" value="{{$data->id_shop_category}}">
                                        <label class="form-check-label" for="category{{$data->id_shop_category}}"></label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6 col-6 row">
                                        
                                        
                <div class="col-md-4  mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="strict" name="strict" value="1" >
                        <label class="form-check-label" for="strict">&nbsp Mode strict</label>
                    </div>
                </div>
                <div class="col-md-4  mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="afiscale" name="afiscale" value="1" >
                        <label class="form-check-label" for="afiscale">&nbsp Attestation fiscale</label>
                    </div>
                </div>
                <div class="col-md-3  mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="nouveaute" name="nouveaute" value="1" >
                        <label class="form-check-label" for="nouveaute">&nbsp Nouveauté</label>
                    </div>
                </div>
                        <label class="mr-2">Limitation par sexe:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="sexLimitMixte" name="sex_limit" value="0" >
                            <label class="form-check-label" for="sexLimitMixte">Mixte</label>
                        </div>
                
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="sexLimitFemme" name="sex_limit" value="1" >
                            <label class="form-check-label" for="sexLimitFemme">Femme</label>
                        </div>
                
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="sexLimitHomme" name="sex_limit" value="2" >
                            <label class="form-check-label" for="sexLimitHomme">Homme</label>
                        </div>
                
                
                
            </div>
           
</div>

                    
<br> 
<style>
        label{
            font-weight: bold;
            color: rgba(0, 0, 0, 0.356);
        }
</style>
<!-- row beige  -->
  <!-- Conteneur global -->
<div class="container mt-4">
        <!-- Première rangée - Paramètres spécifiques -->
        <div class="row py-4 rounded shadow-lg mb-4" style="background-color: #B2B1CF;">
      
          <div class="col-12">
            <h3 class="text-center text-dark">Paramètres spécifiques</h3>
          </div>
          
          <div class="col-md-4 my-2">
            <label>Prix d'adhésion:</label>
            <input class="form-control" name="prix_adhesion" type="number" required>
          </div>
      
          <div class="col-md-4 my-2">
            <label>Prix d'assurance:</label>
            <input class="form-control" name="prix_assurance" type="number" required>
          </div>
      
          <div class="col-md-4 my-2">
            <label>Prix de la licence de fédération:</label>
            <input class="form-control" name="prix_licence_fede" type="number" required>
          </div>
      
        </div>
        
        <!-- Deuxième rangée - Description -->
        <div class="row py-4 rounded shadow-lg" style="background-color: #A2D9CE;">
      
          <div class="col-12">
            <h3 class="text-center text-dark">Description</h3>
          </div>
      
          <div class="col-sm-12 my-2">
            <label>Résumé </label>
            <textarea type="text" name="short_description" class="form-control"></textarea>
          </div>
      
          <div class="col-sm-12 my-2">
            <label>Description</label>
            <textarea name="editor1" id="ckeditor" class="form-control" required></textarea>
          </div>
      
        </div>
      </div>
      


</div>
           
</div>
<div class="row justify-content-center mt-3"> 
    <div class="col-md-4 justify-content-center"> 
    <input class="btn btn-success" name="modifier" type="submit" value="Valider">
    </div>     
</div>
</form>

<script src="//cdn.ckeditor.com/4.25.0/full/ckeditor.js"></script>




</main>
@endsection
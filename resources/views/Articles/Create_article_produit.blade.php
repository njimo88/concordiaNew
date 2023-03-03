@extends('layouts.template')


@section('content')


<main id="main" class="main">
<div class="container">

@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            <div class="row pt-5">
                    <div class="col-md-10">
                        
                    </div>
                    <div class="col-md-2">
                           <a href="{{route('index_article')}}"><button class="btn btn-warning"> retour</button></a>
                    </div>
</div>
          
<form  method="POST" action="{{route('create_article_produit')}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
@csrf
                <div class="row"> 
                    <div class="col-md-11">
                    
                    <input class="btn btn-warning" name="modifier" type="submit" value="Valider">
                    </div>
                   
                   
                </div>
                <br>
                <!-- row vert  -->
      <div class="row" style="background-color: #c6ffc1; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">
                <h3>Paramètres Généraux</h3>  
                        <div class="col-md-2 col-6">
                            <label for="saison">Saison</label>
                                <select id="saison" class="form-control" name="saison">
                                    @foreach($saison_list as $data)
                                    <option value="{{$data->saison}}">{{$data->saison}} - {{$data->saison + 1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                    <label for="title">Titre</label>
                                        
                                        <input  id="title" class="form-control" name="title" for="title" type="text" value="">
                            </div>
                    <div class="col-md-2 col-6">
                    <label for="image">Image</label>
                    
                    <input class="imageUpload form-control" id="image" for="image" name="image" type="upload" placeholder="Image">
                    </div>
                    <div class="col-md-2 col-6">
                    <label for="ref">Référence</label>
                    
                            <input id="ref" class="form-control" for="ref" name="ref" type="text" placeholder="Référence">
                    </div>
                    <div class="col-md-2 col-6">
                    <label for="img">Couleur</label>
                        <input id="img" type="color" class="form-control" class="color" name="color" value="">
                    </div>
                    <div class="col-md-2 col-6">
                    <label for="attestation">Nouveauté</label>
                <input type="checkbox"  for="id_shop_article" value='1' name="nouveaute">
                    </div>

                    <div class="col-md-2 col-6 ">
                <label> Début de validité : </label>
                        <input type="date" class="form-control" name="startvalidity" >
                    </div>

                    <div class="col-md-2 col-6">
                    <label> Fin de validité :</label>
                        <input  type="date" class="form-control" name="endvalidity">
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

                    
                            <label>Age Minimal</label><input type="number" class="form-control" name="agemin" step="0.01"  >

                    </div>

                    <div class="col-md-2 col-6">

                    <label>Age Maximal</label><input type="number" class="form-control" name="agemax" step="0.01" >

                    </div>

                    <div class="col-md-2 col-6">

                         <label> Prix TTC :</label>
                        
                        <input step="0.01" class="form-control" name="price" for="TTC" type="number" >

                    </div>

                <div class="col-md-2 col-6">

                <label> Prix indicatif :</label>
                        
                        <input step="0.01" class="form-control" name="price_indicative" for="TTC" type="number"  >

                </div>

                <div class="col-md-2 col-6">

                <label> Quantité initale:</label>
                        
                        <input step="0.01" class="form-control" name="stock_ini" for="TTC" type="number"  >

                </div>

                <div class="col-md-2 col-6">

                <label>  Quantité restante: </label>
                        <input step="0.01" class="form-control" name="stock_actuel" for="TTC" type="number" >

                </div>

                <div class="col-md-2 col-6">
                <label>  Quantité alerte:</label> 
                        <input step="0.01" class="form-control" name="alert_stock" for="TTC" type="number" >

                </div>

                <div class="col-md-2 col-6">
                <label> type article  :</label>
                        <input step="1" class="form-control" name="type_article"  type="number" value="2"  readonly> 

                </div>

                <div class="col-md-2 col-6">
                <label>  Max utilisateur:</label>
                        <input  class="form-control" name="max_per_user" for="" type="number" >

                </div>
             
                <div class="row pt-3"> 
                <div class="col-md-4 col-6">
                <label>  Mode strict:</label>

                <table class="table">
                   

                        <tr>
                    
                        <td><input style="vertical-align:center;" for="" type="checkbox" name="strict" value="1" ></td>

                        </tr>
                       
            </table>


                </div>
                <div class="col-md-4 col-6">

                <label>  Attestation fiscale :</label>

                        <table class="table">

                                <tr>
                                <input type="hidden" name="afiscale" value="0" />
                                <td><input type="checkbox"  for="" value='1' name="afiscale"></td>

                                </tr>
                            
                        </table>

               
               

                </div>
                <div class="col-md-4 col-6 pb-5">
                 
                        <fieldset>
     <label> Limitation par sexe: </label> 
    <div>
      <input type="radio" id="" name="sex_limit" value=0
             checked>
      <label for="">Mixte</label>
    </div>

    <div>
      <input type="radio" id="dewey" name="sex_limit" value=1>
      <label for="">Femme</label>
    </div>

    <div>
      <input type="radio" id="" name="sex_limit" value=2>
      <label for="">Homme</label>
    </div>
</fieldset>

              
                        
                      
                
                </div>
                <div class="col-md-12">
    <div style="height: 250px;  overflow: scroll; ">
<table class="table">
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
     
      <td><input style="vertical-align:center;" for="catenvoi" type="checkbox" name="category[]" value="{{$data->id_shop_category}}" ></td>
     

    </tr>

    @endforeach
   
  </tbody>
</table>
</div>
    </div>









                </div>


</div>
<br> 






<!-- row beige  -->
  <div class="row" style="background-color: beige;border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

  <h3>Paramètres spécifiques</h3>
  <br>

<div class="adp-box">
<div class="inputWrapper">
</div>
<button type="button" class="addInput"><i class="fa fa-plus"></i> &nbsp; Autres Ajouts</button>
</div>
<div class="form-group">
<textarea class="form-control" rows="10" id="getData" name="Json_declinaison" hidden></textarea>
</div>

  </div>

 
</div>
         
  </div>

<!-- row rose -->
  <div class="row" style="background-color:pink; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

          
          <div class="row">
          
              <div class="col-sm-12">
                        <br>
            
                
                              <label>Résumé </label>
                                <textarea type="text" name="short_description" class="form-control"></textarea>
                              <label>Description</label>
                                <textarea name="editor1"  id="ckeditor" class="form-control" required></textarea>
                                            
              </div>
          
          
          
          </div>
    
          
  </div>


</div>
           
</div>

</form>

   
 
  


<script src="//cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script>


</main>
@endsection
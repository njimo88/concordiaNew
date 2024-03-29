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


@php  $type_article = 0 ; 

foreach($Shop_article as $value1){
    $type_article = $value1->type_article ;
}





@endphp
 


    @if($type_article == 0)
       
    <main id="main" class="main">

        <div class="container">
                             @if(session()->has('success'))
                                             <div class="alert alert-success">
                                                 {{ session()->get('success') }}
                                             </div>
                                         @endif
 
                                         <div class="row pt-5justify-content-end">
                                                 <div class="col-md-2">
                                                     
                                                 </div>
                                                 <div class="col-md-12">
                                                     <a href="{{route('index_article')}}"><button style="float: right" class="btn btn-danger"> Retour</button></a>
                                                 </div>
                             </div>
                             
 
                                     
                             <form  method="POST" action="{{route('edit_article',$Id)}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
                             @csrf
                             <div class="my-4 text-center">
                                 <h3 class="bg-success text-white p-2">Paramètres Généraux</h3>  
                             </div>
                     
                             <div class="row">
                                 @foreach($Shop_article as $value1)
                                     <div class="col-md-2 mb-3">
                                         <label for="saison">Saison</label>
                                         <select id="saison" class="form-control" name="saison">
                                             @foreach($saison_list as $data)
                                                 <option value="{{$data->saison}}" {{ $value1->saison == $data->saison ? 'selected' : '' }}> {{$data->saison}} - {{$data->saison + 1 }}</option>
                                             @endforeach
                                         </select>
                                     </div>
                                     <div class="col-md-4 col-6">
                                         <label for="title">Titre</label>
                                             
                                             <input required id="title" class="form-control" name="title" for="title" type="text" value="{{$value1->title}}">
                                 </div>
                         <div class="col-md-2 col-6">
                         <label for="image">Image</label>
                         
                         <input class="imageUpload form-control" id="image" required for="image" name="image" type="upload" placeholder="Image" value="{{$value1->image}}">
                         </div>
                         <div class="col-md-2 col-6">
                         <label for="ref">Référence</label>
                         
                                 <input id="ref" class="form-control" for="ref" name="ref" type="text" placeholder="Référence" value="{{$value1->ref}}">
                         </div>
                        
                         <div class="col-md-2 col-6 mb-3">
                         <label for="img">Couleur</label>
                         <input class="jscolor {width:243, height:150, position:'right', borderColor:'#000', insetColor:'#FFF', backgroundColor:'#DDD'}">
                     </div>
                     
                     
                 
                         <div class="col-md-2 col-6 ">
                     <label> Début de validité : </label>
                             <input required type="date" class="form-control" name="startvalidity" value="{{$value1->startvalidity}}">
                         </div>
                         <div class="col-md-2 col-6">
                                                      <label> Fin de validité :</label>
                                                          <input required type="date" class="form-control" name="endvalidity" value="{{$value1->endvalidity}}">
                                                      </div>
                 
                                                      <div class="col-md-2 col-6">
                                                        <label>Statut :</label>
                                                        <select name="need_member" class="form-control" id="require">
                                                            <option value="0" {{ $value1->need_member == 0 ? 'selected' : '' }}>Non Membre</option>
                                                            @if(isset($articleLicence1))
                                                                <option value="{{ $articleLicence1->id_shop_article }}" {{ $value1->need_member == $articleLicence1->id_shop_article ? 'selected' : '' }}>
                                                                    {{ $articleLicence1->title }}
                                                                </option>
                                                            @endif
                                                            @if(isset($articleLicence2))
                                                                <option value="{{ $articleLicence2->id_shop_article }}" {{ $value1->need_member == $articleLicence2->id_shop_article ? 'selected' : '' }}>
                                                                    {{ $articleLicence2->title }}
                                                                </option>
                                                            @endif
                                                            @if(isset($articleLicence3))
                                                                <option value="{{ $articleLicence3->id_shop_article }}" {{ $value1->need_member == $articleLicence3->id_shop_article ? 'selected' : '' }}>
                                                                    {{ $articleLicence3->title }}
                                                                </option>
                                                            @endif
                                                            @if(isset($articleLicence4))
                                                                <option value="{{ $articleLicence4->id_shop_article }}" {{ $value1->need_member == $articleLicence4->id_shop_article ? 'selected' : '' }}>
                                                                    {{ $articleLicence4->title }}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    
                 
                                                      <div class="col-md-2 col-6">
                 
                                                      
                                                              <label>Age Minimal</label><input type="number" class="form-control" name="agemin" step="0.01" value="{{$value1->agemin}}" required>
                 
                                                      </div>
                 
                                                      <div class="col-md-2 col-6">
                 
                                                      <label>Age Maximal</label><input type="number" class="form-control" name="agemax" step="0.01" value="{{$value1->agemax}}" required>
                 
                                                      </div>
                 
                                                      <div class="col-md-2 col-6">
                 
                                                          <label> Prix TTC :</label>
                                                          
                                                          <input step="0.01" class="form-control" name="price" for="TTC" type="number" value='{{$value1->price}}' required>
                 
                                                      </div>
                 
                                                  <div class="col-md-2 col-6">
                 
                                                  <label> Prix indicatif :</label>
                                                          
                                                          <input step="0.01" class="form-control" name="price_indicative" for="TTC" type="number" value='{{$value1->price_indicative}}' required>
                 
                                                  </div>
                 
                                                  <div class="col-md-2 col-6">
                 
                                                     <label> Quantité initale:</label>
                                                             
                                                             <input step="0.01" class="form-control" name="stock_ini" for="TTC" type="number" value='{{$value1->stock_ini}}' required>
                 
                                                     </div>
                 
                                                     <div class="col-md-2 col-6">
                 
                                                     <label>  Quantité restante: </label>
                                                             <input step="0.01" class="form-control" name="stock_actuel" for="TTC" type="number" value='{{$value1->stock_actuel}}' required>
                 
                                                     </div>
                 
                                                     <div class="col-md-2 col-6">
                                                     <label>  Quantité alerte:</label> 
                                                             <input step="0.01" class="form-control" name="alert_stock" for="TTC" type="number" value='{{$value1->alert_stock}}' required>
                 
                                                     </div>
                 
                                                     <div class="col-md-2 col-6">
                                                     <label> type article  :</label>
                                                             <input step="1" class="form-control" name="type_article" for="" type="number" value='{{$value1->type_article }}' required readonly>
                 
                                                     </div>
                 
                                                     <div class="col-md-2 col-6">
                                                     <label>  Max utilisateur:</label>
                                                             <input  class="form-control" name="max_per_user" for="" type="number" value='{{$value1->max_per_user}}' required>
                 
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
                                                                 @php
                                                                     $json_cate = json_decode($value1->categories) ?? [];
                                                                 @endphp
                                                                     
                                                                 @foreach($requete_cate as $data)
                                                                     <tr>    
                                                                         <td>{{$data->name}}</td>     
                                                                         <td class="text-center">
                                                                             <div class="form-check form-check-inline">
                                                                                 <input class="form-check-input mb-0" type="checkbox" id="category{{$data->id_shop_category}}" name="category[]" value="{{$data->id_shop_category}}" {{ in_array($data->id_shop_category,$json_cate) ? 'checked ': ""}}>
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
                                                                 <input class="form-check-input" type="checkbox" id="strict" name="strict" value="1" {{ $value1->selected_limit == 1 ? 'checked' : '' }}>
                                                                 <label class="form-check-label" for="strict">&nbsp Mode strict</label>
                                                             </div>
                                                         </div>
                                                         <div class="col-md-4  mt-4">
                                                             <div class="form-check form-switch">
                                                                 <input class="form-check-input" type="checkbox" id="afiscale" name="afiscale" value="1" {{ $value1->afiscale == 1 ? 'checked' : '' }}>
                                                                 <label class="form-check-label" for="afiscale">&nbsp Attestation fiscale</label>
                                                             </div>
                                                         </div>
                                                         <div class="col-md-3  mt-4">
                                                             <div class="form-check form-switch">
                                                                 <input class="form-check-input" type="checkbox" id="nouveaute" name="nouveaute" value="1" {{ $value1->nouveaute == 1 ? 'checked' : '' }}>
                                                                 <label class="form-check-label" for="nouveaute">&nbsp Nouveauté</label>
                                                             </div>
                                                         </div>
                                                                 <label class="mr-2">Limitation par sexe:</label>
                                                                 <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" id="sexLimitMixte" name="sex_limit" value="" {{ $value1->sex_limit === NULL ? 'checked' : ''}}>
                                                                    <label class="form-check-label" for="sexLimitMixte">Mixte</label>
                                                                </div>
                                                                                                             
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" id="sexLimitFemme" name="sex_limit" value="female" {{ $value1->sex_limit === 'female' ? 'checked' : ''}}>
                                                                    <label class="form-check-label" for="sexLimitFemme">Femme</label>
                                                                </div>
                                                                                                             
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" id="sexLimitHomme" name="sex_limit" value="male" {{ $value1->sex_limit === 'male' ? 'checked' : ''}}>
                                                                    <label class="form-check-label" for="sexLimitHomme">Homme</label>
                                                                </div>
                                                                
                                                         
                                                         
                                                         
                                                     </div>
                                                     
                                                     
                                     
                                                     
                                                     
                                 @endforeach
                             </div>
                     
                             
                             <br> 

                                    @foreach($shop_article_0 as $article)

                                    <div class="row" style="background-color:lightblue; border: 2px solid grey;justify-content: center; font-family: 'font-name'">
                                        
                                        <div class="col-sm-12 text-center">
                                            <p>Prix Adhesion: {{$article->prix_adhesion}}</p>
                                            <p>Prix Assurance: {{$article->prix_assurance}}</p>
                                            <p>Prix Licence Fede: {{$article->prix_licence_fede}}</p>
                                        </div>

                                    </div>

                                    @endforeach


                             <br>
                            @foreach($shop_article_0 as $data)

                            <!-- row beige  -->
                            <div class="row" style="background-color: beige; border :2px solid grey; justify-content: center">

                            <h3>Paramètres spécifiques</h3>
                            <br>


                                        <div class="row pb-3 pt-3"> 

                                            <div class="col-md-4 col-6">
                                                <label>  Prix d'adhésion:</label>
                                                        <input  class="form-control" name="prix_adhesion" for="" type="number" value='{{$data->prix_adhesion}}' required>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <label>  Prix d'assurance:</label>
                                                        <input  class="form-control" name="prix_assurance" for="" type="number" value='{{$data->prix_assurance}}' required>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <label>  Prix de la licence de fédération:</label>
                                                        <input  class="form-control" name="prix_licence_fede" for="" type="number" value='{{$data->prix_licence_fede}}' required>
                                            </div>
                                        
                                        
                                        </div>


                                        @endforeach

                                
                                        <br>
                            
                            </div>

                            <br> 

                            @foreach($Shop_article as $value1)
                            <!-- row rose -->
                            <div class="row" style="background-color:pink; border: 2px solid grey;justify-content: center">

                                    
                                    <div class="row">
                                    
                                        <div class="col-sm-12">
                                                    <br>
                                            
                                                        
                                            
                                                        <label>Résumé </label>
                                                            <textarea type="text" name="short_description" class="form-control" > {{$value1->short_description}}</textarea>
                                                        <label>Description</label>
                                                            <textarea name="editor1"  id="ckeditor" class="form-control" required> {{$value1->description}}</textarea>
                                                            
                                                        
                                            
                                    
                                                
                                        </div>
                                    
                                    
                                    
                                    </div>
                                
                                    
                            </div>


                            </div>

                            @endforeach
                                    
                            </div>
                            <div class="row mt-4 d-flex justify-content-center">
                                <div class="col-md-4 d-flex justify-content-center">
                                    <input class="btn btn-success" name="modifier" type="submit" value="Valider">
                                </div>
                            </div>
                            </form>

</main>

       @elseif($type_article == 2)
       <main id="main" class="main">

       <div class="container">
                            @if(session()->has('success'))
                                            <div class="alert alert-success">
                                                {{ session()->get('success') }}
                                            </div>
                                        @endif

                                        <div class="row pt-5justify-content-end">
                                                <div class="col-md-2">
                                                    
                                                </div>
                                                <div class="col-md-12">
                                                    <a href="{{route('index_article')}}"><button style="float: right" class="btn btn-danger"> Retour</button></a>
                                                </div>
                            </div>
                            

                                    
                            <form  method="POST" action="{{route('edit_article',$Id)}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
                            @csrf
                            <div class="my-4 text-center">
                                <h3 class="bg-success text-white p-2">Paramètres Généraux</h3>  
                            </div>
                    
                            <div class="row">
                                @foreach($Shop_article as $value1)
                                    <div class="col-md-2 mb-3">
                                        <label for="saison">Saison</label>
                                        <select id="saison" class="form-control" name="saison">
                                            @foreach($saison_list as $data)
                                                <option value="{{$data->saison}}" {{ $value1->saison == $data->saison ? 'selected' : '' }}> {{$data->saison}} - {{$data->saison + 1 }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <label for="title">Titre</label>
                                            
                                            <input required id="title" class="form-control" name="title" for="title" type="text" value="{{$value1->title}}">
                                </div>
                        <div class="col-md-2 col-6">
                        <label for="image">Image</label>
                        
                        <input class="imageUpload form-control" id="image" required for="image" name="image" type="upload" placeholder="Image" value="{{$value1->image}}">
                        </div>
                        <div class="col-md-2 col-6">
                        <label for="ref">Référence</label>
                        
                                <input id="ref" class="form-control" for="ref" name="ref" type="text" placeholder="Référence" value="{{$value1->ref}}">
                        </div>
                       
                        <div class="col-md-2 col-6 mb-3">
                        <label for="img">Couleur</label>
                        <input class="jscolor {width:243, height:150, position:'right', borderColor:'#000', insetColor:'#FFF', backgroundColor:'#DDD'}">
                    </div>
                    
                    
                
                        <div class="col-md-2 col-6 ">
                    <label> Début de validité : </label>
                            <input required type="date" class="form-control" name="startvalidity" value="{{$value1->startvalidity}}">
                        </div>
                        <div class="col-md-2 col-6">
                                                     <label> Fin de validité :</label>
                                                         <input required type="date" class="form-control" name="endvalidity" value="{{$value1->endvalidity}}">
                                                     </div>
                
                                                     <div class="col-md-2 col-6">
                                                        <label>Statut :</label>
                                                        <select name="need_member" class="form-control" id="require">
                                                            <option value="0" {{ $value1->need_member == 0 ? 'selected' : '' }}>Non Membre</option>
                                                            @if(isset($articleLicence1))
                                                                <option value="{{ $articleLicence1->id_shop_article }}" {{ $value1->need_member == $articleLicence1->id_shop_article ? 'selected' : '' }}>
                                                                    {{ $articleLicence1->title }}
                                                                </option>
                                                            @endif
                                                            @if(isset($articleLicence2))
                                                                <option value="{{ $articleLicence2->id_shop_article }}" {{ $value1->need_member == $articleLicence2->id_shop_article ? 'selected' : '' }}>
                                                                    {{ $articleLicence2->title }}
                                                                </option>
                                                            @endif
                                                            @if(isset($articleLicence3))
                                                                <option value="{{ $articleLicence3->id_shop_article }}" {{ $value1->need_member == $articleLicence3->id_shop_article ? 'selected' : '' }}>
                                                                    {{ $articleLicence3->title }}
                                                                </option>
                                                            @endif
                                                            @if(isset($articleLicence4))
                                                                <option value="{{ $articleLicence4->id_shop_article }}" {{ $value1->need_member == $articleLicence4->id_shop_article ? 'selected' : '' }}>
                                                                    {{ $articleLicence4->title }}
                                                                </option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    
                
                                                     <div class="col-md-2 col-6">
                
                                                     
                                                             <label>Age Minimal</label><input type="number" class="form-control" name="agemin" step="0.01" value="{{$value1->agemin}}" required>
                
                                                     </div>
                
                                                     <div class="col-md-2 col-6">
                
                                                     <label>Age Maximal</label><input type="number" class="form-control" name="agemax" step="0.01" value="{{$value1->agemax}}" required>
                
                                                     </div>
                
                                                     <div class="col-md-2 col-6">
                
                                                         <label> Prix TTC :</label>
                                                         
                                                         <input step="0.01" class="form-control" name="price" for="TTC" type="number" value='{{$value1->price}}' required>
                
                                                     </div>
                
                                                 <div class="col-md-2 col-6">
                
                                                 <label> Prix indicatif :</label>
                                                         
                                                         <input step="0.01" class="form-control" name="price_indicative" for="TTC" type="number" value='{{$value1->price_indicative}}' required>
                
                                                 </div>
                
                                                 <div class="col-md-2 col-6">
                
                                                    <label> Quantité initale:</label>
                                                            
                                                            <input step="0.01" class="form-control" name="stock_ini" for="TTC" type="number" value='{{$value1->stock_ini}}' required>
                
                                                    </div>
                
                                                    <div class="col-md-2 col-6">
                
                                                    <label>  Quantité restante: </label>
                                                            <input step="0.01" class="form-control" name="stock_actuel" for="TTC" type="number" value='{{$value1->stock_actuel}}' required>
                
                                                    </div>
                
                                                    <div class="col-md-2 col-6">
                                                    <label>  Quantité alerte:</label> 
                                                            <input step="0.01" class="form-control" name="alert_stock" for="TTC" type="number" value='{{$value1->alert_stock}}' required>
                
                                                    </div>
                
                                                    <div class="col-md-2 col-6">
                                                    <label> type article  :</label>
                                                            <input step="1" class="form-control" name="type_article" for="" type="number" value='{{$value1->type_article }}' required readonly>
                
                                                    </div>
                
                                                    <div class="col-md-2 col-6">
                                                    <label>  Max utilisateur:</label>
                                                            <input  class="form-control" name="max_per_user" for="" type="number" value='{{$value1->max_per_user}}' required>
                
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
                                                                @php
                                                                    $json_cate = json_decode($value1->categories) ?? [];
                                                                @endphp
                                                                    
                                                                @foreach($requete_cate as $data)
                                                                    <tr>    
                                                                        <td>{{$data->name}}</td>     
                                                                        <td class="text-center">
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input mb-0" type="checkbox" id="category{{$data->id_shop_category}}" name="category[]" value="{{$data->id_shop_category}}" {{ in_array($data->id_shop_category,$json_cate) ? 'checked ': ""}}>
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
                                                                <input class="form-check-input" type="checkbox" id="strict" name="strict" value="1" {{ $value1->selected_limit == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="strict">&nbsp Mode strict</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4  mt-4">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="afiscale" name="afiscale" value="1" {{ $value1->afiscale == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="afiscale">&nbsp Attestation fiscale</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3  mt-4">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="nouveaute" name="nouveaute" value="1" {{ $value1->nouveaute == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="nouveaute">&nbsp Nouveauté</label>
                                                            </div>
                                                        </div>
                                                                <label class="mr-2">Limitation par sexe:</label>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" id="sexLimitMixte" name="sex_limit" value="" {{ $value1->sex_limit === NULL ? 'checked' : ''}}>
                                                                    <label class="form-check-label" for="sexLimitMixte">Mixte</label>
                                                                </div>
                                                                                                             
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" id="sexLimitFemme" name="sex_limit" value="female" {{ $value1->sex_limit === 'female' ? 'checked' : ''}}>
                                                                    <label class="form-check-label" for="sexLimitFemme">Femme</label>
                                                                </div>
                                                                                                             
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" id="sexLimitHomme" name="sex_limit" value="male" {{ $value1->sex_limit === 'male' ? 'checked' : ''}}>
                                                                    <label class="form-check-label" for="sexLimitHomme">Homme</label>
                                                                </div>
                                                                
                                                        
                                                        
                                                        
                                                    </div>
                                                    
                                                    
                                    
                                                    
                                                    
                                @endforeach
                            </div>
                    
                            
                            <br> 

                            @foreach($shop_article_2 as $data)

                            <style>
                               
                                .container {
                                    background-color: rgba(255, 255, 255, 0.8);
                                    border-radius: 15px;
                                    padding: 10px;
                                    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
                                }
                            
                                .card {
                                    border: none;
                                    box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.1);
                                }
                                .roww {
                                    background-color: #eee9e9 ;
                                        }
                            </style>
                            
                            <div class="row justify-content-center py-5" style="background-color: #333333;">
                                <div class="col-lg-8">
                                    <h3 class="mb-4 text-center text-white">Paramètres spécifiques</h3>
                                    <div class="bg-white p-4 shadow rounded">
                                        <div id="add-declinaison" class="mb-4">
                                            @csrf
                                            <input type="hidden" id="shop_article_id" value="{{ $data->id_shop_article }}">
                                            <div class="form-group">
                                                <label for="libelle" class="text-secondary">Libelle de la déclinaison :</label>
                                                <input type="text" class="form-control" id="libelle" name="libelle" placeholder="Entrez le libelle de la déclinaison" >
                                            </div>
                                            <div class="form-group">
                                                <label for="stock_ini_d" class="text-secondary">Stock Initial :</label>
                                                <input type="number" class="form-control" id="stock_ini_d" name="stock_ini_d" placeholder="Entrez le stock initial" >
                                            </div>
                                            <button type="button" id="add-declinaison-btn" class="btn" style="background-color: #482683; color: #fff;">Ajouter déclinaison</button>
                                        </div>
                                        <div class="declinaisons-list">
                                            @foreach($declinaisons as $declinaison)
                                            <div class="row mb-4">
                                                <div class="col-md-12">
                                                    <div class="card text-center bg-light shadow">
                                                        <div class="card-body">
                                                            <h5 class="card-title text-secondary">{{ $declinaison->libelle }}</h5>
                                                            <button type="button" class="btn mt-2 delete-declinaison-btn" style="background-color: #63c3d1; color: #fff;" data-id="{{ $declinaison->id }}">Supprimer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const addButton = document.getElementById('add-declinaison-btn');
                                const declinaisonsList = document.querySelector('.declinaisons-list');
                                var baseURL = "{{ route('delete.declinaison', '') }}";
                                addButton.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    addDeclinaison();
                                });
                            
                                declinaisonsList.addEventListener('click', function(e) {
                                    if (e.target.classList.contains('delete-declinaison-btn')) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        deleteDeclinaison(e);
                                    }
                                });
                            
                                function addDeclinaison() {
                                    const libelle = document.getElementById('libelle').value;
                                    const shopArticleId = document.getElementById('shop_article_id').value;
                                    const stockIniD = document.getElementById('stock_ini_d').value;
                                    const token = document.querySelector('[name="_token"]').value;
                            
                                    if(!libelle.trim()) {
                                        alert('Le libellé est requis.');
                                        return;
                                    }
                            
                                    if(!stockIniD.trim() || isNaN(stockIniD) || stockIniD < 0) {
                                        alert('Le stock initial est invalide.');
                                        return;
                                    }
                            
                                    const data = new FormData();
                                    data.append('libelle', libelle);
                                    data.append('shop_article_id', shopArticleId);
                                    data.append('stock_ini_d', stockIniD);
                            
                                    fetch('{{ route('add.declinaison') }}', {
                                        method: 'POST',
                                        body: data,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'X-CSRF-TOKEN': token
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Déclinaison ajoutée avec succès!');
                                            document.getElementById('libelle').value = '';
                                            const declinaisonElement = `
                                            
                                                <div class="row mb-4">
                                                    <div class="col-md-12">
                                                        <div class="card text-center bg-light shadow">
                                                            <div class="card-body">
                                                                <h5 class="card-title text-secondary">${data.libelle}</h5>
                                                                <button type="button" class="btn mt-2 delete-declinaison-btn" style="background-color: #63c3d1; color: #fff;" data-id="${data.Id}">Supprimer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                            declinaisonsList.insertAdjacentHTML('beforeend', declinaisonElement);
                                        }
                                    })
                                    .catch(error => {
                                        alert('Une erreur s\'est produite. Veuillez réessayer.');
                                    });
                                }
                                
                            
                                function deleteDeclinaison(e) {
                                    const declinaisonId = e.target.getAttribute('data-id');
                                    const token = document.querySelector('[name="_token"]').value;
                            
                                    if (confirm('Êtes-vous sûr de vouloir supprimer cette déclinaison ?')) {
                                        fetch(baseURL + '/' + declinaisonId, {
                                            method: 'DELETE',
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest',
                                                'X-CSRF-TOKEN': token
                                            }
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                e.target.closest('.row.mb-4').remove();
                                                alert('Déclinaison supprimée avec succès!');
                                            }
                                        })
                                        .catch(error => {
                                            alert('Une erreur s\'est produite. Veuillez réessayer.');
                                        });
                                    }
                                }
                            });
                            </script>
                            
                            
                            
                            
                            
                            
                            
                            
                           
                            
                                   

                                    
                            @endforeach
                            
                            <br> 

                            @foreach($Shop_article as $value1)
                            <!-- row rose -->
                            <div class="row mx-1" style="background-color:pink; border: 2px solid grey;">

                                    
                                    <div class="row">
                                    
                                        <div class="col-sm-12">
                                                    <br>
                                        
                                                        <label>Résumé </label>
                                                            <textarea type="text" name="short_description" class="form-control" > {{$value1->short_description}}</textarea>
                                                        <label>Description</label>
                                                            <textarea name="editor1"  id="ckeditor" class="form-control" required> {{$value1->description}}</textarea>
                                                            
                                        </div>
                                    
                                    
                                    
                                    </div>
                                
                                    
                            </div>


                            </div>

                            @endforeach
                                    
                            </div>
                            <div class="row mt-4 d-flex justify-content-center">
                                <div class="col-md-4 d-flex justify-content-center">
                                    <input class="btn btn-success" name="modifier" type="submit" value="Valider">
                                </div>
                            </div>
                            </form>


       @else


       <main id="main" class="main">
        <div class="row mb-5 d-flex justify-content-end">
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{route('index_article')}}">
                    <button class="btn btn-danger">Retour</button>
                </a>
            </div>
        </div>
    
        <form id="bigform" class="border  p-3" method="POST" action="{{route('edit_article', $Id)}}" enctype="multipart/form-data" novalidate>
            @csrf
    
            <div class="my-4 text-center">
                <h3 class="bg-success text-white p-2">Paramètres Généraux</h3>  
            </div>
    
            <div class="row">
                @foreach($Shop_article as $value1)
                    <div class="col-md-2 mb-3">
                        <label for="saison">Saison</label>
                        <select id="saison" class="form-control" name="saison">
                            @foreach($saison_list as $data)
                                <option value="{{$data->saison}}" {{ $value1->saison == $data->saison ? 'selected' : '' }}> {{$data->saison}} - {{$data->saison + 1 }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-6">
                        <label for="title">Titre</label>
                            
                            <input required id="title" class="form-control" name="title" for="title" type="text" value="{{$value1->title}}">
                </div>
        
        <div class="col-md-2 col-6">
        <label for="ref">Référence</label>
        
                <input id="ref" class="form-control" for="ref" name="ref" type="text" placeholder="Référence" value="{{$value1->ref}}">
        </div>
       
        <div class="col-md-2 col-6 mb-3">
        <label for="img">Couleur</label>
        <input class="jscolor {width:243, height:150, position:'right', borderColor:'#000', insetColor:'#FFF', backgroundColor:'#DDD'}">
    </div>
    
    

        <div class="col-md-2 col-6 ">
    <label> Début de validité : </label>
            <input required type="date" class="form-control" name="startvalidity" value="{{$value1->startvalidity}}">
        </div>
        <div class="col-md-2 col-6">
                                     <label> Fin de validité :</label>
                                         <input required type="date" class="form-control" name="endvalidity" value="{{$value1->endvalidity}}">
                                     </div>

                                     <div class="col-md-2 col-6">
                                        <label>Statut :</label>
                                        <select name="need_member" class="form-control" id="require">
                                            <option value="0" {{ $value1->need_member == 0 ? 'selected' : '' }}>Non Membre</option>
                                            @if(isset($articleLicence1))
                                                <option value="{{ $articleLicence1->id_shop_article }}" {{ $value1->need_member == $articleLicence1->id_shop_article ? 'selected' : '' }}>
                                                    {{ $articleLicence1->title }}
                                                </option>
                                            @endif
                                            @if(isset($articleLicence2))
                                                <option value="{{ $articleLicence2->id_shop_article }}" {{ $value1->need_member == $articleLicence2->id_shop_article ? 'selected' : '' }}>
                                                    {{ $articleLicence2->title }}
                                                </option>
                                            @endif
                                            @if(isset($articleLicence3))
                                                <option value="{{ $articleLicence3->id_shop_article }}" {{ $value1->need_member == $articleLicence3->id_shop_article ? 'selected' : '' }}>
                                                    {{ $articleLicence3->title }}
                                                </option>
                                            @endif
                                            @if(isset($articleLicence4))
                                                <option value="{{ $articleLicence4->id_shop_article }}" {{ $value1->need_member == $articleLicence4->id_shop_article ? 'selected' : '' }}>
                                                    {{ $articleLicence4->title }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                    

                                     <div class="col-md-2 col-6">

                                     
                                             <label>Age Minimal</label><input type="number" class="form-control" name="agemin" step="0.01" value="{{$value1->agemin}}" required>

                                     </div>

                                     <div class="col-md-2 col-6">

                                     <label>Age Maximal</label><input type="number" class="form-control" name="agemax" step="0.01" value="{{$value1->agemax}}" required>

                                     </div>

                                     <div class="col-md-2 col-6">

                                         <label> Prix TTC :</label>
                                         
                                         <input step="0.01" class="form-control" name="price" for="TTC" type="number" value='{{$value1->price}}' required>

                                     </div>

                                 <div class="col-md-2 col-6">

                                 <label> Prix indicatif :</label>
                                         
                                         <input step="0.01" class="form-control" name="price_indicative" for="TTC" type="number" value='{{$value1->price_indicative}}' required>

                                 </div>

                                 <div class="col-md-2 col-6">

                                    <label> Quantité initale:</label>
                                            
                                            <input step="0.01" class="form-control" name="stock_ini" for="TTC" type="number" value='{{$value1->stock_ini}}' required>

                                    </div>

                                    <div class="col-md-2 col-6">

                                    <label>  Quantité restante: </label>
                                            <input step="0.01" class="form-control" name="stock_actuel" for="TTC" type="number" value='{{$value1->stock_actuel}}' required>

                                    </div>

                                    <div class="col-md-2 col-6">
                                    <label>  Quantité alerte:</label> 
                                            <input step="0.01" class="form-control" name="alert_stock" for="TTC" type="number" value='{{$value1->alert_stock}}' required>

                                    </div>

                                    <div class="col-md-2 col-6">
                                    <label> type article  :</label>
                                            <input step="1" class="form-control" name="type_article" for="" type="number" value='{{$value1->type_article }}' required readonly>

                                    </div>

                                    <div class="col-md-2 col-6">
                                    <label>  Max utilisateur:</label>
                                            <input  class="form-control" name="max_per_user" for="" type="number" value='{{$value1->max_per_user}}' required>

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
                                                @php
                                                    $json_cate = json_decode($value1->categories) ?? [];
                                                @endphp
                                                    
                                                @foreach($requete_cate as $data)
                                                    <tr>    
                                                        <td>{{$data->name}}</td>     
                                                        <td class="text-center">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input mb-0" type="checkbox" id="category{{$data->id_shop_category}}" name="category[]" value="{{$data->id_shop_category}}" {{ in_array($data->id_shop_category,$json_cate) ? 'checked ': ""}}>
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
                                                <input class="form-check-input" type="checkbox" id="strict" name="strict" value="1" {{ $value1->selected_limit == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="strict">&nbsp Mode strict</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4  mt-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="afiscale" name="afiscale" value="1" {{ $value1->afiscale == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="afiscale">&nbsp Attestation fiscale</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3  mt-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="nouveaute" name="nouveaute" value="1" {{ $value1->nouveaute == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="nouveaute">&nbsp Nouveauté</label>
                                            </div>
                                        </div>
                                                <label class="mr-2">Limitation par sexe:</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="sexLimitMixte" name="sex_limit" value="" {{ $value1->sex_limit === NULL ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="sexLimitMixte">Mixte</label>
                                                </div>
                                                                                             
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="sexLimitFemme" name="sex_limit" value="female" {{ $value1->sex_limit === 'female' ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="sexLimitFemme">Femme</label>
                                                </div>
                                                                                             
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="sexLimitHomme" name="sex_limit" value="male" {{ $value1->sex_limit === 'male' ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="sexLimitHomme">Homme</label>
                                                </div>
                                                
                                                
                                                
                                        
                                        
                                        
                                    </div>
                                    
                                    
                    
                                    
                                    
                @endforeach
            </div>
    
            <br> 


            @foreach($Shop_article as $value1)
            <!-- row rose -->
            <div class="row pb-3" style="background-color:pink; border: 2px solid grey;justify-content: center">

                    
                    <div class="row">
                    
                        <div class="col-sm-12">
                                    <br>
                        
                                        <label>Résumé </label>
                                            <textarea type="text" name="short_description" class="form-control" > {{$value1->short_description}}</textarea>
                                        <label>Description</label>
                                            <textarea name="editor1"  id="ckeditor" class="form-control" required> {{$value1->description}}</textarea>
                                            
                        </div>
                    
                    
                    
                    </div>
                
                    
            </div>


            </div>

            @endforeach
        
    

        <br> 
        <style>
            .my-custom-container {
                background-color: #eee9e9;  
                
            }
        
            .my-custom-container h2 {
                text-align: center;
            }
        
            .add-session {
                text-align: right;
            }
        
            .table-row {
                background-color: #f8f9fa; 
            }
        
            .table-row:hover {
                background-color: #e9ecef; 
            }
        </style>
        
        <!-- row beige  -->
        <div class="my-custom-container">
            <div class="row p-4">
                <div class="col-md-12">
                    <h2>Information sur la séance</h2>
                </div>
        
                <div class="col-md-12 add-session mb-3">
                    <button class="add_field_button btn btn-dark">Ajouter des séances</button>
                </div>
        
                <div class="col-md-4">
                    <div style="height: 250px;  overflow: scroll; ">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="position: sticky" scope="col">Choix des professeurs :</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $json_teacher = '';           
                                    foreach($shop_article_1 as $val){
                                        $json_teacher = json_decode($val->teacher);  
                                    }
                                @endphp
                                @foreach($requete_prof as $data)
                                    <tr class="table-row">
                                        <td>{{$data->name}}  {{$data->lastname }}</td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="prof{{$data->user_id}}" name="prof[]" value="{{$data->user_id}}" {{ in_array($data->user_id, $json_teacher) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="prof{{$data->user_id}}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                
            </form>
            <div class="col-md-8 mt-2">
                @foreach($shop_article_1 as $data1)
                    @php
                        $norepeat = TRUE; // éviter de répéter les redondances d'informations à l'affichage
                        $Data_lesson = (array) json_decode($data1->lesson,true);
                    @endphp
                    @foreach($Data_lesson['start_date'] as $key => $dt)
                    @php
                        $date = new DateTime($dt);
                        $end_date = new DateTime($Data_lesson['end_date'][$key]);
                    @endphp
                        <div class="row">
                            <div class="col-10">
                                Séance {{ $loop->iteration }}:<br>
                                Cette séance est dispensée le {{ fetcchDayy($dt) }} {{ $date->format('d') }} {{ fetchMonthh($dt) }}.<br>
                                De {{ $date->format('G:i') }} à {{ $end_date->format('G:i') }}.<br>
                                @foreach($rooms as $room)
                                    @if($room->id_room == $Data_lesson['room'][$key])
                                        <span class="text-primary">{{ $room->name }}</span><br>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-2 d-flex align-items-center">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal{{ $loop->parent->iteration }}-{{ $loop->iteration }}">
                                    Éditer
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="modal fade" id="editModal{{ $loop->parent->iteration }}-{{ $loop->iteration }}" tabindex="-1" role="dialog" aria-labelledby="editModal{{ $loop->parent->iteration }}Label-{{ $loop->iteration }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModal{{ $loop->parent->iteration }}Label-{{ $loop->iteration }}">Éditer la séance {{ $loop->iteration }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="form-group">
                                                <label for="editStartDate{{ $loop->parent->iteration }}-{{ $loop->iteration }}">Date de début :</label>
                                                <input type="date" class="form-control" id="editStartDate{{ $loop->parent->iteration }}-{{ $loop->iteration }}" value="{{ $date->format('Y-m-d') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="editEndDate{{ $loop->parent->iteration }}-{{ $loop->iteration }}">Date de fin :</label>
                                                <input type="date" class="form-control" id="editEndDate{{ $loop->parent->iteration }}-{{ $loop->iteration }}" value="{{ $end_date->format('Y-m-d') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="editStartTime{{ $loop->parent->iteration }}-{{ $loop->iteration }}">Heure de début :</label>
                                                <input type="time" class="form-control" id="editStartTime{{ $loop->parent->iteration }}-{{ $loop->iteration }}" value="{{ $date->format('H:i') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="editEndTime{{ $loop->parent->iteration }}-{{ $loop->iteration }}">Heure de fin :</label>
                                                <input type="time" class="form-control" id="editEndTime{{ $loop->parent->iteration }}-{{ $loop->iteration }}" value="{{ $end_date->format('H:i') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="editRoom{{ $loop->parent->iteration }}-{{ $loop->iteration }}">Chambre :</label>
                                                <select class="form-control" id="editRoom{{ $loop->parent->iteration }}-{{ $loop->iteration }}">
                                                    @foreach($rooms as $room)
                                                        <option value="{{ $room->id_room }}" {{ in_array($room->id_room, $Data_lesson['room']) ? 'selected' : '' }}>{{ $room->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                        <button type="button" class="btn btn-primary" onclick="updateLesson({{ $loop->parent->iteration }}, {{ $loop->iteration }})">Sauvegarder</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    @endforeach
                @endforeach
            </div>
            
                
                <div class="col-md-12">
                    <div class="input_fields_wrap"></div>
                </div>
                
            </div>
        </div>
    <!-- fin -->
    <script>
        function updateLesson(parentIndex, index) {
            var startDate = $('#editStartDate' + parentIndex + '-' + index).val();
            var endDate = $('#editEndDate' + parentIndex + '-' + index).val();
            var startTime = $('#editStartTime' + parentIndex + '-' + index).val();
            var endTime = $('#editEndTime' + parentIndex + '-' + index).val();
            var room = $('#editRoom' + parentIndex + '-' + index).val();
            var url = '{{ route("lesson.update") }}';

    
            // Requête AJAX pour mettre à jour les informations de la séance
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    shop_article_id: '{{ $data1->id_shop_article }}',
                    lesson_index: index - 1,
                    start_date: startDate,
                    end_date: endDate,
                    start_time: startTime,
                    end_time: endTime,
                    room: room
                },
                success: function(response) {
                    alert('Séance mise à jour avec succès !');
                },
                error: function(xhr) {
                    // Gérer les erreurs lors de la mise à jour
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
    
    
               
                         
                 </div>
                 <div class="row mt-4 d-flex justify-content-center">
                    <div class="col-md-4 d-flex justify-content-center">
                        <input form="bigform" class="btn btn-success" name="modifier" type="submit" value="Valider">
                    </div>
                </div>
                


  



       @endif


       <script src="//cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('editor1', {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserBrowseUrl: "/elfinder/ckeditor",
        filebrowserUploadMethod: 'form',
        language: 'fr',
        on: {
		loaded: function() {
			ajaxRequest({method: "POST", url: action, redirectTo: redirectPage, form: form});
		}
	},

        toolbar: [{ name: 'document', items : [ 'Source','NewPage','Preview' ] },
            { name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat','strikethrough', 'underline', 'subscript', 'superscript', '|' ] },
            { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
            { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
            '/',
            { name: 'heading', items : ['heading', '|' ] },
            { name: 'alignment', items : ['alignment', '|' ] },
            { name: 'font', items : [ 'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor', '|'] },
                      
            { name: 'styles', items : [ 'Styles','Format' ] },
            { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','todoList',] },
            { name: 'insert', items :[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
            { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
            { name: 'tools', items : [ 'Maximize','-','About' ] }

],

  
				uiColor: '#FFDC6E'
    });



</script>

<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
    $(document).ready(function() {
        var max_fields = 10;
        var wrapper = $(".input_fields_wrap");
        var add_button = $(".add_field_button");
        var x = 1;
        $(add_button).click(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'GET',
                dataType: 'html',
                url: "{{ route('test_create_article') }}",
                success: function(msg) {
                    if (x < max_fields) {
                        x++;
                        $(wrapper).append('<br><br><div class="small-12" id="mysession">Début <input type="datetime-local" name="startdate[]"/>Fin <input class="mx-2" type="datetime-local" name="enddate[]"/>Salle'  + msg + '<a href="#" class="mx-3 remove_field">Supprimer</a></div>')
                    }
                }
            });
        });
        $(wrapper).on("click", ".remove_field", function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })
    });
    
</script>
</body>

</html>

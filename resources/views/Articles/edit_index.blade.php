@extends('layouts.template')


@section('content')

<style>
    input {
        margin: 0 !important;
        margin-bottom: 1rem !important;
    }
      /* Style for custom checkbox */
      .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
        background-color: #007bff;
    }

    .custom-checkbox .custom-control-input:checked~.custom-control-label::after {
        display: block;
        content: "";
        position: absolute;
        top: 50%;
        left: 20%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border: 4px solid #fff;
        border-top-width: 0;
        border-left-width: 0;
        border-radius: 2px;
        animation: checkmark .3s ease-in-out;
    }

    /* Animation for checkmark */
    @keyframes checkmark {
        0% {
            width: 0;
            height: 0;
            transform: translateX(-50%) translateY(-50%) rotate(45deg) scale(0);
        }
        50% {
            width: 8px;
            height: 4px;
            transform: translateX(-50%) translateY(-50%) rotate(45deg) scale(1);
        }
        100% {
            width: 8px;
            height: 16px;
            transform: translateX(-50%) translateY(-50%) rotate(45deg) scale(1);
        }
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.4.5/jscolor.min.js"></script>

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

                                        <div class="row pt-5">
                                                <div class="col-md-2">
                                                    
                                                </div>
                                                <div class="col-md-12">
                                                    <a href="{{route('index_article')}}"><button class="btn btn-warning"> retour</button></a>
                                                </div>
                            </div>

                                    
                            <form  method="POST" action="{{route('edit_article',$Id)}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
                            @csrf
                                            <div class="row"> 
                                                <div class="col-md-4">
                                                
                                                <input class="btn btn-warning" name="modifier" type="submit" value="Valider">
                                                </div>
                                            
                                            
                                            </div>
                                            <br>

                                @foreach($Shop_article as $value1)         
                                            <!-- row vert  -->
                                <div class="row" style="background-color: #c6ffc1; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">
                                            <h3>Paramètres Généraux</h3>  
                                                    <div class="col-md-2 col-6">
                                                        <label for="saison">Saison</label>
                                                            <select id="saison" class="form-control" name="saison">
                                                                @foreach($saison_list as $data)
                                                               
                                                                <option value="{{$data->saison}}" {{ $value1->saison == $data->saison ? 'selected' : '' }}> {{$data->saison}} - {{$data->saison + 1 }}</option>
                                                                
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-6">
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
                                                <div class="col-md-2 col-6">
                                                <label for="img">Couleur</label>
                                                    <input id="img" type="color" class="form-control" class="color" name="color" value="">
                                                </div>
                                                <div class="col-md-2 col-6">
                                                <label for="attestation">Nouveauté</label>
                                            <input type="checkbox"  for="id_shop_article" value='1' name="nouveaute"  value="{{$value1->nouveaute}}" {{ $value1->nouveaute == 1 ? 'checked' : 0 }}>
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

                                                    <label>  Statut :   </label> 
                                                        
                                                            <select value="0" name="need_member" class="form-control" id="require">
                                                                <option value="0" {{ $value1->need_member == 0 ? 'selected' : '' }}>Non membre</option>
                                                                <option value="1" {{ $value1->need_member == 1 ? 'selected' : '' }}>membre loisir</option>
                                                                <option value="3" {{ $value1->need_member == 3 ? 'selected' : '' }}>membre compétition</option>
                                                            
                                                    </label>       
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
                                                    <input step="1" class="form-control" name="type_article" for="" type="number" value='0' required readonly>

                                            </div>

                                            <div class="col-md-2 col-6">
                                            <label>  Max utilisateur:</label>
                                                    <input  class="form-control" name="max_per_user" for="" type="number" value='{{$value1->max_per_user}}' required>

                                            </div>
                                        
                                            <div class="row pt-3"> 
                                            <div class="col-md-4 col-6">
                                            <label>  Mode strict:</label>

                                            <table class="table">
                                            

                                                    <tr>
                                                
                                                    <td><input style="vertical-align:center;" for="" type="checkbox" name="strict" value="{{$value1->selected_limit}}" {{ $value1->selected_limit == 1 ? 'checked' : 0 }} ></td>

                                                    </tr>
                                                
                                        </table>


                                            </div>
                                            <div class="col-md-4 col-6">

                                            <label>  Attestation fiscale :</label>

                                                    <table class="table">

                                                            <tr>
                                                           
                                                            <td><input type="checkbox"  value=1 name="afiscale" value="{{$value1->afiscale}}" {{ $value1->afiscale == 1 ? 'checked' : 0 }} ></td>

                                                            </tr>
                                                        
                                                    </table>

                                        
                                        

                                            </div>
                                            <div class="col-md-4 col-6 pb-5">
                                            
                                 <fieldset>
                                <label> Limitation par sexe: </label> 
                                <div>
                                <input type="radio" id="" name="sex_limit"  value=0 {{ $value1->sex_limit == 0 ? 'checked' : ''}}>
                                <label for="">Mixte</label>
                                </div>

                                <div>
                                <input type="radio" id="dewey" name="sex_limit"  value=1 {{ $value1->sex_limit == 1 ? 'checked' : ''}}  >
                                <label for="">Femme</label>
                                </div>

                                <div>
                                <input type="radio" id="" name="sex_limit"  value=2 {{ $value1->sex_limit == 2 ? 'checked' : ''}}>
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
                            @php
                                $json_cate = json_decode($value1->categories) ;  
                                if ($json_cate){

                                }else{
                                    $json_cate = [];  
                                }
                                
                            @endphp

                            
                                
                                @foreach($requete_cate as $data)
                                
                                <tr>
                            
                                <td>{{$data->name}}</td>
                                
                                <td><input style="vertical-align:center;" for="catenvoi" type="checkbox" name="category[]"  value="{{$data->id_shop_category}}" {{ in_array($data->id_shop_category,$json_cate) ? 'checked ': " "}}></td>
                                
                            

                                </tr>

                                @endforeach
                            
                            </tbody>
                            </table>
                            </div>
                                </div>

                                            </div>


                            </div>

                            @endforeach
                            <br> 

                            @foreach($shop_article_0 as $data)

                            <!-- row beige  -->
                            <div class="row" style="background-color: beige;border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

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
                            <div class="row" style="background-color:pink; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

                                    
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

                                        <div class="row pt-5">
                                                <div class="col-md-2">
                                                    
                                                </div>
                                                <div class="col-md-12">
                                                    <a href="{{route('index_article')}}"><button class="btn btn-warning"> retour</button></a>
                                                </div>
                            </div>

                                    
                            <form  method="POST" action="{{route('edit_article',$Id)}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
                            @csrf
                                            <div class="row"> 
                                                <div class="col-md-4">
                                                
                                                <input class="btn btn-warning" name="modifier" type="submit" value="Valider">
                                                </div>
                                            
                                            
                                            </div>
                                            <br>

                                @foreach($Shop_article as $value1)         
                                            <!-- row vert  -->
                                <div class="row" style="background-color: #c6ffc1; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">
                                            <h3>Paramètres Généraux</h3>  
                                                    <div class="col-md-2 col-6">
                                                        <label for="saison">Saison</label>
                                                        <select id="saison" class="form-control" name="saison">
                                                                @foreach($saison_list as $data)
                                                               
                                                                <option value="{{$data->saison}}" {{ $value1->saison == $data->saison ? 'selected' : '' }}> {{$data->saison}} - {{$data->saison + 1 }}</option>
                                                                
                                                                @endforeach
                                                        </select>
                                                        </div>
                                                        <div class="col-md-2 col-6">
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
                                                <div class="col-md-2 col-6">
                                                <label for="img">Couleur</label>
                                                    <input id="img" type="color" class="form-control" class="color" name="color" value="">
                                                </div>
                                                <div class="col-md-2 col-6">
                                                <label for="attestation">Nouveauté</label>
                                            <input type="checkbox"  for="id_shop_article" value='1' name="nouveaute"  value="{{$value1->nouveaute}}" {{ $value1->nouveaute == 1 ? 'checked' : 0 }}>
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

                                                    <label>  Statut :   </label> 
                                                        
                                                            <select value="0" name="need_member" class="form-control" id="require">
                                                                <option value="0" {{ $value1->need_member == 0 ? 'selected' : '' }}>Non membre</option>
                                                                <option value="1" {{ $value1->need_member == 1 ? 'selected' : '' }}>membre loisir</option>
                                                                <option value="3" {{ $value1->need_member == 3 ? 'selected' : '' }}>membre compétition</option>
                                                            
                                                    </label>       
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
                                        
                                            <div class="row pt-3"> 
                                            <div class="col-md-4 col-6">
                                            <label>  Mode strict:</label>

                                            <table class="table">
                                            

                                                    <tr>
                                                
                                                    <td><input style="vertical-align:center;" for="" type="checkbox" name="strict"  value=1 value="{{$value1->selected_limit}}" {{ $value1->selected_limit == 1 ? 'checked' : 0 }} ></td>

                                                    </tr>
                                                
                                        </table>


                                            </div>
                                            <div class="col-md-4 col-6">

                                            <label>  Attestation fiscale :</label>
                                            <table class="table">

                                            <tr>

                                            <td><input type="checkbox"  value=1 name="afiscale" value="{{$value1->afiscale}}" {{ $value1->afiscale == 1 ? 'checked' : 0 }} ></td>

                                            </tr>

                                            </table>
                                                                                    

                                            </div>
                                            <div class="col-md-4 col-6 pb-5">
                                            
                            <fieldset>
                                <label> Limitation par sexe: </label> 
                                <div>
                                <input type="radio" id="" name="sex_limit"  value=0 {{ $value1->sex_limit == 0 ? 'checked' : ''}}>
                                <label for="">Mixte</label>
                                </div>

                                <div>
                                <input type="radio" id="dewey" name="sex_limit"  value=1 {{ $value1->sex_limit == 1 ? 'checked' : ''}}  >
                                <label for="">Femme</label>
                                </div>

                                <div>
                                <input type="radio" id="" name="sex_limit"  value=2 {{ $value1->sex_limit == 2 ? 'checked' : ''}}>
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
                            @php
                                $json_cate = json_decode($value1->categories) ;  
                                if ($json_cate){

                                }else{
                                    $json_cate = [];  
                                }
                                
                            @endphp

                            
                                
                                @foreach($requete_cate as $data)
                                
                                <tr>
                            
                                <td>{{$data->name}}</td>
                                
                                <td><input style="vertical-align:center;" for="catenvoi" type="checkbox" name="category[]"  value="{{$data->id_shop_category}}" {{ in_array($data->id_shop_category,$json_cate) ? 'checked ': " "}}></td>
                                
                            

                                </tr>

                                @endforeach
                            
                            </tbody>
                            </table>
                            </div>
                                </div>



                                            </div>


                            </div>

                            @endforeach
                            <br> 

                            @foreach($shop_article_2 as $data)

                            <!-- row beige  -->
                            <div class="row" style="background-color: beige;border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">
                            <i style="color: red;">Si vous voulez modifier réécrivez les anciennes données à converser et ajouter les nouvelles à la suite ; dans le cas où vous voulez remplacer complètement les données remplissez le formulaire avec les nouvelles données (les anciennes données seront perdues) </i>
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

                                    <div class="form-group">
                                  
                                    @php
                                    $tableau = [] ;
                                    $Data_json = (array) json_decode($data->declinaison,true);
                                   
                                    
                                        foreach($Data_json as $tab1){
                                           
                                            foreach($tab1 as $tab2){
                                                echo'<div class="row d-flex justify-content-center">';
                                                foreach($tab2 as $tab){

                                                @endphp
                                              
                                                    <div class="col-md-3">
                                                    <div class="card" style="width: 10rem;">
                                                    <div class="card-body">
                                                             <h5 class="card-title">{{$tab}}</h5>
                                                    </div>
                                                    </div>
                                                    </div>


                                                @php
                                    

                                            }


                                        }


                                     }
                                    
                                    @endphp

                                    </div>
                                   
                            
                            </div>
                            @endforeach
                            </div>
                            <br> 

                            @foreach($Shop_article as $value1)
                            <!-- row rose -->
                            <div class="row" style="background-color:pink; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

                                    
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
        
            <form class="border border-dark p-3" method="POST" action="{{route('edit_article', $Id)}}" enctype="multipart/form-data" novalidate>
                @csrf
        
                <div class="mb-4 text-center">
                    <h3 class="bg-success text-white p-2">Paramètres Généraux</h3>  
                </div>
        
                <div class="row">
                    @foreach($Shop_article as $value1)
                        <div class="col-md-4 mb-3">
                            <label for="saison">Saison</label>
                            <select id="saison" class="form-control" name="saison">
                                @foreach($saison_list as $data)
                                    <option value="{{$data->saison}}" {{ $value1->saison == $data->saison ? 'selected' : '' }}> {{$data->saison}} - {{$data->saison + 1 }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
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
            <div class="col-md-2 col-6 mt-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="nouveaute" name="nouveaute" value="1" {{ $value1->nouveaute == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="nouveaute">&nbsp Nouveauté</label>
                  </div>
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

                                             <label>  Statut :   </label> 
                                                 
                                                     <select value="0" name="need_member" class="form-control" id="require">
                                                         <option value="0" {{ $value1->need_member == 0 ? 'selected' : '' }}>Non membre</option>
                                                         <option value="1" {{ $value1->need_member == 1 ? 'selected' : '' }}>membre loisir</option>
                                                         <option value="3" {{ $value1->need_member == 3 ? 'selected' : '' }}>membre compétition</option>
                                                     
                                             </label>       
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
                                        <div class="col-md-2 col-6 pb-5">
                                            <fieldset>
                                                <label> Limitation par sexe: </label> 
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="sexLimitMixte" name="sex_limit" value=0 {{ $value1->sex_limit == 0 ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="sexLimitMixte">Mixte</label>
                                                </div>
                                        
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="sexLimitFemme" name="sex_limit" value=1 {{ $value1->sex_limit == 1 ? 'checked' : ''}} >
                                                    <label class="form-check-label" for="sexLimitFemme">Femme</label>
                                                </div>
                                        
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="sexLimitHomme" name="sex_limit" value=2 {{ $value1->sex_limit == 2 ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="sexLimitHomme">Homme</label>
                                                </div>
                                            </fieldset>                                         
                                        </div>
                                        
                                        <div class="col-md-2 col-6 mt-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="strict" name="strict" value="1" {{ $value1->selected_limit == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="strict">&nbsp Mode strict</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6 mt-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="afiscale" name="afiscale" value="1" {{ $value1->afiscale == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="afiscale">&nbsp Attestation fiscale</label>
                                            </div>
                                        </div>
                                        
                                        
                                        
                        
                                        <div class="col-md-12">
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
                                                                    <input class="form-check-input" type="checkbox" id="category{{$data->id_shop_category}}" name="category[]" value="{{$data->id_shop_category}}" {{ in_array($data->id_shop_category,$json_cate) ? 'checked ': ""}}>
                                                                    <label class="form-check-label" for="category{{$data->id_shop_category}}"></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                        
                                                    </tbody>
                                                </table>
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
        

            <br> 
        
            <!-- row beige  -->
            <div class="row p-3" style="background-color: beige;border: 2px solid grey;justify-content: center">
                <div class="col-md-12">
                    <i style="color: red;">Si vous voulez modifier réécrivez les anciennes données à conserver et ajoutez les nouvelles à la suite ; dans le cas où vous voulez remplacer complètement les données, remplissez le formulaire avec les nouvelles données (les anciennes données seront perdues)</i>
                </div>
        
                <div class="col-md-12">
                    <div style="height: 250px;  overflow: scroll; ">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Choix des professeurs :</th>
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
                                    <tr>
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
        
                <div class="col-md-12 mt-2">
                    <b>Lieu: </b>
                    <br>
                    @foreach($shop_article_1 as $data1)
                        @php
                            $norepeat = TRUE; // éviter de répéter les redondances d'informations à l'affichage
                            $Data_lesson = (array) json_decode($data1->lesson,true);
        
                            foreach($rooms as $room){
                                foreach($Data_lesson['room'] as $r){
                                    if($r == $room->id_room && $norepeat == TRUE){
                                        echo "<p><a class='a' href='https://www.google.com/maps?q=" . urlencode($room->name . " " . $room->address) . "' target='_blank'>" . $room->name . " - " . $room->address . "</a></p>";
                                    }
                                }
                            }
                        @endphp
                    @endforeach
        
                    <b>Horaire Respectif: </b>
                    <br>
                    @foreach($shop_article_1 as $data1)
                        @php
                            $norepeat = TRUE; // éviter de répéter les redondances d'informations à l'affichage
                            foreach($Data_lesson['start_date'] as $dt){
                                $date = new DateTime($dt);
                                echo "Cette séance est dispensée le ".fetchDayy($dt)." ".$date->format('d')." ";
                                foreach($Data_lesson['end_date'] as $end_dt){
                                    $end_date = new DateTime($end_dt);
                                    echo "De ".$date->format('G:i')." à ".$end_date->format('G:i');
                                    break;
                                }
                                echo "<br>";
                            }
                        @endphp
                    @endforeach
                </div>
        
                <div class="row-md-2 col-12">
                    <div class="col-lg-12">
                        <div class="input_fields_wrap">
                        </div>
                        <br><button class="add_field_button btn btn-info">Ajouter des séances</button>
                    </div>
                </div>
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
                             
                     </div>

                     </form>


      





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
                        $(wrapper).append('<br><br><div class="small-12" id="mysession">Début <input type="datetime-local" name="startdate[]"/>Fin <input type="datetime-local" name="enddate[]"/>Salle'  + msg + '<a href="#" class="remove_field">Supprimer</a></div>')
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

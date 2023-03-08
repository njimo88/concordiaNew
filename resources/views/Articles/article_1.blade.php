@extends('layouts.template')

@section('content')
@php
function fetchDayy($date)
                 {

    $lejour = ( new DateTime($date) )->format('l');

     $jour_semaine = array(

                     "lundi" => "Monday",
                    "Mardi" => "Tuesday",
                    "Mercredi" => "Wednesday",
                    "Jeudi" => "Thursday",
                    "Vendredi" => "Friday",
                    "Samedi" => "Saturday",
                    "Dimanche" => "Sunday"

                                         );
                                                                                                        

                               foreach($jour_semaine as $key=>$j){

                                 if ($j == $lejour){
                                 return $key ;
                                                }
                                   }

                                }

@endphp
 




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

<form  method="POST" action="{{route('duplicate_article',['id'=>$id_article]) }}" enctype="multipart/form-data" formnovalidate="formnovalidate">
@csrf
                <div class="row"> 
                    <div class="col-md-11">
                    
                    <input class="btn btn-warning" name="modifier" type="submit" value="Valider">
                    </div>
                   
                   
                </div>
                <br>
                <!-- row vert  -->
     @foreach($Shop_article as $value1)         
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
                                        
                                        <input required id="title" class="form-control" name="title" for="title" type="text" value="new-{{$value1->title}}">
                            </div>
                    <div class="col-md-2 col-6">
                    <label for="image">Image</label>
                    
                    <input class="imageUpload form-control" id="image" required for="image" name="image" type="upload" placeholder="Image" value="{{$value1->image}}">
                    </div>
                    <div class="col-md-2 col-6">
                    <label for="ref">Référence</label>
                    
                            <input id="ref" class="form-control" for="ref" name="ref" type="text" placeholder="Référence" value="new-{{$value1->ref}}">
                    </div>
                    <div class="col-md-2 col-6">
                    <label for="img">Couleur</label>
                        <input id="img" type="color" class="form-control" class="color" name="color" value="">
                    </div>
                    <div class="col-md-2 col-6">
                    <label for="attestation">Nouveauté</label>
                <input type="checkbox"  for="id_shop_article" value='1' name="nouveaute" value="{{$value1->nouveaute}}" {{ $value1->nouveaute == 1 ? 'checked' : 0 }}>
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

                    <label>Age Maximal</label><input type="number" class="form-control" name="agemax" step="0.01"  value="{{$value1->agemax}}" required>

                    </div>

                    <div class="col-md-2 col-6">

                         <label> Prix TTC :</label>
                        
                        <input step="0.01" class="form-control" name="price" for="TTC" type="number" value="{{$value1->price}}" required>

                    </div>

                <div class="col-md-2 col-6">

                <label> Prix indicatif :</label>
                        
                        <input step="0.01" class="form-control" name="price_indicative" for="TTC" type="number" value="{{$value1->price_indicative}}" required>

                </div>

                <div class="col-md-2 col-6">

                <label> Quantité initale:</label>
                        
                        <input step="0.01" class="form-control" name="stock_ini" for="TTC" type="number" value="{{$value1->stock_ini}}" required>

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
                        <input step="1" class="form-control" name="type_article" for="" type="number" value='1' required readonly>

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
                                <input type="hidden" name="afiscale" value="0" />
                                <td><input type="checkbox"  for=""  name="afiscale" value="{{$value1->afiscale}}" {{ $value1->afiscale == 1 ? 'checked' : 0 }}></td>

                                </tr>
                            
                        </table>

               
               

                </div>
                <div class="col-md-4 col-6 pb-5">
                 
                        <fieldset>
     <label> Limitation par sexe: </label> 
    <div>
      <input type="radio" id="" name="sex_limit"  value="{{$value1->sex_limit}}" {{ $value1->sex_limit == 0 ? 'checked' : ''}}>
      <label for="">Mixte</label>
    </div>

    <div>
      <input type="radio" id="dewey" name="sex_limit"  value="{{$value1->sex_limit}}" {{ $value1->sex_limit == 1 ? 'checked' : ''}}>
      <label for="">Femme</label>
    </div>

    <div>
      <input type="radio" id="" name="sex_limit"  value="{{$value1->sex_limit}}" {{ $value1->sex_limit == 2 ? 'checked' : ''}}>
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



                    <!-- row beige  -->
                    <div class="row" style="background-color: beige;border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">
                    <i style="color: red;">Si vous voulez modifier réécrivez les anciennes données à converser et ajouter les nouvelles à la suite ; dans le cas où vous voulez remplacer complètement les données remplissez le formulaire avec les nouvelles données (les anciennes données seront perdues) </i>
                    <h3>Paramètres spécifiques</h3>

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
                        $json_teacher = '' ;           
                        foreach($shop_article_1 as $val){
                            $json_teacher = json_decode($val->teacher) ;  
                        }
                       
                         
                     @endphp
                    @foreach($requete_prof as $data)

                    <tr>
                    
                        <td>{{$data->name}}  {{$data->lastname }}</td>
                  
                        <td><input style="vertical-align:center;" for="" type="checkbox" name="prof[]"  value="{{$data->user_id}}" {{ in_array($data->user_id, $json_teacher) ? 'checked ': " "}}></td>
                         

                    </tr>

                    @endforeach
                    
                    </tbody>
                    </table>
                    </div>
                    </div>

                    <div class="col-md-12">

                  


                    
                   
                 <b>Lieu: </b>
                 <br>
                    @foreach($shop_article_1 as $data1)
                            @php
                            $norepeat = TRUE ; // eviter de repeter les redondances d'informations a l'affichage
                            $Data_lesson = (array) json_decode($data1->lesson,true);

                                 foreach($rooms as $room){

                                  
                                    foreach($Data_lesson['room'] as $r){

                                        if($r == $room->id_room and $norepeat == TRUE){
                                               
                                                echo "  <a class='a' href='https://www.google.com/maps?q=" . urlencode($room->name . " " . $room->address) . "' target='_blank'>" . $room->name . " - " . $room->address . "</a>";
                                                echo"</p>";

                                            }


                                        };

                                    }

                     @endphp
                    @endforeach

                    <b>Horaire Respectif: </b>
                    <br>
                    @foreach($shop_article_1 as $data1)
                            @php

                            
                            $norepeat = TRUE ; // eviter de repeter les redondances d'informations a l'affichage
                                                        foreach($Data_lesson['start_date'] as $dt){
                                                        $date = new DateTime($dt);
                                                       

                                                        echo "Cette séance est dispensée le ".fetchDayy($dt)." ".$date->format('G:i');
                                                        foreach($Data_lesson['end_date'] as $dt){

                                                                                                                        
                                                                $date = new DateTime($dt);


                                                                echo " à ".$date->format('G:i');

                                                               break;

                                                                };

                                                        echo"<br>" ;
                                                   
                                                   
                                                    };

                                                
                           
                     @endphp
                    @endforeach

                            @foreach($shop_article_1 as $data1)  

                    @php
                        
                    
                         $Data_lesson = $data1->lesson;
                    
                    @endphp


                           @endforeach
                        

                    <input name="json" value= "{{ $Data_lesson }}" hidden>
                    


                     <div class="row">
                     
                        <div class="col-lg-4">
                                   
                                     
                        </div>
                    </div>
                  
                     </div>

                        <div class="row-md-2 col-12">
                        <div class="col-lg-12">
                                    <div class="input_fields_wrap">
                                    </div>
                                    <br><button class="add_field_button btn btn-info">Ajouter des séances</button>
                          </div>
                        
                        </div>
                    </div>

                    </div>

                    <br> 


@foreach($Shop_article as $data)
<!-- row rose -->
  <div class="row" style="background-color:pink; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

          
          <div class="row">
          
              <div class="col-sm-12">
                        <br>
                
                            
                
                              <label>Résumé </label>
                                <textarea type="text" name="short_description" class="form-control">{{$data->short_description}}</textarea>
                              <label>Description</label>
                                <textarea name="editor1"  id="ckeditor" class="form-control" required> {{$data->description}}</textarea>
                                
                             
                
        
                    
              </div>
          
          
          
          </div>
    
          
  </div>
  @endforeach


</div>
           
</div>

</form>

<script src="//cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script>


</main>
@endsection
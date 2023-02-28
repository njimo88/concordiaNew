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

          
<form  method="POST" action="{{route('create_article_member')}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
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
                                    <option value="{{$value1->saison}}">{{$data->saison}} - {{$data->saison + 1 }}</option>
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

  <h3>Paramètres spécifiques</h3>
  <br>

          <div class="row pb-3 pt-3"> 

              <div class="col-md-2 col-6">
                    <label>  XS:</label>
                            <input  class=" form-control" name="XS" for="" type="number" value='XS' placeholder="XS" readonly>
              </div>
              <div class="col-md-5 col-6 ">
                    <label style="margin-left:30%"> Stock initial:</label>
                            <input style="width: 50%; margin-left:30%"  class=" form-control" name="stock_ini_xs" for="" type="number"  required>
              </div>
              <div class="col-md-5 col-6">
                    <label>  Stock actuel:</label>
                            <input  style="width: 50%;" class=" form-control" name="stock_actu_xs" for="" type="number"  required>
              </div>

              <div class="col-md-2 col-6 ">
                    <label> S:</label>
                            <input  class="form-control" name="S" for="" type="number" value='S' placeholder="S" readonly>
              </div>
              <div class="col-md-5 col-6">
              <label style="margin-left:30%">  Stock initial:</label>
                            <input  style="width: 50%; margin-left:30%" class="form-control" name="stock_ini_s" for="" type="number"  required>
              </div>
              <div class="col-md-5 col-6">
              <label >  Stock actuel:</label>
                            <input  style="width: 50%;" class="form-control" name="stock_actu_s" for="" type="number"  required>
              </div>


              <div class="col-md-2 col-6">
                    <label> M:</label>
                            <input  class="form-control" name="M" for="" type="number" value='M' placeholder="M" readonly>
              </div>
              <div class="col-md-5 col-6">
              <label style="margin-left:30%">  Stock initial:</label>
                            <input style="width: 50%; margin-left:30%" class="form-control" name="stock_ini_m" for="" type="number"  required>
              </div>
              <div class="col-md-5 col-6">
              <label>  Stock actuel:</label>
                            <input  style="width: 50%;" class="form-control" name="stock_actu_m" for="" type="number"  required>
              </div>


              <div class="col-md-2 col-6">
                    <label> L:</label>
                            <input  class="form-control" name="L" for="" type="number" value='L' placeholder="L" readonly>
              </div>
              <div class="col-md-5 col-6">
                  <label style="margin-left:30%">  Stock initial:</label>
                            <input  style="width: 50%; margin-left:30%" class="form-control" name="stock_ini_l" for="" type="number"  required>
              </div>
              <div class="col-md-5 col-6">
             <label>  Stock actuel:</label>
                            <input  style="width: 50%;" class="form-control" name="stock_actu_l" for="" type="number"  required>
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

<script src="https://cdn.ckeditor.com/ckeditor5/22.0.0/classic/ckeditor.js"></script>
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
</main>
@endsection
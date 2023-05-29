<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  
  <title>Sous categorie index</title>

  <!-- Favicons -->
  <link href="" rel="icon">
 

  <!-- Bootstrap core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <!--external css-->
  <link href="../css/create_article.css" rel="stylesheet" />
 
  <!-- Custom styles for this template -->
  <link href="" rel="stylesheet">

                                                      
<!-- Datatables css -->
<link href="assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>


</head>

<body>

<div class="container">

@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

<form  method="POST" action="{{route('create_article')}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
@csrf
                <div class="row"> 
                    <div class="col">
                    
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
                    <div class="col-md-2 col-6">
                    <label for="attestation">Nouveauté</label>
                <input type="checkbox"  for="id_shop_article" value='1' name="nouveaute">
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

                    
                            <label>Age Minimal</label><input type="number" class="form-control" name="agemin" step="0.01" value="">

                    </div>

                    <div class="col-md-2 col-6">

                    <label>Age Maximal</label><input type="number" class="form-control" name="agemax" step="0.01" value="">

                    </div>

                    <div class="col-md-2 col-6">

                         <label> Prix TTC :</label>
                        
                        <input step="0.01" class="form-control" name="price" for="TTC" type="number" value=''>

                    </div>

                <div class="col-md-2 col-6">

                <label> Prix indicatif :</label>
                        
                        <input step="0.01" class="form-control" name="price_indicative" for="TTC" type="number" value=''>

                </div>

                <div class="col-md-2 col-6">

                <label> Quantité initale:</label>
                        
                        <input step="0.01" class="form-control" name="stock_ini" for="TTC" type="number" value=''>

                </div>

                <div class="col-md-2 col-6">

                <label>  Quantité restante: </label>
                        <input step="0.01" class="form-control" name="stock_actuel" for="TTC" type="number" value=''>

                </div>

                <div class="col-md-2 col-6">
                <label>  Quantité alerte:</label> 
                        <input step="0.01" class="form-control" name="alert_stock" for="TTC" type="number" value=''>

                </div>

                <div class="col-md-2 col-6">
                <label> type article  :</label>
                        <input step="1" class="form-control" name="type_article" for="" type="number" value='1'>

                </div>

                <div class="col-md-2 col-6">
                <label>  Max utilisateur:</label>
                        <input  class="form-control" name="max_per_user" for="" type="number" value=''>

                </div>
             
                <div class="row pt-3"> 
                <div class="col-md-4 col-6">
                <label>  Mode strict:</label>

                <table class="table">
                   

                        <tr>
                    
                        <td><input style="vertical-align:center;" for="" type="checkbox" name="strict" value=1 ></td>

                        </tr>
                       
            </table>


                </div>
                <div class="col-md-4 col-6">

                <label>  Attestation fiscale :</label>

                        <table class="table">

                                <tr>
                            
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
                </div>


</div>
<br> 

<!-- row beige  -->
  <div class="row" style="background-color: beige;border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

  <h3>Choix des catégories</h3>  

    <div class="col-md-4">
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


    <div class="col-md-4">
    <div style="height: 250px;  overflow: scroll; ">
<table class="table">
  <thead>
    <tr>
      
      <th scope="col">Choix des professeurs :</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  
  @foreach($requete_prof as $data)

    <tr>
    
      <td>{{$data->name}}  {{$data->lastname }}</td>
     
      <td><input style="vertical-align:center;" for="" type="checkbox" name="prof[]" value="{{$data->user_id}}"></td>
     

    </tr>

    @endforeach
   
  </tbody>
</table>
</div>
    </div>

    <div class="col-md-4">
  
        <div class="row-md-2 col-6">
        <div class="col-lg-12">
                    <div class="input_fields_wrap">
                    </div>
                    <br><button class="add_field_button btn btn-info">Ajouter des séances</button>
                </div>
        
        </div>
    </div>

  </div>

  <br> 


<!-- row rose -->
  <div class="row" style="background-color:pink; border-right: 2px solid grey;border-top: 2px solid grey;border-left: 2px solid grey;justify-content: center">

          
          <div class="row">
          
              <div class="col-sm-12">
                        <br>
                
                            
                
                              <label>Résumé </label>
                                <textarea type="text" name="short_description" class="form-control"></textarea>
                              <label>Description</label>
                                <textarea name="editor1"  id="ckeditor" class="form-control" ></textarea>
                                
                             
                
        
                    
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



</body>

</html>

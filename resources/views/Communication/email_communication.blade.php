<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap 4 Website Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
  <!-- Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<!-- Or for RTL support -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

  
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>

  <link href="../css/styleCom.css" rel="stylesheet">
</head>
<body>


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
                           <a href=""><button class="btn btn-warning"> retour</button></a>
                    </div>
            </div>

          
<form  method="POST" action="{{route('saison')}}" enctype="multipart/form-data" formnovalidate="formnovalidate">
        @csrf
              
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
                            <div class="row"> 
                        <div class="col-md-11"> 
                        <input class="btn btn-warning"  name="SubmitButton" type="submit" value="Valider">
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
<script>
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
</main>


</body>



@extends('layouts.template')

@section('content')
 

<div class="container" >
          

                 
          <div class="row"  style="background-color:lightblue;" >
         

          <div class="col-md-4" style="background-color:lightblue;">
          <br>
                  <h1> Envoi de mails</h1>
                  <a href=""> <button class="btn btn-primary"> retour </button> </a>
          <br>

          </div>
          <div class="col-md-8" style="background-color:light-blue;">
          <br>
         
          </div>

        



          </div>
          <form action=""  method="GET"  enctype="multipart/form-data">
          @csrf
          <div class="row pt-5">
          <input type="submit" class="btn btn-primary" value="Valider">
          </div>
         
          <div class="row">

          <div class="col-md-2">
              

          </div>

          



              
          <div class="col-md-8">
              <br>

                          <input type="text" name="title" class="form-control" placeholder="Title">

                          <textarea name="editor1"  id="ckeditor" class="form-control" name="ckeditor"></textarea>
                          
          </div>
              <div class="col-md-2">
              

              </div>



          </div>

         


  </div>

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
          { name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
          { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
          { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
          '/',
          { name: 'styles', items : [ 'Styles','Format' ] },
          { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
          { name: 'insert', items :[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
          { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
          { name: 'tools', items : [ 'Maximize','-','About' ] }
],
              uiColor: '#FFDC6E'
  });




</script>

</form>
@endsection
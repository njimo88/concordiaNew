@extends('layouts.template')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<!-- Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<!-- Or for RTL support -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/path/to/cdn/jquery.min.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>

@section('content')

<style>
.dataTables_wrapper .dataTables_filter {
    float: right !important;
    text-align: left !important;
}
</style>



@if (auth()->user()->role == 40)

@php 
$id_teacher = auth()->user()->user_id ;
$add = [] ;
@endphp

<main id="main" class="main">

<figure class="text-center">
  
    <h2>Envoi de mail.</h2>
  
{{$id_teacher}}
</figure>


<div class="container">

   
<div class="row pb-5" >

<select class="form-select form-control" id="multiple-select-field"  name="framework[]" data-placeholder="Choix d'articles" onchange="myFunction()"  multiple>
                @foreach($shop_article_lesson as $value)
                        @php $add [] = (array)json_decode($value->teacher) ; 
                if (isset($add)) {
                        foreach ($add as $teacherArray) {
                                  foreach($teacherArray as  $t){
                                   
                                    if ($id_teacher === $t){
                                        

                        @endphp
                        <option value="{{$value->id_shop_article}}">{{$value->title}}   {{$value->id_shop_article}}</option>
                                      
                        @php
                                        break;
                                    }
                                       
                                        }

                                        }

                                }
                                       
                                        $add = [] ;
                        @endphp
                       

               
                @endforeach
</select>
   
</div>



</div>


<div class="container">



<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
                <tr>
                    <th><input type="button" id="select-all" value="tout selectionner"> <input type="button" id="deselect-all" value="tout deselectionner"> </th>
                    <th hidden>Id user</th>
                    <th>Nom</th>
                    <th>email</th>
                    <th>Articles</th>
                    <th hidden>title</th>
                
                </tr>
    </thead>
 
   
 
    <tbody>
        
       @foreach($uusers_lesson as $data)
            <tr>
               <td><input type="checkbox" id="checkboxes" > </td>
               <td hidden> {{$data->user_id}} </td>
                <td> {{$data->name}} </td>
                <td> {{$data->email}} </td>
                <td id='myTdElement' hidden>{{$data->id_shop_article}}</td>
                <td>{{$data->title}}</td>
                
            </tr>
       @endforeach
  
    </tbody>

</table>

<br>




</div>


<div class="container">



<form action=""  method="GET"  enctype="multipart/form-data">
          @csrf
          <div class="row pt-5">
       
          </div>
         
          <div class="row">

          <div class="col-md-2">
              

          </div>

        
          <div class="col-md-8">
              <br>

                          <input type="text" id="title_email" name="title" class="form-control" placeholder="Title">

                          <textarea name="editor1"  id="ckeditor" class="form-control" ></textarea>
                          
          </div>
              <div class="col-md-2">
              

              </div>



          </div>

         


  </div>

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
<input  class="btn btn-primary"  id="button" value="Valider">
</form>




</div>




</main>

<script>
$( '#multiple-select-field' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
} );
</script>




<script>



    $(document).ready(function() {
    var table = $('#example').DataTable();
    
    var selectedRows = [];

    $('#select-all').on('click', function () {
    $('#example tbody tr').addClass('selected', selectedRows.length === 0);
    $('#example tbody input[type="checkbox"]').prop('checked', selectedRows.length === 0);
    if (selectedRows.length === 0) {
      selectedRows = rows;
    } else {
      selectedRows = [];
    }
   

  });

  $('#deselect-all').on('click', function () {
    selectedRows = [];
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $(rows).removeClass('selected');
    $('#example tbody input[type="checkbox"]').prop('checked', false);
  });

  // Handle pagination
  table.on('page.dt', function () {
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $(rows).removeClass('selected');
    selectedRows.forEach(function(row) {
      $(row).addClass('selected');
      $(row).find('input[type="checkbox"]').prop('checked', true);
    });
  });

  
    $('#example tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        var checkbox = $(this).find('input[type="checkbox"]');
        var row = $(this).closest('tr')[0];
    checkbox.prop('checked', !checkbox.prop('checked'));
    $(this).toggleClass('selected', checkbox.prop('checked'));
    if (checkbox.prop('checked')) {
      selectedRows.push(row);
    } else {
      selectedRows.splice(selectedRows.indexOf(row), 1);
    }

    });


    CKEDITOR.replace( 'ckeditor' );
    $('#button').click( function () {
        var editor = CKEDITOR.instances.ckeditor;
  if (editor) {
    var editorValue = editor.getData();
    console.log(editorValue);
  } else {
    console.log('Editor not found.');
  }
  var textWithoutTags = editorValue;

        alert( table.rows('.selected').data().length +' row(s) selected' );
        const selectedData = table.rows('.selected').data();
        const selectedRows = [];
        const tab_selected_users = [] ;

        if (selectedData.length > 0) {
        const selectedCellValue = table.cell('.selected', 3).data();
        console.log(selectedCellValue);
    }

          $.each(selectedData, function(index, value) {
              selectedRows.push(value);
          });
          console.log(selectedRows);

           for (let index = 0; index < table.rows('.selected').data().length; index++) {
           
            tab_selected_users.push(selectedRows[index][1]) ;
            
           }

          console.log(tab_selected_users);

         // return tab_selected_users;
         var inputValue = $('#title_email').val();
         var text_area  = textWithoutTags;
       
          $.ajax({


    url: '/Communication/email_sender',
    type: 'POST',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
        tab_selected_users: tab_selected_users,
        inputValue: inputValue,
        text_area: text_area
    },
    success: function(response) {
        console.log(response);
    },
    error: function(xhr, status, error) {
        console.log(xhr.responseText);
    }


});








});




});


  
</script>


<!-- Script JS qui permet de trier dynamiquement en fonction des shop articles, les users qui les ont achetes   -->
<script>

function myFunction(value) {

  var selectElement = document.querySelector('select');
  var selectedValues = [];
  const table = $('#example').DataTable();

  // loop through all the selected options and add their values to an array
  for (var i = 0; i < selectElement.options.length; i++) {
    if (selectElement.options[i].selected) {
      selectedValues.push(selectElement.options[i].value);
    }
  }

  // do something with the selected values
    console.log(selectedValues);
    const columnData = table.column(4).data();
    console.log(columnData);

    const filteredData = columnData.filter(value => selectedValues.includes(value));
    console.log(filteredData);
    
    table.column(4).data(filteredData);
    // Redraw the filtered column
    table.column(4).search(filteredData.join('|'), true, false).draw();

}
</script>




@endsection
























@elseif (auth()->user()->role == 90 || auth()->user()->role == 100)




<main id="main" class="main">

<figure class="text-center">
  
    <h2>Envoi de mail.</h2>

 
</figure>


<div class="container">

   
<div class="row pb-5" >

<select class="form-select form-control" id="multiple-select-field"  name="framework[]" data-placeholder="Choix d'articles" onchange="myFunction()"  multiple>
@foreach($shop_article as $value)
                  <option value="{{$value->id_shop_article}}">{{$value->title}}</option>
                @endforeach
</select>
   
</div>


</div>


<div class="container">



<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
                <tr>
                    <th><input type="button" id="select-all" value="tout selectionner"> <input type="button" id="deselect-all" value="tout deselectionner"> </th>
                    <th hidden>Id user</th>
                    <th>Nom</th>
                    <th>email</th>
                    <th>Articles</th>
                    <th hidden>title</th>
                
                </tr>
    </thead>
 
   
 
    <tbody>
        
       @foreach($uuser as $data)
            <tr>
               <td><input type="checkbox" id="checkboxes" > </td>
               <td hidden> {{$data->user_id}} </td>
                <td> {{$data->name}} </td>
                <td> {{$data->email}} </td>
                <td id='myTdElement' hidden>{{$data->id_shop_article}}</td>
                <td>{{$data->title}}</td>
                
            </tr>
       @endforeach
  
    </tbody>

</table>

<br>




</div>


<div class="container">



<form action=""  method="GET"  enctype="multipart/form-data">
          @csrf
          <div class="row pt-5">
       
          </div>
         
          <div class="row">

          <div class="col-md-2">
              

          </div>

        
          <div class="col-md-8">
              <br>

                          <input type="text" id="title_email" name="title" class="form-control" placeholder="Title">

                          <textarea name="editor1"  id="ckeditor" class="form-control" ></textarea>
                          
          </div>
              <div class="col-md-2">
              

              </div>



          </div>

         


  </div>

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
<input  class="btn btn-primary"  id="button" value="Valider">
</form>




</div>




</main>

<script>
$( '#multiple-select-field' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
} );
</script>




<script>



    $(document).ready(function() {
    var table = $('#example').DataTable();
    
    var selectedRows = [];

    $('#select-all').on('click', function () {
    $('#example tbody tr').addClass('selected', selectedRows.length === 0);
    $('#example tbody input[type="checkbox"]').prop('checked', selectedRows.length === 0);
    if (selectedRows.length === 0) {
      selectedRows = rows;
    } else {
      selectedRows = [];
    }
   

  });

  $('#deselect-all').on('click', function () {
    selectedRows = [];
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $(rows).removeClass('selected');
    $('#example tbody input[type="checkbox"]').prop('checked', false);
  });

  // Handle pagination
  table.on('page.dt', function () {
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $(rows).removeClass('selected');
    selectedRows.forEach(function(row) {
      $(row).addClass('selected');
      $(row).find('input[type="checkbox"]').prop('checked', true);
    });
  });

  
    $('#example tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        var checkbox = $(this).find('input[type="checkbox"]');
        var row = $(this).closest('tr')[0];
    checkbox.prop('checked', !checkbox.prop('checked'));
    $(this).toggleClass('selected', checkbox.prop('checked'));
    if (checkbox.prop('checked')) {
      selectedRows.push(row);
    } else {
      selectedRows.splice(selectedRows.indexOf(row), 1);
    }

    });


    CKEDITOR.replace( 'ckeditor' );
    $('#button').click( function () {
        var editor = CKEDITOR.instances.ckeditor;
  if (editor) {
    var editorValue = editor.getData();
    console.log(editorValue);
  } else {
    console.log('Editor not found.');
  }
  var textWithoutTags = editorValue;

        alert( table.rows('.selected').data().length +' row(s) selected' );
        const selectedData = table.rows('.selected').data();
        const selectedRows = [];
        const tab_selected_users = [] ;

        if (selectedData.length > 0) {
        const selectedCellValue = table.cell('.selected', 3).data();
        console.log(selectedCellValue);
    }

          $.each(selectedData, function(index, value) {
              selectedRows.push(value);
          });
          console.log(selectedRows);

           for (let index = 0; index < table.rows('.selected').data().length; index++) {
           
            tab_selected_users.push(selectedRows[index][1]) ;
            
           }

          console.log(tab_selected_users);

         // return tab_selected_users;
         var inputValue = $('#title_email').val();
         var text_area  = textWithoutTags;
       
          $.ajax({


    url: '/Communication/email_sender',
    type: 'POST',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
        tab_selected_users: tab_selected_users,
        inputValue: inputValue,
        text_area: text_area
    },
    success: function(response) {
        console.log(response);
    },
    error: function(xhr, status, error) {
        console.log(xhr.responseText);
    }


});








});




});


  
</script>


<!-- Script JS qui permet de trier dynamiquement en fonction des shop articles, les users qui les ont achetes   -->
<script>

function myFunction(value) {

  var selectElement = document.querySelector('select');
  var selectedValues = [];
  const table = $('#example').DataTable();

  // loop through all the selected options and add their values to an array
  for (var i = 0; i < selectElement.options.length; i++) {
    if (selectElement.options[i].selected) {
      selectedValues.push(selectElement.options[i].value);
    }
  }

  // do something with the selected values
    console.log(selectedValues);
    const columnData = table.column(4).data();
    console.log(columnData);

    const filteredData = columnData.filter(value => selectedValues.includes(value));
    console.log(filteredData);
    
    table.column(4).data(filteredData);
    // Redraw the filtered column
    table.column(4).search(filteredData.join('|'), true, false).draw();

}
</script>




@endsection


@endif



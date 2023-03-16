<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

<!-- jQuery JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />






<script src="/path/to/cdn/jquery.min.js"></script>


<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>

<link href="../css/styleCom.css" rel="stylesheet">

</head>
<body>
<div class="container">

<div class="row pb-5" style="background-color: red;">

        


   

    <select id="framework" name="framework[]" multiple class="form-control" >
    @foreach($shop_article as $value)
                <option value="{{$value->id_shop_article}}">{{$value->title}}</option>
               @endforeach
     </select>
   






</div>


<br>
<br>

             
<div class="row">

<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Nom</th>
                    <th>email</th>
                    <th>id article</th>
                
                </tr>
    </thead>
 
   
 
    <tbody>
        
       @foreach($uuser as $data)
            <tr>
            <td> <input type="checkbox"> </td>
                <td>{{$data->name}}</td>
                <td>{{$data->email}}</td>
                <td>{{$data->id_shop_article}}</td>
                
            </tr>
       @endforeach
       
    
    </tbody>

</table>
<button id="button">test</button>
</div>





</div>


<script>

$(document).ready(function(){
 $('#framework').multiselect({
  nonSelectedText: 'Select Framework',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'400px'
 });
 
});



</script>

<script>


</script>






<script>

    $(document).ready(function() {
    var table = $('#example').DataTable();
    
 
    $('#example tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );
 
    $('#button').click( function () {
        alert( table.rows('.selected').data().length +' row(s) selected' );
    } );
} );


</script>








</body>
</html>
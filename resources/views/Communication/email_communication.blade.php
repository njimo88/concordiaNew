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

<div class="container">
  <div class="row">
    <form action="{{route('test')}}" method="GET">
      @csrf
 <br> 
      <label> Selectionner les users</label>
      
      <div id="someScrollingDiv">
             <select class="form-select" id="multiple-select-field" data-placeholder="Choix des users" name="user[]" multiple>
     </div>
      @foreach($user as $data)
        <option value="{{$data->user_id}}">{{$data->name}} {{$data->lastname}}</option>
      @endforeach
        
      </select>
  </div>
  <br>
  <button class="btn-primary" type="submit"> valider  </button>
  <div class="row">
  <div class="table-responsive">

      
                
<table id="mytable" class="table table-bordred table-striped">
     
<thead>
        <tr>
            <th>Nom</th>
            <th>Prenom</th>
            <th>email</th>
            <th>adresse</th>
          
        </tr>
    </thead>
<tbody>
@foreach($user2 as $data)
        <tr>
       
            <td>{{$data->name}}</td>
            <td>{{$data->lastname}}</td>
            <td>{{$data->email}}</td>
            <td>{{$data->address}}</td>
            
           
            
        </tr>
@endforeach



</tbody>

</table>

<div class="clearfix"></div>
<div class="d-flex justify-content-center">
{!! $user2->links() !!}
</div>          
</div>
 
  </div>

</div>

<script>


$( '#multiple-select-field' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
} );


</script>

<script type="text/javascript">  
            function selects(){  
                var ele=document.getElementsByName('chk');  
                for(var i=0; i<ele.length; i++){  
                    if(ele[i].type=='checkbox')  
                        ele[i].checked=true;  
                }  
            }  
            function deSelect(){  
                var ele=document.getElementsByName('chk');  
                for(var i=0; i<ele.length; i++){  
                    if(ele[i].type=='checkbox')  
                        ele[i].checked=false;  
                      
                }  
            }             
        </script>  

          <script>

                $('#someScrollingDiv').on('scroll', function() {
                    let div = $(this).get(0);
                    if(div.scrollTop + div.clientHeight >= div.scrollHeight) {
                        // do the lazy loading here


                    }
                });




          </script>
        





</body>
</html>

   

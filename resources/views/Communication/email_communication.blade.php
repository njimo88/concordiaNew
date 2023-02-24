<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap 4 Website Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
  <link href="../css/styleCom.css" rel="stylesheet">
</head>
<body>

    <div class="container" >
    @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <div class="row"  style="background-color:lightblue;" >

            <div class="col-md-4" style="background-color:lightblue;">
            <br>
            <form action="{{route('saison')}}"  method="POST"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                            
                    <label for="email">Saison</label>  
                      <select name="saison" class="custom-select mb-3">
                          <option selected>Choix de la saison</option>
                          @foreach($saison_list as $data)
                          <option value="{{$data->saison}}">{{$data->saison - 1}} - {{$data->saison}} </option>
                          @endforeach
                      </select>
                    <input type="submit" class="btn btn-primary" value="Valider">
            </form>        

            <br>

            </div>
            <div class="col-md-8" style="background-color:light-blue;">
            <br>
           
            </div>

            </div>
            <div class="row">
                 
            </div>




    </div>

</body>
</html>

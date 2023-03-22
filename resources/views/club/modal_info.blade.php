<link rel="stylesheet" href="//cdn.tutorialjinni.com/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="//g.tutorialjinni.com/mojoaxel/bootstrap-select-country/dist/css/bootstrap-select-country.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
      
<div class="container border border-dark">
  
  <h1 style="text-align: center;">Modification d'utilisateur </h1>
  <form action="{{ route('modif_user',$the_id) }}" method="POST" id="myForm" >
@foreach($info_user as $data)

 
  @csrf

<div class="row">




<div class="col-md-4">
    <label  class="form-label">Nom</label>
<input type="text" class="form-control"  name="name" value="{{$data->name}}"> 
</div>
<div class="col-md-4">
<label  class="form-label">Prénom</label>
<input type="text" class="form-control"   name="lastname" value="{{$data->lastname}}" ></div>
<div class="col-md-4">
<label  class="form-label">ID</label>
<input type="text" class="form-control"  name="user_id" value="{{$data->user_id}}" readonly style="background-color: grey;">
</div>


</div>

<div class="row">

<div class="col-md-4">
    <label  class="form-label">Date de naissance</label>
<input type="Date" class="form-control"  name="birthdate" value="{{$data->birthdate}}" > 
</div>
<div class="col-md-4">




                                <div class="labels mb-3">
                                 <label for="nationality">Nationalité</label>
                                </div>
                                <select style="height: 35px;" data-style="btn-light" id="nationality" data-default="{{ $data->nationality }}" value="$data->nationality " class="border col-md-12 form-group countrypicker @error('nationality') is-invalid @enderror" name="nationality" data-flag="true" ></select>
                                  @error('nationality')
                                      <span class="text-alert" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                         


</div>
<div class="col-md-4">
<label  class="form-label">Profession</label>
<input type="text" class="form-control"  name="profession" value="{{$data->profession }}">
</div>

</div>
<div class="row">

<div class="col-md-4">
    <label  class="form-label">Email</label>
<input type="text" class="form-control"  name="email" value="{{$data->email}}"> </div>
<div class="col-md-4">
<label  class="form-label">Téléphone</label>
<input type="text" class="form-control"   name="phone" value="{{$data->phone }}"></div>
<div class="col-md-4">
<label  class="form-label">Sexe</label>
<input type="text" class="form-control"   name="gender"   value="{{$data->gender }}">
</div>


</div>
<div class="row">

<div class="col-md-4">
    <label  class="form-label">Adresse</label>
<input type="text" class="form-control"  name="address"  value="{{$data->address }}"> </div>
<div class="col-md-4">
<label  class="form-label">Ville</label>
<input type="text" class="form-control"   name="city"  value="{{$data->city}}"></div>
<div class="col-md-4">
<label  class="form-label">Code postal</label>
<input type="text" class="form-control"   name="zip"  value="{{$data->zip }}">
</div>




</div>
<div class="row">

<div class="col-md-4">




                                <div class="labels mb-3">
                                 <label for="nationality">Pays</label>
                                </div>
                                <select style="height: 35px;" data-style="btn-light" id="nationality" data-default="{{ $data->country }}" value="$data->country " class="border col-md-12 form-group countrypicker @error('country') is-invalid @enderror" name="country" data-flag="true" ></select>
                                  @error('nationality')
                                      <span class="text-alert" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                         


</div>
<div class="col-md-4">

<label  class="form-label">Date d'inscription</label>
<input type="text" class="form-control"   name="created_at" value="{{$data->created_at}}"readonly style="background-color: grey;">
</div>
<div class="col-md-4">
<label  class="form-label">Licence FFGYM</label>
<input type="text" class="form-control" name="licenceFFGYM" value="{{$data->licenceFFGYM }}"readonly style="background-color: grey;">
</div>

</div>  

<br>


</div>


<br>

<div class="row">
    
<button  onclick="submitForm()" type="submit" class="btn btn-primary">Valider</button>
</div>
</form>
@endforeach

<hr>
<h3>Informations sur les parents</h3>

@foreach($tab as $info)

<div class="row">
    
<div class="col-md-4">

<label  class="form-label"> Nom </label>
<input type="text" class="form-control"  name="name" value="{{$info->name}}" readonly style="background-color: grey;"> 
</div>

<div class="col-md-4">
<label  class="form-label">prenom</label>
<input type="text" class="form-control"   name="lastname" value="{{$info->lastname}}"readonly style="background-color: grey;">
</div>

<div class="col-md-4">
<label  class="form-label">Téléphone</label>
<input type="text" class="form-control"   name="phone" value="{{$info->phone }}" readonly style="background-color: grey;">
</div>

</div>

@endforeach

<script>
function submitForm() {
    document.getElementById("myForm").submit();
  }


</script>



</div>
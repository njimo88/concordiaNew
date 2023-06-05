@extends('layouts.template')

@section('content')
  <style>
    /* Vous pouvez ajouter ici des styles personnalisés */
    body {
      background-color: #f8f9fa;
    }

    .card {
      border-radius: 15px;
    }

    .btn {
      float: right;
    }
    .btn-custom {
        background-color: #6200ee;
        color: #fff;
    }
    .btn-custom:hover {
        background-color: #3700b3;
        color: #fff;
    }
    .card-custom {
        border-color: #6200ee;
    }
    .active-season{
    color: green !important;
    font-weight: bold;
}
  </style>
<main class="main" id="main">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
        
        
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card border-0 shadow-lg rounded-lg card-custom">
                    <div class="card-body bg-light">
                        <h5 class="card-title text-primary">Sélection de la saison</h5>
                        <p class="card-text">Sélectionnez la saison active à partir de cette liste déroulante.</p>
                        <form action="{{ route("setActiveSeason") }}" method="POST" id="activeSeasonForm">
                            @csrf
                            <div class="form-group">
                                <select class="form-control" id="activeSeason" name="activeSeason">
                                    @foreach($seasons as $season)
                                        <option value="{{ $season->saison }}" {{ ($season->activate == 1) ? 'selected' : '' }}>{{ $season->saison }}-{{ $season->saison+ 1 }}  
                                            @if($season->activate == 1)
                                                <span class="active-season">(Activée)</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-custom m-3 mx-0" id="submitBtn">Définir comme saison active</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main> 
    
    <script>
        document.getElementById("submitBtn").addEventListener("click", function(event){
          var confirmation = confirm("Êtes-vous sûr de vouloir modifier la saison active ?");
          if (!confirmation) {
            event.preventDefault();
          }
        });
    </script>
    

@endsection
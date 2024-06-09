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
    <a href="{{ route("createNewSeason") }}" class="btn btn-custom m-3 mx-0" id="new-season-button">Créer la nouvelle saison</a>

    <div class="container py-3">
        <h2 class="text-primary mb-3">Liste des saisons</h2>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Saison</th>
                <th>Fichier d'inscription 1</th>
                <th>Fichier d'inscription 2</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($seasons as $season)
                <div class="modal fade" id="duplicateModal-{{ $season->saison }}" tabindex="-1" role="dialog" aria-labelledby="duplicateModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="duplicateModalLabel">Dupliquer les produits de la saison</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="{{ route('seasons.duplicate', $season->saison) }}" method="POST">
                          @csrf
                          <div class="form-group mb-3">
                            <label for="target_season-{{ $season->saison }}">Saison cible</label>
                            <select class="form-control" id="target_season-{{ $season->saison }}" name="target_season">
                              @foreach($seasons as $targetSeason)
                                @if($targetSeason->saison != $season->saison)
                                  <option  value="{{ $targetSeason->saison }}">{{ $targetSeason->saison }}-{{ $targetSeason->saison + 1 }}</option>
                                @endif
                              @endforeach
                            </select>
                          </div>
                          <div class="form-group mb-3">
                            <label for="start_validity-{{ $season->saison }}">Début de validité</label>
                            <input type="date" class="form-control" id="start_validity-{{ $season->saison }}" name="start_validity">
                        </div>
                        
                          <button type="submit" class="btn btn-custom">Dupliquer les produits</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <tr>
                  <td>{{ $season->saison }}-{{ $season->saison+1 }}</td>
                  <td>{{ $season->fichier_inscription1 }}</td>
                  <td>{{ $season->fichier_inscription2 }}</td>
                  <!-- Ajoutez d'autres cellules selon vos besoins -->
                  <td>
                    <button class="btn btn-custom btn-sm" data-toggle="modal" data-target="#editModal-{{ $season->saison }}">Éditer</button>
                    <button class="btn btn-custom btn-sm mx-2" data-toggle="modal" data-target="#duplicateModal-{{ $season->saison }}">Dupliquer</button>
                  </td>
                </tr>
    
                <!-- Modal d'édition -->
                <div class="modal fade" id="editModal-{{ $season->saison }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Éditer la saison</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="{{ route("editSeason", $season->saison) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <div class="form-group">
                            <label for="fichier_inscription1-{{ $season->saison }}">Fichier d'inscription 1</label>
                            <input type="text" class="form-control" id="fichier_inscription1-{{ $season->saison }}" name="fichier_inscription1" value="{{ $season->fichier_inscription1 }}">
                          </div>
                          <div class="form-group">
                            <label for="fichier_inscription2-{{ $season->saison }}">Fichier d'inscription 2</label>
                            <input type="text" class="form-control" id="fichier_inscription2-{{ $season->saison }}" name="fichier_inscription2" value="{{ $season->fichier_inscription2 }}">
                          </div>
                          <!-- Ajoutez d'autres champs de formulaire selon vos besoins -->
                          <button type="submit" class="btn btn-custom">Sauvegarder</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </tbody>
          </table>
        </div>
    </div>
        
    <div class="container">
            <div class="row">
                <div class="col-lg-6">
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

                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg rounded-lg card-custom">
                        <div class="card-body bg-light">
                            <h5 class="card-title text-primary">Upgrade Articles</h5>
                            <form action="{{ route("upgradeArticles") }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="source_season">Source Season</label>
                                    <select class="form-control" id="source_season" name="source_season">
                                        @foreach($seasons as $season)
                                            <option value="{{ $season->saison }}">{{ $season->saison }}-{{ $season->saison + 1 }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="target_season">Target Season</label>
                                    <select class="form-control" id="target_season" name="target_season">
                                        @foreach($seasons as $season)
                                            <option value="{{ $season->saison }}">{{ $season->saison }}-{{ $season->saison + 1 }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="start_validity">Start Validity</label>
                                    <input type="date" class="form-control" id="start_validity" name="start_validity" required>
                                </div>
                                <div class="form-group">
                                    <label for="end_validity">End Validity</label>
                                    <input type="date" class="form-control" id="end_validity" name="end_validity" required>
                                </div>
                                <button type="submit" class="btn btn-custom m-3 mx-0">Upgrade Articles</button>
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
        document.getElementById("new-season-button").addEventListener("click", function(event){
          var confirmation = confirm("Êtes-vous sûr de vouloir créer une nouvelle saison ?");
          if (!confirmation) {
            event.preventDefault();
          }
        });
    </script>
    

@endsection
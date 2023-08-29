@extends('layouts.app')

@section('content')
<style>
.main {
    min-height: 100vh;
    background-color: #F5F5F5;
}



.custom-card-header {
    background-color: #A9BCF5;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.8em 1.2em;
}



.header-icon img {
    height: 24px;
    padding: 0 10px;
}

.custom-btn {
    background-color: #482683;
    color: #ffffff;
    height: 60px;
    font-size: 14px;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s;
    margin: 12px auto;
}

.custom-btn:hover {
    background-color: #7351a8;
    color: #ffffff !important;
}

.tile {
    text-align: center;
    padding: 1em;
    transition: background-color 0.3s;
}

.tile:hover {
    background-color: #6B6B6B;
}

.col-md-3, .col-sm-6 {
    padding: 10px;
}

.btn-block {
    display: block;
    width: 100%;
}

.card-header {
    background: linear-gradient(90deg, #482683 0%, #7351a8 100%);
    padding: 15px 20px;
    text-align: center;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}
h6.font-weight-bold {
    color: #ffffff;
    font-size: 18px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    margin: 0;
}
</style>

<main class="main" id="main">
    <div class="container">
        <div class="row">
            <div class="card shadow my-4 custom-card p-0">
                <div class="card-header d-flex justify-content-center custom-card-header">
                      <h6 class="font-weight-bold ">Mentions légales</h6>
                </div>
                <div class="row p-3">
                    @foreach($parametre as $pt)
                        <div class="col-md-3 col-sm-6 my-2">
                            <a href="{{ $pt->fichier_inscription3 }}" target="_blank" class="btn custom-btn btn-block">Statut de l'association</a>
                            <a href="{{ $pt->fichier_inscription2 }}" target="_blank" class="btn custom-btn btn-block">Règlement Intérieur</a>
                            <a href="{{ $pt->fichier_inscription1 }}" target="_blank" class="btn custom-btn btn-block">Notice d'assurance</a>
                        </div>
                    @endforeach
                    <div class="col-md-3 col-sm-6 my-2">
                        <a href="{{ asset('uploads/Fichiers/FFGym-Quest-Mineurs-2022-2023.pdf') }}" target="_blank" class="btn custom-btn btn-block">Questionnaire pour les Mineurs</a>
                        <a href="{{ asset('uploads/Fichiers/FFGym-Quest-Majeurs-2022-2023.pdf') }}" target="_blank" class="btn custom-btn btn-block">Questionnaire pour les Majeurs</a>
                    </div>
                    <div class="col-md-3 col-sm-6 my-2">
                        <a href="{{route('index_politique')}}" target="_blank" class="btn custom-btn btn-block">Politique de Confidentialité</a>
                        <a href="#" target="_blank" class="btn custom-btn btn-block">Conditions d'Utilisation</a>
                    </div>
                    <div class="col-md-3 col-sm-6 my-2">
                        <a href="{{ asset('uploads/Fichiers/Valeurs%20et%20Chartes%20-%20FFGym.pdf') }}" target="_blank" class="btn custom-btn btn-block">Valeurs et Chartes de la FFGym</a>
                        <a href="{{ asset('uploads/Fichiers/Eco%20Gym.pdf') }}" target="_blank" class="btn custom-btn btn-block">Charte EcoGym FFGym</a>
                        <a href="{{ asset('uploads/Fichiers/Charte%20Ethique%20et%20D%C3%A9ontologie%20ffgym.pdf') }}" target="_blank" class="btn custom-btn btn-block">Charte de l'Ethique et&nbsp;la D&eacute;ontologie FFGym</a>
                        <a href="{{ asset('uploads/Fichiers/Developpement%20durable.pdf') }}" target="_blank" class="btn custom-btn btn-block">Valeurs et Chartes du D&eacute;veloppement Durable</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@extends('layouts.app')

@section('content')
<div class="wave-header">
    <div class="contenu">
        <h1>DE VRAIES PERSONNES
            VOUS <br> AIDENT À FAIRE <br>
            DES PROGRÈS RÉELS.</h1>
        <p>Bonjour et bienvenue sur Gym Concordia
        </p>
            <img src="{{ asset("assets/images/famille.png") }}" class="laptop">
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#fff" fill-opacity="1" d="M0,64L120,90.7C240,117,480,171,720,202.7C960,235,1200,245,1320,250.7L1440,256L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path></svg>
    @include('A_Blog_index')
</div>
@endsection

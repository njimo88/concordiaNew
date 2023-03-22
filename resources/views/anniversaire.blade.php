@extends('layouts.app')

@section('content')
@php
use Carbon\Carbon;
@endphp
<main style="min-height:100vh; background-image: url('{{asset("/assets/images/background.png")}}" class="main" id="main">

<div class="container">
<div  class="row">
    <div style="margin-top : 80px;" id="vueParent">
        <div class="card shadow mb-4 border rounded">
            <div class="card-header py-3 d-flex justify-content-center">
                <h6 class="m-0 font-weight-bold text-primary text-center">Joyeux anniversaire ! - lundi 20 mars 2023</h6>
            </div>
            <div style="align-items :flex-center !important" class="card-body">
                @php
                    $date = new DateTime();
                    $dateString = $date->format('Y-m-d');
                    $filename = $dateString . "-birthday.jpg";

                @endphp
                <img src="{{ asset('assets/images/' . $filename ) }}" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="100">
            </div>
            <div style="align-items :flex-start !important" class="card-body">
                <p class="mt-2">Vous pouvez envoyer un petit message à nos membres qui fêtent leurs anniversaires aujourd’hui en cliquant sur leurs noms... À vous de jouer :</p>
                <ul>
                    @foreach ($usersbirth as $user) 
                      @php
                        $age = Carbon::parse($user->birthdate)->diffInYears(Carbon::now());
                      @endphp
                     <li>Écrivez à : {{ $user->name }} {{ $user->lastname  }} qui vient d'avoir ses <b>{{ $age }} ans</b> </li> 
                    @endforeach
                </ul>
                
                
                <br>  <p>La Gym Concordia vous souhaite un joyeux anniversaire!</p>      
            </div>
           
        </div>
    </div>
</div>
</div>

</main>
@endsection
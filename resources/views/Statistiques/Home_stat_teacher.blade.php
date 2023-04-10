@extends('layouts.template')

@section('content')

@php

require_once('../app/fonction.php');
$saison_active = saison_active() ;

@endphp
<main id="main" class="main">
@if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif


            <div class="container">

            @if (auth()->user()->role == 40 || auth()->user()->role == 30 )

            @php 


                        $id_teacher = auth()->user()->user_id ;
                        $my_articles = [] ;
                        $add = [] ;



            @endphp








            @endif





            jgjhgjhgh







            </div>



</main>
<script>


    let nbrPresents = [];
    let pourcentage = [];
    let colors = [];

    function percentage_2(num1, num2) {
        let value = (num1 * 100) / num2;
        if (value > 100) {
            value = 100
        } else {
            value = value
        }

</script>


@endsection




















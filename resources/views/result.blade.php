@extends('layouts.app')

@section('content')
<main class="main" id="main">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <h1 class="text-center mb-5">Vos scores :</h1>
        @if (session('scores'))
            @php
                $sortedScores = collect(session('scores'))->sortDesc()->take(5);
                $operationCounts = collect(session('operationCounts'));
                $maxScore = $sortedScores->max(); // Obtenir le score maximum pour déterminer le pourcentage
                $labels = $sortedScores->keys()->map(function($id_shop_category) {
                    return \App\Models\Shop_category::find($id_shop_category)->name;
                })->toArray();
                $scores = $sortedScores->values()->toArray();
            @endphp

            <!-- Afficher les bâtons de score ici -->
            <div class="row justify-content-center">
                @foreach($scores as $index => $score)
                    @php
                        $percentage = ($score / $maxScore) * 100; // Calculer le pourcentage par rapport au score maximum
                        $category = \App\Models\Shop_category::find($sortedScores->keys()[$index]);
                    @endphp
                    <div class="col-md-2 text-center mb-4">
                    @if ($percentage <= 0)
                        <p class="mb-1">{{ round($percentage, 2) }}%</p>
                        <img src="{{ asset('assets/images/int.png') }}" alt="{{ $category->name }}" style="margin-top: 210%;width: 30px; height: 30px; object-fit: cover;">
                        <a href="/SubCategorie_front/{{ $category->id_shop_category }}" class="d-inline-block">
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" style="width: 70px; height: 70px; object-fit: cover;">
                            <p class="mt-2">{{ $category->name }}</p>
                        </a>
                @else
                <p class="mb-1">{{ round($percentage, 2) }}%</p>
                        <div  class="d-flex align-items-end " style="margin: 20px auto;width: 20px; height: 200px; background-image: url('{{ asset('assets/images/carre-violet.jpg') }}'); background-position: bottom; background-size: 100% {{ $percentage }}%; background-repeat: no-repeat;"></div>
                        <a href="/SubCategorie_front/{{ $category->id_shop_category }}" class="d-inline-block">
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" style="width: 70px; height: 70px; object-fit: cover;">
                            <p class="mt-2">{{ $category->name }}</p>
                        </a>
                @endif
            </div>

                @endforeach
            </div>

            @php
            session()->forget('scores');
        @endphp
        
        @else
            <p>Aucun score n'a été enregistré.</p>
        @endif


    </div>
</main>

@endsection

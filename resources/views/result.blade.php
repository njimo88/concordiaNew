@extends('layouts.app')

@section('content')
<style>
    .container {
    padding: 2em;
}

.score-card {
    box-shadow: 0 4px 6px 0 hsla(0, 0%, 0%, 0.2);
    transition: all 0.3s ease-in-out;
    background-color: white !important;
    border-radius: 10px;
}

.score-card:hover {
    transform: scale(1.05);
}

.score-percentage {
    font-weight: bold;
}

.score-bar {
    width: 100%;
    transition: height 0.5s ease-in-out;
}

.score-image {
    width: 60%;
    height: 60%;
    object-fit: cover;
}

.no-score {
    text-align: center;
    color: #999 !important;
}

</style>
<main class="main" id="main">
    <div class="container">
        <h1 class="text-center mb-5">Vos scores :</h1>
        @if (session('scores'))
            @php
                $sortedScores = collect(session('scores'))->sortDesc()->take(5);
                $maxScore = $sortedScores->max(); 
                $labels = $sortedScores->keys()->map(function($id_shop_category) {
                    return \App\Models\Shop_category::find($id_shop_category)->name;
                })->toArray();
                $scores = $sortedScores->values()->toArray();
            @endphp
            <div class="row d-flex justify-content-center">
                @foreach($scores as $index => $score)
                    @php
                        $percentage = ($score / $maxScore) * 100; 
                        $category = \App\Models\Shop_category::find($sortedScores->keys()[$index]);
                    @endphp
                    <div class="col-lg-2 col-md-4 col-sm-6 text-center mb-4">
                        <div class="score-card">
                            <p class="mb-1 score-percentage">{{ round($percentage, 2) }}%</p>
                            <div class="score-bar" style="height: {{ $percentage }}%; background-image: url('{{ asset('assets/images/carre-violet.jpg') }}')"></div>
                            <a href="/SubCategorie_front/{{ $category->id_shop_category }}">
                                <img class="score-image" src="{{ $category->image }}" alt="{{ $category->name }}">
                                <p class="mt-2 score-name">{{ $category->name }}</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            @php
                session()->forget(['scores', 'operationCounts', 'multiplicativeScores']);
            @endphp
        @else
            <p class="no-score">Aucun score n'a été enregistré.</p>
        @endif
    </div>
</main>
@endsection

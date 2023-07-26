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
        min-height: 400px;
    }
    
    .score-card:hover {
        transform: scale(1.05);
    }
    
    .score-percentage {
        font-weight: bold;
    }
    
    .score-image {
        width: 60%;
        height: 60%;
        object-fit: cover;
    }
    
    .no-progress-image {
        width: 45%;
        height: 45%;
        margin-bottom: 144px;
    }
    
    @media (max-width: 575.98px) { 
      .score-image {
        width: 60%;
      }
    
      .score-card {
        flex: 0 0 50%; 
        min-height: 390px;
      }
    
      
    }
    
    .no-score {
        text-align: center;
        color: #999 !important;
    }
    
    .progress.vertical {
      width: 30px;
      height: 200px;
      display: inline-block;
      position: relative;
      margin: 0 auto;
    }
    
    .progress.vertical .progress-bar {
      width: 100%;
      position: absolute;
      bottom: 0;
      background: linear-gradient(to top, rgb(111, 0, 255), rgb(0, 17, 255));
      animation: grow 2s ease-in-out;
    }
    
    @keyframes grow {
      0% { height: 0%; }
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
                    <div class="col-md-2 col-4 text-center mb-4">
                        <div class="score-card row justify-content-center">
                            <p class="mb-1 score-percentage">{{ round($percentage, 2) }}%</p>
                            @if ($percentage == 0)
                                <img src="{{ asset('assets/images/int.png') }}" class="no-progress-image">
                            @else
                                <div class="progress vertical p-0 mb-3">
                                    <div class="progress-bar" role="progressbar" style="height: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            @endif
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

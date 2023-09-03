@extends('layouts.app')

@section('content')
<main style="min-height:100vh; background-color: #FAFAFA;" class="main" id="main">

    <div class="container my-5">
        <div class="card shadow border-0 rounded-lg">
            <div class="card-header">
                <h6>Résultats de la recherche pour : <b>{{ $_GET['query'] }}</b></h6>
            </div>
            <div class="card-body">
                @foreach ($results as $result)
                    <div class="search-item mb-3 px-3 py-2">
                        @if ($searchType === 'blog')
                            <a class="aSearch d-block" href="{{ url('/Simple_Post/' . $result->id_blog_post_primaire) }}"><i class="far fa-newspaper me-2"></i>[{{ $result->date_post }}] {{ $result->titre }}</a>
                        @elseif ($searchType === 'shop')
                            <a class="aSearch d-block" href="{{ url('/details_article/' . $result->id_shop_article) }}"><i class="fas fa-store me-2"></i>[{{$result->saison }}-{{ $result->saison+1 }}] {{ $result->title }}</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</main>

<style>
    .card-body
    {
        background-color: #f8f8f8;
        align-items: flex-start;
    }
    .aSearch {
        color: #482683;
        transition: all 0.3s;
        font-size: 16px;
    }
    .aSearch:hover {
        text-decoration: none;
        color: #7351a8;
    }
    .search-item {
        border-left: 3px solid #e0e0e0;
        border-radius: 4px;
        transition: all 0.3s;
    }
    .search-item:hover {
        background-color: #f8f8f8;
        border-left-color: #482683;
    }
    .card-header {
        background: linear-gradient(90deg, #482683 0%, #7351a8 100%);
        padding: 15px 20px;
        text-align: left;  /* Alignement à gauche */
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }
    h6 {
        color: #ffffff;
        font-size: 18px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        margin: 0;
    }
</style>

@endsection

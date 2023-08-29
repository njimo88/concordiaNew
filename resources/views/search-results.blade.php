@extends('layouts.app')

@section('content')
<main style="min-height:100vh;" class="main" id="main">

    <div class="container my-5">
        <div class="card shadow border-0 rounded-lg">
            <div class="card-header bg-primary text-white">
                <h6 class="font-weight-bold ">Résultats de la recherche pour : <b style="color : #63c3d1">{{ $_GET['query'] }}</b></h6>
            </div>
            <div class="card-body">
                @foreach ($results as $result)
                    @if ($searchType === 'blog')
                        <div class="search-item mb-3">
                            <a class="aSearch text-primary p-2 d-block" href="{{ url('/Simple_Post/' . $result->id_blog_post_primaire) }}">[{{ $result->date_post }}] {{ $result->titre }}</a>
                        </div>
                    @elseif ($searchType === 'shop')
                        <div class="search-item mb-3">
                            <a class="aSearch text-primary p-2 d-block" href="{{ url('/details_article/' . $result->id_shop_article) }}">[{{$result->saison }}-{{ $result->saison+1 }}] {{ $result->title }}</a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

</main>

<style>
    
    .aSearch:hover {
        text-decoration: none;
        opacity: 0.8;
    }
    .search-item {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        transition: all 0.3s;
    }
    .search-item:hover {
        background-color: #f8f8f8;
        color: #f8f8f8 !important;
    }
    .search-item:hover a {
        color: black !important;
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
body , main{
    background-color: #FAFAFA;
}
</style>

@endsection

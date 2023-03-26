@extends('layouts.app')

@section('content')
<main style="min-height:100vh; background-image: url('{{asset("/assets/images/background.png")}}" class="main" id="main">

<div class="container">
<div  class="row">
    <div class="mt-4" id="vueParent">
        <div class=" container row card shadow mb-4 border rounded">
            <div  class="card-header py-3">
                <h6 class="font-weight-bold ">Résultats de la recherche pour : {{ $_GET['query'] }}</h6>
            </div>
            <div  class="card-body row d-flex justify-content-center ">
                @foreach ($results as $result)
                @if ($searchType === 'blog')
                    <div class="border border-dark col-9 m-2">
                        <a class="aSearch p-2" href="{{ url('/Simple_Post/' . $result->id_blog_post_primaire) }}">[{{ $result->date_post }}] {{ $result->titre }}</a>
                    </div>
                @elseif ($searchType === 'shop')
                    <div class="border border-dark col-9 m-2">
                        <a class="aSearch p-2" href="{{ url('/details_article/' . $result->id_shop_article) }}">[{{$result->saison }}-{{ $result->saison+1 }}] {{ $result->title }}</a>
                    </div>
                @endif
            @endforeach
            
            </div>
           
        </div>
    </div>
</div>
</div>

</main>
@endsection
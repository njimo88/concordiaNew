@extends('layouts.app')

@section('content')
<main style="min-height:100vh; background-image: url('{{asset("/assets/images/background.png")}}" class="main" id="main">

<div class="container">
<div  class="row">
    <div class="mt-4" id="vueParent">
        <div class="card shadow mb-4 border rounded">
            <div style="background-color: #f3eded;height:45px;padding: 3px;">
                <span x style="margin-right: 5px; vertical-align: sub;">
                    <a style="text-decoration:none" href="https://www.gym-concordia.com/index.php/category//1">
                        <img style="height:26px" src="">
                    </a>
                </span>
                <span class="info-news-emit">
                    <img src="https://www.gym-concordia.com/favicon.png" alt="Gym concordia" title="Gym Concordia"><font style="color: #b8c2c2; text-shadow: 1px 1px 1px #ffffff;vertical-align: -webkit-baseline-middle;">Publié par <a id="test" href="#"  >Gym Concordia</a> le
                                              <em><?php echo date("d/m/Y", strtotime($a_post->date_post)); ?></em>
        </font>
      </span>
    </div>
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    @php
                    /* we use json_decode to make this transformation : JSON arrays become PHP numeric arrays */
                    $a_macategorie1 = json_decode($a_post->categorie1) ;
                    foreach($a_macategorie1 as $i){
                        foreach($a_categorie1 as $j){
                            if($i == ($j->Id_categorie1)){
                    @endphp  
                    <a href="{{route('A_blog_par_categorie1', ['id' => $i])}}"> <img src='{{ $j->image }}'>  </a>
                    @php
                            }
                        }
                    }
                    @endphp   
                    {{$a_post->titre}} 
                    @php
                    /* we use json_decode to make this transformation : JSON arrays become PHP numeric arrays */
                    $a_macategorie2 = json_decode($a_post->categorie2) ;
                    foreach($a_macategorie2 as $i){
                        foreach($a_categorie2 as $j){
                            if($i == ($j->Id_categorie2)){
                    @endphp  
                    <a href="{{route('A_blog_par_categorie2', ['id' => $i])}}"> <img src='{{ $j->image }}'>  </a>
                    @php
                            }
                        }
                    }
                    @endphp
                </h6>
            </div>
            <div style="text-align: left !important;" class="card-body">
                {!!html_entity_decode($a_post->contenu) !!}
            </div>
           
        </div>
    </div>
</div>
</div>

</main>
@endsection
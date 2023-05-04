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
                        <?php
                        // Configure la locale en français
                        setlocale(LC_TIME, 'fr_FR.UTF-8');
                        
                        // Tableau de traduction des mois
                        $englishToFrench = [
                            'January' => 'janvier',
                            'February' => 'février',
                            'March' => 'mars',
                            'April' => 'avril',
                            'May' => 'mai',
                            'June' => 'juin',
                            'July' => 'juillet',
                            'August' => 'août',
                            'September' => 'septembre',
                            'October' => 'octobre',
                            'November' => 'novembre',
                            'December' => 'décembre',
                        ];
                        
                        // Formate la date en français avec strftime()
                        $dateInFrench = strftime('%d %B %Y', strtotime($a_post->date_post));
                        
                        // Traduit les noms des mois en français
                        $dateInFrench = strtr($dateInFrench, $englishToFrench);
                        ?>
                        <em><?php echo $dateInFrench; ?></em>
                        
        </font>
      </span>
    </div>
            <div  class="card-header py-3">
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
                    <p class="m-0" style="color : #084B8A !important">{{$a_post->titre}} </p>
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
            <div style="text-align: left !important; background-color:#ffffff !important" class="card-body">
                {!!html_entity_decode($a_post->contenu) !!}
            </div>
            <hr>
            <div class="footer-news p-3 py-2 mb-3">
                <div class="col-lg-12">
                  <a class="_2vmz" href="https://www.facebook.com/sharer.php/?u=https%3A%2F%2Fdev2022.gym-concordia.com%2F&amp;display=popup&amp;ref=plugin&amp;src=like&amp;kid_directed_site=0&amp;app_id=113869198637480" style="text-decoration:none" target="_blank" id="u_0_2_hC">
                    <i class="fa-brands fa-facebook fa-2xl" style="color:#3c5a99"></i>
                  </a>
                  <a class="_2vmz" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https://dev2022.gym-concordia.com/index.php&amp;display=popup&amp;ref=plugin&amp;src=like&amp;kid_directed_site=0&amp;app_id=113869198637480" style="text-decoration:none" target="_blank" id="u_0_2_hC">
                    <i class="fa-brands fa-linkedin fa-2xl" style="color:#0274b3"></i>
                  </a>
                  <a class="_2vmz" href="https://twitter.com/intent/tweet?url=https://dev2022.gym-concordia.com/index.php&amp;text=&amp;display=popup&amp;ref=plugin&amp;src=like&amp;kid_directed_site=0&amp;app_id=113869198637480" style="text-decoration:none" target="_blank" id="u_0_2_hC">
                    <i class="fa-brands fa-twitter fa-2xl" style="color:#1da1f2"></i>
                  </a>
                  <a class="_2vmz" href="https://pinterest.com/pin/create/button/?url=https://dev2022.gym-concordia.com/index.php&amp;media=&amp;description=&amp;display=popup&amp;ref=plugin&amp;src=like&amp;kid_directed_site=0&amp;app_id=113869198637480" style="text-decoration:none" target="_blank" id="u_0_2_hC">
                    <i class="fa-brands fa-pinterest fa-2xl" style="color:#ca2127"></i>
                  </a>
                </div>
                </div>
        </div>
        
    </div>
    
</div>
</div>

</main>
@endsection
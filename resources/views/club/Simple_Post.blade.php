@extends('layouts.app')

@section('content')
<main style="min-height:100vh; background-image: url('{{asset("/assets/images/background.png")}}" class="main" id="main">

<div class="container">
<div  class="row">
    <div class="mt-4" id="vueParent">
        <div style="    border: solid !important;
              border-width: 1px !important;
              border-color: grey !important;
              margin-bottom: 10px !important;
              box-shadow: 3px 3px 3px #5c5c5c !important;
              " class="card shadow mb-4">
                                  <div style="background-color: #A9BCF5 !important" class="card-header py-2  d-flex justify-content-between">
                                    <div class="col-9 d-flex align-items-center">
                                      <h6 class="m-0 font-weight-bold text-primary">
    
                                      @php
    
                                          /* we use json_decode to make this transformation : JSON arrays become PHP numeric arrays */
    
                                              $a_macategorie1 = json_decode($a_article->categorie1) ;
                                              
                                              
                                              foreach($a_macategorie1 as $i){
    
                                                  foreach($a_categorie1 as $j){
    
                                                      if($i == ($j->Id_categorie1)){
    
                                        @endphp  
                                                      
                                                          <a  href="{{route('A_blog_par_categorie1', ['id' => $i])}}"> <img src='{{ $j->image }}'>  </a>
                                        @php
                                                      }
                                                  }
    
    
                                              }
    
                                        @endphp   
    
                                            <a style="color : #084B8A !important" href="{{ route('Simple_Post',$a_article->id_blog_post_primaire) }}">{{$a_article->titre}}</a>
    
                                        @php
    
                                          /* we use json_decode to make this transformation : JSON arrays become PHP numeric arrays */
    
    
                                          $a_macategorie2 = json_decode($a_article->categorie2) ;
    
    
    
    
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
                                    @php
                                         $mois_francais = [
                                            '01' => 'Janvier',
                                            '02' => 'Février',
                                            '03' => 'Mars',
                                            '04' => 'Avril',
                                            '05' => 'Mai',
                                            '06' => 'Juin',
                                            '07' => 'Juillet',
                                            '08' => 'Août',
                                            '09' => 'Septembre',
                                            '10' => 'Octobre',
                                            '11' => 'Novembre',
                                            '12' => 'Décembre',
                                        ];
                                        $date = \Carbon\Carbon::parse($a_article->date_post);
                                        $mois = $mois_francais[$date->format('m')];
                                    @endphp
                                    <div class="col-lg-3 d-flex justify-content-end">
                                        <font class="news-small" style=" text-shadow: 1px 1px 1px #ffffff; font-size: 5px !important;">
                                            <p class="m-0" style="font-size: 13px; text-align :end">Publié par 
                                                <a style="color : #1c3e70 !important;font-weight : bold;" href="#" data-toggle="modal" data-target="#mailModal" 
                                                   data-firstnamesend="{{$a_article->name}}" 
                                                   data-lastnamesend="{{$a_article->lastname}}" 
                                                   data-mailsend="{{$a_article->email}}" 
                                                   data-firstnamedest="{{$a_article->name}}" data-lastnamedest="{{$a_article->lastname}}" 
                                                   data-mail="{{$a_article->email}}">
                                                    {{$a_article->name}} {{$a_article->lastname}}
                                                </a><br> le <em><time datetime="<?php echo e($a_article->date_post); ?>"><?php echo e($date->format('d')); ?> <?php echo e($mois); ?> <?php echo e($date->format('Y')); ?></time></em>
                                            </p>
                                        </font>
                                    </div>
                                    
                                    
                                    
                                 
                                  </div>
    
    
    
    
    
                                  <div style="align-items: start !important; background-color : #FFFFFF !important" class="card-body post-content">
                                    {!!html_entity_decode($a_article->contenu) !!}
                                </div>
                                <script >
                                        $(window).on('scroll',function(){
                                            clearTimeout(fetch);
                                        
                                            fetch = setTimeout(function () {
                                                var page_url = $("#posts").attr('next-page-url');
                                                console.log("scrolled");
                                            // This condition is very essential //
                                                if (page_url != null) {
                                                    $.get(page_url, function (data) {
                                                        $("#posts").append(data.view);
                                                        $("#posts").attr('next-page-url', data.url);
                                                    });
                                                }
                                            }, 2000);
                                        });
                                    </script>
    
    
              
              
              
    
                
    
                  <hr class="">
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
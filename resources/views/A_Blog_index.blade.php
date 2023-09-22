@extends('layouts.app')

@section('content')
<style>
  .icon-image {
  height: 80px;
  width: auto;
}

.icon-box-custom-height {
  height: 30px !important;
  display: flex;
  align-items: center;
  padding: 5px;
}

</style>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="modal fade" id="contactModal" tabindex="-1" style="z-index:100000" data-backdrop="static" data-keyboard="false" aria-labelledby="contactModalLabel" aria-hidden="true" data-target="#myModal">
  <div class="modal-dialog modal-lg">
      <div style="background-color: #d8e3ff" class="modal-content">
          <div class="modal-header border m-4 mb-0" style="background-color: white;">
              <h5 class="modal-title" id="contactModalLabel" style="font-size: 1.5em;font-weight: 400; font-family: Arial, Helvetica, sans-serif">Envoyer un Message</h5>
              <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <form action="{{route('traitement_prendre_contact')}}" id="sendMailForm" method="post" class="form-example">
              @csrf
              <div class="modal-body p-5 py-3">
                  <fieldset class="form-group">
                      <div class="form cf form-group row">
                          <div class="plan cf form-group col-sm-10">
                              <input type="radio" name="send_me" id="bureau" value="1">
                              <label class="bureau-label four col" for="bureau">Bureau</label>
                              <input type="radio" name="send_me" id="tresorier" value="2">
                              <label class="tresorier-label four col" for="tresorier">Trésorier</label>
                              <input type="radio" name="send_me" id="president" value="3">
                              <label class="president-label four col" for="president">Président</label>
                          </div>
                      </div>
                  </fieldset>
                  <div class="form-row row mt-5">
                      @if (!auth()->user())
                      <div class="form-group col-md-5">
                          <label class="text-dark" for="name">&nbsp;Prénom - Nom</label>
                          <input placeholder="Prénom - Nom" class="form-control mt-2" type="text" name="name" value="" required>
                      </div>
                      <div class="form-group col-md-7">
                          <label class="text-dark" for="email">&nbsp;Email</label>
                          <input placeholder="Email" class="form-control mt-2" type="email" name="email" value="" required>
                      </div>
                      @else
                      <div class="form-group col-md-5">
                          <label class="text-dark" for="name">&nbsp;Prénom - Nom</label>
                          <input placeholder="Prénom - Nom" class="form-control mt-2" type="text" name="name" value="{{auth()->user()->lastname}} {{auth()->user()->name}}" required readonly style="background-color: grey; color:#fff">
                      </div>
                      <div class="form-group col-md-7">
                          <label class="text-dark" for="email">&nbsp;Email</label>
                          <input placeholder="Email" class="form-control mt-2" type="email" name="email" value="{{auth()->user()->email}}" required readonly style="background-color: grey; color:#ffffff">
                      </div>
                      @endif
                  </div>
                  <div class="form-row">
                      <div class="form-group col-md-12">
                          <label class="text-dark" for="message">&nbsp;Votre Message</label>
                          <textarea style="width: 100%; height: 150px;" placeholder="Votre message" class="form-control mt-2" required name="message" value=""></textarea>
                      </div>
                  </div>
                  <div class="row d-flex justify-content-between m-1 mt-4">
                      <div class="col-3 d-flex justify-content-center">
                          <button type="button" class="cancel btn btn-secondary" data-dismiss="modal">Annuler</button>
                      </div>
                      <div class="col-3 d-flex justify-content-center">
                          <button type="submit" class="submit btn btn-primary">Envoyer</button>
                      </div>
                  </div>
              </div>
          </form>
      </div>
  </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?render=6Lfcl0UoAAAAAN81l-w_Z0T7Qs0NPkaHkvmX5ubS"></script>
<script>
  grecaptcha.ready(function() {
      grecaptcha.execute('6Lfcl0UoAAAAAN81l-w_Z0T7Qs0NPkaHkvmX5ubS', {action: 'submit'}).then(function(token) {
          var form = document.getElementById('sendMailForm');
          var input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'g-recaptcha-response';
          input.value = token;
          form.appendChild(input);
      });
  });
</script>



<!-- header Content -->
<section style="background-image: url('{{asset("/assets/images/background.png")}}')" id="hero" class="hero">
    <div class="container position-relative">
      <div class="row d-flex justify-content-center gy-5" data-aos="fade-in">
        <div class="col-lg-6 order-1 order-lg-2">
          @php
            $date = new DateTime();
            $dateString = $date->format('Y-m-d');
            $filename = $dateString . "-birthday.jpg";
          @endphp
          <a href="{{ route('anniversaire') }}"><img  src="{{ asset('assets/images/'.$filename) }}" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="100"></a>
        </div>
        <div style="    background-color: #DAE7FF;
        border: 2px solid #1F6FB5!important;" class="col-11 col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start border border-dark">
          {!! $post->contenu !!}
        </div>
      </div>
    
    <style>
      .icon-box {
        padding: 5px;
        display: flex !important;
        align-items: center !important;
        height: 30px !important;
        justify-content: center !important;
      }
    </style>
    
    
        <div class="row mt-4">
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-center" data-aos="fade-up" data-aos-delay="100">
            <div class="icon-box" style="background-color: #cc0000;">
              <img src="{{ asset('assets/images/1.png') }}" alt="" class="icon-image">
              <h4 class="title" style="color: white; margin: 5px;"><a href="{{-- route('masection') --}}#">Je choisis ma section</a></h4>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="icon-box" style="background-color: #e0be00;">
              <img src="{{ asset('assets/images/2.png') }}" alt="" class="icon-image">
              <h4 class="title" style="color: white; margin: 13px;"><a href="" data-toggle="modal" data-target="#contactModal">Nous contacter</a></h4>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="icon-box" style="background-color: #2626f7;">
              <img src="{{ asset('assets/images/3.png') }}" alt="" class="icon-image">
              <h4 class="title" style="color: white; margin: 0;"><a href="{{ route('Simple_Post',13019) }}">Questions Fréquentes</a></h4>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="500">
            <div class="icon-box" style="background-color: green;">
              <img src="{{ asset('assets/images/4.png') }}" alt="" class="icon-image">
              <h4 class="title" style="color: white; margin: 4px;"><a href="#">Animations été</a></h4>
            </div>
          </div>
        </div>
      
    
  
    </div>
  </section>
<!-- Fin header  -->
<main class="main" id="main" style="background-image: url('{{ asset("/assets/images/background.png") }}');">
  <div class="container">
      <div class="row">
          <div id="post-data">
            @foreach($a_post as $a_article)
    
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
    
                                              $a_macategorie1 = json_decode($a_article->categorie) ;
                                              
                                              
                                              foreach($a_macategorie1 as $i){
    
                                                  foreach($a_categorie1 as $j){
    
                                                      if($i == ($j->Id_categorie)){
    
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
    
    
                                          $a_macategorie2 = json_decode($a_article->categorie) ;
    
    
    
    
                                          foreach($a_macategorie2 as $i){
    
                                          foreach($a_categorie2 as $j){
    
                                              if($i == ($j->Id_categorie)){
    
    
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
            @endforeach     
          </div>
  
          <div class="ajax-load text-center" style="display:none">
              <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Chargement de plus de posts</p>
          </div>
      </div>
  </div>
</main>

<script>
  let page = 1;

  $(window).scroll(function() {
      if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
          page++; // Incrémente la page quand on atteint le bas
          loadMoreData(page); // Charge plus de données
      }
  });

  function loadMoreData(page) {
      $.ajax({
          url: '?page=' + page,
          type: "get",
          beforeSend: function() {
              $('.ajax-load').show(); // Vous pouvez montrer un spinner ici
          }
      }).done(function(data) {
          if (data.html == " ") {
              $('.ajax-load').html("Pas de données supplémentaires disponibles");
              return;
          }
          $('.ajax-load').hide(); // Cache le spinner
          $("#post-data").append(data.html); // Ajoute les nouveaux posts
      }).fail(function(jqXHR, ajaxOptions, thrownError) {
          alert('serveur non répondant...');
      });
  }
</script>


@endsection

                                
            

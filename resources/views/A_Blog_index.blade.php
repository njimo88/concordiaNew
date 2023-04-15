@extends('layouts.app')

@section('content')
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
          <a href="{{ route('anniversaire') }}"><img style="width : 100% !important; height: 284px !important;" src="{{ asset('assets/images/'.$filename) }}" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="100"></a>
        </div>
        <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start border border-dark">
          {!! $post->contenu !!}
        </div>
      </div>
    </div>
  
    <div style="background-image: url('{{asset("/assets/images/background.png")}}');" class="icon-boxes position-relative">
      <div class="container position-relative">
          <div class="row mt-5">
            <div class="col-md-6 col-lg-3 mb-4 mb-lg-0 col-6" data-aos="fade-up" data-aos-delay="100">
              <div class="icon-box">
                <div class="icon "><i class="bi bi-easel"></i></div>
                <h4 class="title"><a href="{{ route('questionnaire') }}">Je choisis ma section</a></h4>
              </div>
            </div>
          
            <div class="col-md-6 col-lg-3 mb-4 mb-lg-0 col-6" data-aos="fade-up" data-aos-delay="200">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-envelope"></i></div>
                <h4 class="title"><a href="">Nous contacter</a></h4>
              </div>
            </div>
          
            <div class="col-md-6 col-lg-3 mb-4 mb-md-0 col-6" data-aos="fade-up" data-aos-delay="300">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-question"></i></div>
                <h4 class="title"><a href="">Questions Fréquentes</a></h4>
              </div>
            </div>
          
            <div class="col-md-6 col-lg-3 col-6" data-aos="fade-up" data-aos-delay="500">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-info-circle"></i></div>
                <h4 class="title"><a href="">Je te dis plus tard</a></h4>
              </div>
            </div>
          </div>
      </div>
    </div>
  
    </div>
  </section>
<!-- Fin header  -->
<main class="main" id="main" style="background-image: url('{{asset("/assets/images/background.png")}}');" >
<div  class="container">
 
    <div class="row">
         <div id="vueParent">
             <div id="posts" next-page-url=" {{ $a_post->nextPageUrl() }}">
             @foreach($a_post as $a_article)
              @include('A_Blog_scroll')
             <div class="card shadow mb-4">
                                 <div class="card-header py-3">
                                     <h6 class="m-0 font-weight-bold text-primary">
 
                                     @php
 
                                         /* we use json_decode to make this transformation : JSON arrays become PHP numeric arrays */
 
                                             $a_macategorie1 = json_decode($a_article->categorie1) ;
                                             
                                             
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
                                        <a href="{{ route('Simple_Post',$a_article->id_blog_post_primaire) }}">{{$a_article->titre}}</a>

                                         
 
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
 

 
                                 <div class="card-body">
                                 {!!html_entity_decode($a_article->contenu) !!}
                                 </div>

 
                 <hr class="my-4">
 
 
                 <p>{{$a_article->date_post}}</p>
 
             
                 
           </div>
           @endforeach      
                    
                
               
             </div>
         
 
         </div>
         
     </div>
 
 
   </div>
 </main>
    
   <script>
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
@endsection

                                
            

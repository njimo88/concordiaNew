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
<div class="modal fade" id="contactModal" tabindex="-1" style="z-index:100000" data-backdrop="static" data-keyboard="false" aria-labelledby="contactModalLabel" aria-hidden="true" data-target="#myModal">
      
 

     
  <div class="modal-dialog  modal-lg">
       <div style="background-color: #d8e3ff" class="modal-content">
           <div class="modal-header border m-4 mb-0" style="background-color: white;">
               <h5 class="modal-title" id="contactModalLabel" style=" font-size: 1.5em;font-weight: 400; font-family: Arial, Helvetica, sans-serif">Envoyer un Message</h5>
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

                               <input type="radio" name="send_me" id="bureau" value="1" >
                               <label class="bureau-label four col" for="bureau">
                                   Bureau
                               </label>

                               <input  type="radio" name="send_me" id="tresorier" value="2">
                               <label class="tresorier-label four col" for="tresorier">
                                   Trésorier
                               </label>

                               <input  type="radio"name="send_me" id="president" value="3">
                               <label class="president-label four col" for="president">
                                   Président
                               </label>

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
                           <input placeholder="Prénom - Nom" class="form-control mt-2" type="text" name="name" value="{{auth()->user()->lastname}} {{auth()->user()->name}} " required readonly style="background-color: grey; color:#fff">
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
                   <div class='form-row mt-3'>
                       <div class='form-group col-md-12' style="text-align:-webkit-center">
                           <div class='g-recaptcha' name="captchaTest" data-sitekey='6Lft5c8UAAAAAORHh9eDop9d3C8R2IJfrBqc0Sx3'></div>
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
        <div style="    background-color: #DAE7FF;
        border: 2px solid #1F6FB5!important;" class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start border border-dark">
          {!! $post->contenu !!}
        </div>
      </div>
    </div>
    
    <div style="background-image: url('{{asset("/assets/images/background.png")}}');" class="icon-boxes position-relative">
      <div class="container position-relative">
        <div class="row mt-5">
  <div class="col-md-6 col-lg-3 mb-4 mb-lg-0 col-6" data-aos="fade-up" data-aos-delay="100">
    
    <div class="icon-box d-flex align-items-center" style="background-color: #cc0000; padding: 5px;">
      <img src="{{ asset('assets/images/1.png') }}" alt="" class="icon-image">
      <h4 class="title" style="color: white; margin: 5px;"><a href="{{ route('questionnaire') }}">Je choisis ma section</a></h4>
    </div>
  
  </div>
</a>
  <div class="col-md-6 col-lg-3 mb-4 mb-lg-0 col-6" data-aos="fade-up" data-aos-delay="200">
    <a href="" data-toggle="modal" data-target="#contactModal">
    <div class="icon-box d-flex align-items-center" style="background-color: #e0be00; padding: 5px;">
      <img src="{{ asset('assets/images/2.png') }}" alt="" class="icon-image">
      <h4 class="title" style="color: white; margin: 13px;"><a href="" data-toggle="modal" data-target="#contactModal">Nous contacter</a></h4>
    </div>
  </a>
  </div>

  <div class="col-md-6 col-lg-3 col-6" data-aos="fade-up" data-aos-delay="300">
    <a href="{{ route('Simple_Post',13019) }}">
    <div class="icon-box d-flex align-items-center" style="background-color: #2626f7; padding: 5px;">
      <img src="{{ asset('assets/images/3.png') }}" alt="" class="icon-image">
      <h4 class="title" style="color: white; margin: 0;"><a href="{{ route('Simple_Post',13019) }}">Questions Fréquentes</a></h4>
    </div>
  </a>
  </div>

  <div class="col-md-6 col-lg-3 col-6" data-aos="fade-up" data-aos-delay="500">
    <a href="">
    <div class="icon-box d-flex align-items-center" style="background-color: green; padding: 5px;">
      <img src="{{ asset('assets/images/4.png') }}" alt="" class="icon-image">
      <h4 class="title" style="color: white; margin: 4px;"><a href="">Je te dis plus tard</a></h4>
    </div>
  </a>
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

                                
            

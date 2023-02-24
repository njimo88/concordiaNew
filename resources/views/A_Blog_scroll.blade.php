
<body>
  
  
<div class="container">
 
 <div class="row">
      <div id="vueParent">
          <div id="posts" next-page-url=" {{ $a_post->nextPageUrl() }}">
          @foreach($a_post as $a_article)

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

                                      {{$a_article->titre}} 

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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



</body>


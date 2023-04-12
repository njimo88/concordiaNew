<style>.post-content {
    max-width: 100%;
    overflow-wrap: break-word;
    word-wrap: break-word;
}
</style>


  
              <div id="posts" next-page-url=" {{ $a_post->nextPageUrl() }}">  <!-- il permet de generer la prochaine page lorsque le script js s'execute -->
              @foreach($a_post as $a_article)
    
              <div class="card shadow mb-4">
                                  <div class="card-header py-2  d-flex justify-content-between">
                                    <div class="col-9">
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
                                    <div class="col-3  d-flex justify-content-end ">
                                        <p>{{$a_article->date_post}}</p>
                                    </div>
                                 
                                  </div>
    
    
    
    
    
                                  <div style="align-items: start !important;" class="card-body post-content">
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
    
    
              
              
              
    
                
    
                  <hr class="my-4">
    
    
                 
    
              
                  
            </div>
            @endforeach      
                 
                
              </div>
          
    
    
    
    
    
    
    
    
    
    
    
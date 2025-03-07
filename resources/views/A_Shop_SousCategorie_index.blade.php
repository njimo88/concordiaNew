@extends('layouts.app')

@section('content')
<style>
  @media (min-width: 1600px) {
  .container {
    max-width: 1500px !important;
  }
}
.breadcrumb-item a {
    color: black;
    font-size: 16px;
    font-family: Arial, sans-serif;
}



</style>
<main id="main" class="main pt-3" class="mt-0" style="background-image: url('{{asset("/assets/images/background.png")}}'); min-height: 100vh;">
  
  @if($messageContent)
      <div style="background-color: #fefefe" class="container mb-3 p-3 border rounded">
          <div class="row">
              <div class="col-12">
                  <!-- Affichez le contenu du champ Message -->
                  {!! $messageContent !!}
              </div>
          </div>
      </div>
      @endif

  <div class="container">
    

    
<section id="portfolio" class="border border-dark portfolio sections-bg row py-3">
      <div class=" container" data-aos="fade-up">
        @php $memory = 0 ; @endphp 
    <nav aria-label="breadcrumb">

          <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="{{route('index_categorie')}}">..</a></li>

            @php  
              $memory = strval($indice) ; 
              $rest1 = substr($memory,0,1);
              $rest2 = substr($memory,0,3);
              $rest3 = substr($memory,0,4);
            @endphp

{{--
  POUR REALISER LA BARE DE NAVIGATION ENTRE Categorie
  ici on compare id shop category avec la valeur des rest reconvertis en int 
      si $rest1 = $rest2 = $rest 3 c-a-d qu'on est sur une categorie principale de (0 - 9 id_shop_categorie) et on affiche le nom de la categorie
      sinon on affichera egalement les autres id_shop_category des sous categories
  --}}
            @foreach($info as $data)

                @if($data->id_shop_category == intval($rest1) and intval($rest1) == intval($rest2) and intval($rest1) == intval($rest3))

                      <li class="breadcrumb-item " aria-current="page"><a href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}">{{$data->name}}</a></li>
                      @elseif(   $data->id_shop_category == intval($rest1) )
                      <li class="breadcrumb-item " aria-current="page"><a href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}">{{$data->name}}</a></li>
                        
                        

                        @elseif(   $data->id_shop_category == intval($rest2) )
                        <li class="breadcrumb-item " aria-current="page"><a href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}">{{$data->name}}</a></li>
                         

                        @elseif(   $data->id_shop_category == intval($rest3) )
                        <li class="breadcrumb-item " aria-current="page"><a href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}">{{$data->name}}</a></li>
                   

                @endif

        
            @endforeach
           
         
          </ol>
          
      </nav>
      <hr>
        
        <div class="widget-title col-12 d-flex justify-content-start align-items-center">
          <span>{{ $info2->name }}</span>
        </div>
        <div class="px-2"><p class="px-3">{!!$info2->description!!}</p></div>

        <div class="portfolio-isotope" data-portfolio-filter="*" data-portfolio-layout="masonry" data-portfolio-sort="original-order" data-aos="fade-up" data-aos-delay="100">



          <div class="row justify-content-center">

          @php  $display_product = TRUE ; @endphp

          @foreach($info as $data)
               @if($data->id_shop_category_parent == $indice)

                        @php      $display_product = FALSE ;   @endphp
            <div class=" col-md-2 mt-2 portfolio-item filter-app ">
              <div class="portfolio-wrap">
             
                <a href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}" ><img style="width: 100% !important;" src="{{ $data->image }}" class="img-fluid" alt=""></a>
                <div class="portfolio-info d-flex justify-content-center">
                  <h5 class="d-flex justify-content-center"><a style="text-align:center !important; color: darkblue;" href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}" title="More Details"> {{ $data->name}}</a></h5>

                
                </div>
              </div>
            </div><!-- End Portfolio Item -->
            @endif

            @endforeach


          </div><!-- End Portfolio Container -->

        </div>

      </div>
    </section><!-- End Portfolio Section -->
  </section>
    
</section>

    </div>
    @if( $display_product == TRUE )
                         <p>  @include('A_Shop_Affichage_Article') </p>
                          
                    @endif
       
     
    
                  </main>

@endsection
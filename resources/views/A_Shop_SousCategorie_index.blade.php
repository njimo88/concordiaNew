@extends('layouts.app')

@section('content')
<main id="main" class="main" class="mt-0" style="background-image: url('{{asset("/assets/images/background.png")}}'); min-height: 100vh;">
  

  <div class="container">
    @if (session('success'))
    <div class="alert alert-success col-12">
        {{ session('success') }}
    </div>
@endif

    
<section style="padding-top:40px !important" id="portfolio" class="border border-dark portfolio sections-bg row ">
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
        <div class="section-header">
          <h2> {{$info2->name}}</h2>
          <p>{!!$info2->description!!}</p>
        </div>

        <div class="portfolio-isotope" data-portfolio-filter="*" data-portfolio-layout="masonry" data-portfolio-sort="original-order" data-aos="fade-up" data-aos-delay="100">



          <div class="row gy-4 portfolio-container">

          @php  $display_product = TRUE ; @endphp

          @foreach($info as $data)
               @if($data->id_shop_category_parent == $indice)

                        @php      $display_product = FALSE ;   @endphp
            <div class="col-xl-4 col-md-4 portfolio-item filter-app">
              <div class="portfolio-wrap">
             
                <a href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}" data-gallery="portfolio-gallery-app" class="glightbox"><img src="../Shop_CSS/img/portfolio/port04.jpg" class="img-fluid" alt=""></a>
                <div class="portfolio-info">
                  <h4><a href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}" title="More Details"> {{ $data->name}}</a></h4>
                
                </div>
              </div>
            </div><!-- End Portfolio Item -->
            @endif

            @endforeach


          </div><!-- End Portfolio Container -->

        </div>

      </div>
    </section><!-- End Portfolio Section -->

    @if( $display_product == TRUE )
                         <p>  @include('A_Shop_Affichage_Article') </p>
                          
                    @endif
       
     
    </section>
    
  </section>

      </div>
    </main>

@endsection
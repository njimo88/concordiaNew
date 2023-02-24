@extends('layouts.app')

@section('content')


  <section id="container" class="p-0">

    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
  

  <!-- ======= Portfolio Section ======= -->
  <section id="portfolio" class="portfolio sections-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Nos catégories</h2>
          <p></p>
        </div>

        <div class="portfolio-isotope" data-portfolio-filter="*" data-portfolio-layout="masonry" data-portfolio-sort="original-order" data-aos="fade-up" data-aos-delay="100">

          <div class="row gy-4 portfolio-container">
          @foreach($info as $data)
            <div class="col-xl-4 col-md-4 portfolio-item filter-app">
              <div class="portfolio-wrap">
             
                <a href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}" data-gallery="portfolio-gallery-app" class="glightbox"><img src="../Shop_CSS/img/portfolio/port04.jpg" class="img-fluid" alt=""></a>
                <div class="portfolio-info">
                  <h4><a href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}" title="More Details"> {{ $data->name}}</a></h4>
                
                </div>
              </div>
            </div><!-- End Portfolio Item -->
   
            @endforeach

          </div><!-- End Portfolio Container -->

        </div>

      </div>
    </section><!-- End Portfolio Section -->

       
     
  </section>
  <!-- js placed at the end of the document so the pages load faster -->
 

@endsection
@extends('layouts.app')

@section('content')


  <section style="padding: 0 !important;" id="container" class="p-0" >

    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
  

  <!-- ======= Portfolio Section ======= -->
  <section id="portfolio" class="portfolio sections-bg" style="background-image: url('{{asset("/assets/images/background.png")}}') !important; padding: 21px 0 !important;">
    <!-- Vérifiez si $messageContent n'est pas null -->
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

<!-- Le reste de votre code HTML pour la vue... -->

      <div class="container border rounded p-5" data-aos="fade-up" style="background-color:#f2f2f2 !important">

        <div class="section-header">
          <h2>Nos catégories</h2>
          <p></p>
        </div>

        <div class=" d-flex justify-content-center" >

          <div class="row gy-4 portfolio-container justify-content-center">
          @foreach($info as $data)
            <div class=" col-md-3   ">
              <div style="background-color:white !important;" class="portfolio-wrap">
             
                <a href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}"  class=" d-flex justify-content-center"><img src="{{ $data->image }}" class="img-fluid" alt=""></a>
                <div class="portfolio-info d-flex justify-content-center">
                  <h5 class="d-flex justify-content-center"><a style="text-align:center !important"  href="{{ route('sous_categorie', ['id' =>  $data->id_shop_category]) }}" title="More Details"> {{ $data->name}}</a></h5>
                
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
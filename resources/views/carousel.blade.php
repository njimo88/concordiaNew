@extends('layouts.app')

@section('content')

<style>

    .slick-next::before, .slick-prev::before {
        color: #482683  !important;
    }
    .top-banner {
        height: 660px;
    }
    .btn-rouge:hover {
        background-color: #d40214 !important;
        color: #fff !important;
    }
    .top-50 {
        top: 40% !important;
    }
    .custom-slider .custom-slide {
            margin: 5px 20px;
        }
    
        .custom-slider .custom-slide img {
            width: 100%;
        }
    
        .custom-slider {
            position: relative;
            display: block;
            box-sizing: border-box;
        }
    
        .custom-slider .custom-list {
            position: relative;
            display: block;
            overflow: hidden;
            margin: 5px !important;
            padding: 5px !important;
        }
    
        .custom-slider .custom-track {
            position: relative;
            top: 0;
            left: 0;
            display: block;
        }
    
        .custom-slider .custom-slide {
            display: none;
            float: left;
            height: 100%;
            min-height: 1px;
        }
    
        .custom-slider .custom-slide img {
            display: block;
        }
    
        .custom-slider .custom-initialized .custom-slide {
            display: block;
        }
    
        .copy {
            padding-top: 250px;
        }

        .h2{
        margin-left: 5rem ;
    }
      
@media (max-width: 993px)  {
    span {
        font-size: 0.8rem !important;
    }
    .h2{
        margin-left: 5px !important;
    }
}

@media (min-width: 993px) and (max-width: 1200px) {
    .top-banner {
        height: 300px;
    }
}

@media (min-width: 1201px) and (max-width: 1700px) {
    .top-banner {
        height: 400px; 
    }
}

@media (min-width: 1701px) {
    .top-banner {
        height: 550px;
    }
}

img[alt="Logo"] {
    width: auto;
    max-width: 100%;
    margin: 5px;
}

@media (max-width: 576px) {
    img[alt="Logo"] {
        width: 500px;
    }
}

@media (min-width: 577px) and (max-width: 768px) {
    img[alt="Logo"] {
        width: 250px;
    }
}

@media (min-width: 769px) and (max-width: 992px) {
    img[alt="Logo"] {
        width: 350px;
    }
}

@media (min-width: 993px) and (max-width: 1200px) {
    img[alt="Logo"] {
        width: 400px;
    }
}

@media (min-width: 1201px) {
    img[alt="Logo"] {
        width: 500px;
    }
}
@media (max-width: 768px) {
    .news-card {
        margin-left: auto   !important; /* ou une valeur spécifique si nécessaire */
        margin-right: auto  !important; /* ou une valeur spécifique si nécessaire */
    }
}


</style>

@if(count($imageUrls) == 1)
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if($errors->has('captcha'))
    <script>
        alert('{{ $errors->first('captcha') }}');
    </script>
@endif

<section style="background-image: url('{{ $imageUrls[0] }}');" class="bg-light position-relative bg-cover top-banner">
    <img loading="lazy" src="{{ asset("/assets/images/gymm.jpg") }}" alt="Blog" title="Blog" class="d-none">
    <div class="dark-overlay"></div>
    <div class="position-absolute top-50 start-50 translate-middle container-xl z-100">
        <div class="d-flex flex-column justify-content-center align-items-center mb-3">
            <img src="{{ asset('assets/images/Logo - Concordia.png') }}" alt="Logo" class="mb-3" width="500">
            <div class="d-flex gap-3">
                <a href="/shop_categories" class="btn-rouge">Boutique</a>
                <a href="/tousLesArticles" class="btn-rouge">Blog en ligne</a>
            </div>
        </div>
    </div>
</section>
@elseif(count($imageUrls) > 1)
<div class="carousel slide top-banner" data-ride="carousel" id="concordiaCarousel">
    <!-- Indicateurs pour le carrousel -->
    <ol class="carousel-indicators">
        @foreach($imageUrls as $index => $imageUrl)
            <li data-target="#concordiaCarousel" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
        @endforeach
    </ol>

    <!-- Contenu du carrousel -->
    <div class="carousel-inner">
        @foreach($imageUrls as $index => $imageUrl)
        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
            <section style="background-image: url('{{ $imageUrl }}');" class="bg-cover top-banner">
                <div class="dark-overlay"></div>
                <div class="position-absolute top-50 start-50 translate-middle container-xl z-100">
                    <div class="d-flex flex-column justify-content-center align-items-center mb-3">
                        <img src="{{ asset('assets/images/Logo - Concordia.png') }}" alt="Logo" class="mb-3" width="500">
                        <div class="d-flex gap-3">
                            <a href="/shop_categories" class="btn-rouge">Boutique</a>
                            <a href="/tousLesArticles" class="btn-rouge">Blog en ligne</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @endforeach
    </div>
  
</div>
@endif

@include('carouselArticles')
@include('modernSection')
@include('shop')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>


<div class="container">
    <h2 class="h2 fw-black h1 text-gym mt-5">Nos Partenaires</h2>
    <section class="custom-slider">
        @foreach ($imageFiles as $image)
            <div class="custom-slide">
                <img src="{{ asset('uploads/Partenaires/' . $image->getFilename()) }}" alt="logo">
            </div>
        @endforeach
    </section>
</div>

<script>
    $(document).ready(function() {
        $('.custom-slider').slick({
            slidesToShow: 6, 
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 1500,
            arrows: false,
            dots: false,
            pauseOnHover: false,
            responsive: [
                {
                    breakpoint: 768, 
                    settings: {
                        slidesToShow: 1 
                    }
                },
                {
                    breakpoint: 520, 
                    settings: {
                        slidesToShow: 1 
                    }
                }
            ]
        });
    });
</script>


@endsection

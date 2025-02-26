@extends('layouts.app')

@section('content')
    <style>
        .slick-next::before,
        .slick-prev::before {
            color: #482683 !important;
        }

        .top-banner {
            height: 350px;
        }

        .btn-rouge:hover {
            background-color: #d40214 !important;
            color: #fff !important;
        }

        .top-50 {
            top: 40% !important;
        }

        .custom-slider .custom-slide {
            margin: 5px 10px;
        }

        .custom-slider .custom-slide img {
            /* width: 100%; */
            height: 150px;
            align: center;
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

        .h2 {
            margin-left: 5rem;
        }

        @media (max-width: 993px) {
            .top-banner {
                height: 300px;
            }

            span {
                font-size: 0.8rem !important;
            }

            .h2 {
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
                height: 325px;
            }
        }

        @media (min-width: 1701px) {
            .top-banner {
                height: 350px;
            }
        }

        img[alt="Logo"] {
            width: auto;
            max-width: 100%;
            margin: 5px;
        }

        @media (max-width: 576px) {
            .custom-slider .custom-slide img {
                height: 300px;
            }

            img[alt="Logo"] {
                width: 300px;
            }
        }

        @media (min-width: 577px) and (max-width: 768px) {
            .custom-slider .custom-slide img {
                height: 300px;
            }

            img[alt="Logo"] {
                width: 220px;
            }
        }

        @media (min-width: 769px) and (max-width: 992px) {
            .custom-slider .custom-slide img {
                height: 100px;
            }

            img[alt="Logo"] {
                width: 260px;
            }
        }

        @media (min-width: 993px) and (max-width: 1200px) {
            .custom-slider .custom-slide img {
                height: 120px;
            }

            img[alt="Logo"] {
                width: 300px;
            }
        }

        @media (min-width: 1201px) {
            .custom-slider .custom-slide img {
                height: 150px;
            }

            img[alt="Logo"] {
                width: 400px;
            }
        }

        @media (max-width: 768px) {
            .news-card {
                margin-left: auto !important;
                /* ou une valeur spécifique si nécessaire */
                margin-right: auto !important;
                /* ou une valeur spécifique si nécessaire */
            }
        }

        .btn-carousel-ctrl:hover {
            background-color: unset;
            box-shadow: none;
        }

        /* .img-carousel {
                    max-height: 420px;
                    height: 100%;
                    width: 100%;
                    object-fit: cover;
                } */

        .img-carousel {
            max-height: 420px;
            height: 100%;
            width: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
        }

        .carousel {
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }


        /* Ajustement pour les écrans plus petits */
        @media (max-width: 1250px) {
            .img-carousel {
                height: 100%;
                width: 100%;
                object-fit: cover;
            }
        }
    </style>

    @if (count($imageUrls) == 1)
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($errors->has('captcha'))
            <script>
                alert(
                    '{{ $errors->first('
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                captcha ') }}'
                );
            </script>
        @endif

        <a href="{{ $imageUrls[0]->click_link }}" target="_blank">
            <img src="{{ $imageUrls[0]->image_link }}" alt="" class="d-block img-carousel">
        </a>
    @elseif(count($imageUrls) > 1)
        <div class="carousel slide" data-bs-ride="carousel" id="concordiaCarousel">
            <!-- Contenu du carrousel -->
            <div class="carousel-inner">
                @foreach ($imageUrls as $index => $imageUrl)
                    <div class="carousel-item {{ $imageUrl->image_order == 1 ? 'active' : '' }}"
                        style="background-color: #63c3d1 !important" data-bs-interval="3000">
                        <a href="{{ $imageUrl->click_link }}" {{ $imageUrl->click_link !== '#' ? 'target="_blank"' : '' }}>
                            <img src="{{ $imageUrl->image_link }}" alt="" class="d-block img-carousel">
                        </a>
                    </div>
                @endforeach
                <button class="carousel-control-prev btn-carousel-ctrl" type="button" data-bs-target="#concordiaCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next btn-carousel-ctrl" type="button" data-bs-target="#concordiaCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    @endif

    @include('carouselArticles')
    @include('modernSection')
    @include('shop')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
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
                responsive: [{
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

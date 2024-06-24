
<style>
    .card{
        border: solid 1px gray;
        border-radius: 2px;
    }
    .carousel-img {
    height: 300px;       
    width: 80%;  
    object-fit: cover;  
    display: block; 
    margin-top: 15%;
    margin-left: 10%;  
    margin-right: 10%;  
}


.col-md-3 {
    margin: 0 7px;  
}

    
    .carousel .card {
        display: flex;
        flex-direction: column;  
    }

    .carousel .card-body {
        flex-grow: 1;  
        background-color: #ffffff;  
    }

    /* Changer les couleurs */
    {
        background-color: white;  
    }

    .card-title ,.card-price
     {
        background-color: white;  
        color: #482683 !important;;  
    }
    .card-text{
        color: #222222 !important;;
    }
    .carousel-indicators .active {
        background-color: #007BFF;
    }

    .card-body {
        min-height: 230px !important;
        background-color: #ffffff !important;
    }

     .carousel-control-prev, .carousel-control-next {
        z-index: 10;  
        top: 50%;  
        transform: translateY(-50%); 
    }

    
    .card-price {
        font-size: 1.25rem;
        font-weight: bold;
        margin-top: 10px;
    }
    
    .slick-dots li.slick-active button:before {
  background: #482683 !important;
}

@media (max-width: 768px) {
    .col-md-3 {
        max-width: 80%; 
        margin: 0 auto; 
    }

    .carousel-img {
        height: 400px; 
    }

    .card-title {
        font-size: 0.9rem;
    }

    
}



</style>

<section class="position-relative d-block container-fluid bg-primary text-gym py-5 slice-news-testimony">
    <div class="container-xxl">
        <div class="row d-flex justify-content-center reveal reveal-visible">
            <div class="col-xl-11 p-0 col-heading">
                <div class="d-flex align-items-center justify-content-between px-md-5 mb-2">
                    <h1 class="fw-black h1 text-white">Boutique</h1>
                    <a href="/shop_categories" class="fw-bold ms-1 text-end d-flex align-items-center text-white">
                        <span class="span">Voir tous les Articles</span>
                        <i class="fa-solid fa-circle-plus text-white"></i>
                    </a>
                </div>
                <div id="articleCarousel" class="slick-shop">
                    @foreach($shop_articles as $article)
                        <div class="col-md-3 col-10">
                            <a target="_blank" href="{{ route('singleProduct', ['id' => $article->id_shop_article]) }}" class="text-decoration-none">
                                <div class="card">
                                    <img src="{{ $article->image }}" alt="{{ $article->title }}" class="carousel-img card-img-top">
                                    <div class="card-body">
                                        <h5 class="card-title">{!! $article->title !!}</h5>
                                        <p class="card-text">{!! Str::limit(strip_tags($article->short_description), 90, '...') !!}</p>
                                        @include('availability', ['data' => $article])
                                        <div class="card-price">{{ number_format($article->price, 2) }} €</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(){
    $('#articleCarousel').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        centerPadding: '20px',  
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true,
                    centerPadding: '10px',  
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,  
                    slidesToScroll: 1,
                    centerPadding: '10px'
                }
            }
        ]
    });
});

</script>


@extends('layouts.app')

@section('content')
<style>
    .slick-dots li button:before {
        background: #63c3d1 !important;
    }
    .slick-dots li.slick-active button:before {
  background: #482683 !important;
}
</style>
@php
    preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $posts->contenu, $image);
    $firstImage = !empty($image['src']) ? $image['src'] : 'default-image.jpg';
    setlocale(LC_TIME, 'french');
    $date = \Carbon\Carbon::parse($posts->date_post);
    $formatted = strftime('%A %d %B %Y à %H:%M:%S', $date->timestamp);
    if ($date->format('H:i:s') === '00:00:00') {
        $formatted = strftime('%A %d %B %Y', $date->timestamp);
    }
    // Enlever la premiere image du contenu
    $dom = new \DOMDocument();
    libxml_use_internal_errors(true);  // Désactiver les erreurs de libxml
    $dom->loadHTML(mb_convert_encoding($posts->contenu, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();  // Nettoyer les erreurs de libxml

    // Trouver la première balise <img>
    $image = $dom->getElementsByTagName('img')->item(0);
    if ($image) {
        // Supprimer la première balise <img>
        $image->parentNode->removeChild($image);
    }

    $content = $dom->saveHTML();
@endphp
<section class="container-fluid bg-danger ps-4 py-4">
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <p class="fw-bold text-white mb-0 link-white">
                    <i class="far fa-lg fa-newspaper me-2 me-md-3 text-white"></i>
                    <a href="/blog/{{ $posts->id_blog_post_primaire }}" rel="category tag">Blog</a> &nbsp;&nbsp;/&nbsp;&nbsp; 
                    <a href="/blog/{{ $posts->id_blog_post_primaire }}" rel="category tag">{{ $posts->titre }}</a> &nbsp;&nbsp;/&nbsp;&nbsp; 
                    @foreach($posts->categories as $category)
                        <a href="" rel="category tag">{{ $category->nom_categorie }}</a> @if (!$loop->last)&nbsp;&nbsp;|&nbsp;&nbsp;@endif
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</section>
<section class="container-fluid bg-light-gray">
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="position-relative">
                    <div class="slick slick-main slick-initialized slick-slider" data-slick-opt="{&quot;infinite&quot;:true,&quot;slidesToShow&quot;:1,&quot;slidesToScroll&quot;:1,&quot;nextArrow&quot;:&quot;.slick-nav-single.next&quot;,&quot;prevArrow&quot;:&quot;.slick-nav-single.prev&quot;,&quot;dots&quot;:false,&quot;swipeToSlide&quot;:true,&quot;touchMove&quot;:true,&quot;draggable&quot;:true}">
                        <div class="slick-list draggable">
                            <div class="slick-track" style="opacity: 1; width: 856px; transform: translate3d(0px, 0px, 0px);">
                                <div class="slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 856px;">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-center" style="width: 100%; display: inline-block;">
                                            <a data-fancybox="" href="{{ $firstImage }}" tabindex="0">
                                                <img src="{{ $firstImage }}" alt="" class="m-0 img-fluid">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 end-0">
                        <button type="button" class="slick-nav-single prev slick-arrow slick-hidden" aria-disabled="true" tabindex="-1">
                            <i class="fas fa-angle-left fa-2x p-1 text-white"></i>
                        </button>
                        <button type="button" class="slick-nav-single next slick-arrow slick-hidden" aria-disabled="true" tabindex="-1">
                            <i class="fas fa-angle-right fa-2x p-1 text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="container-fluid bg-white py-4">
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="content py-3">
                    {!! $content !!}
                </div>
            </div>
        </div>
    </div>
</section>
<section class="container-fluid bg-secondary-gym text-white p-3">
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10 d-flex align-items-center">
                <i class="far fa-calendar-alt me-3"></i>
                <p class="fw-bold mb-0">
                    Publié le {{ $formatted }}
                </p>
            </div>
        </div>
    </div>
</section>

<section class="position-relative d-block container-fluid bg-white text-white  py-5  slice-news-testimony" >
    <div class="container-xxl">
        <div class="row d-flex justify-content-center reveal reveal-visible">
            <div class="col-xl-11 p-0 col-heading">
                <div class="d-flex align-items-center justify-content-between px-md-5 mb-2">
                    <h1 class=" fw-black h1 text-gym">Articles</h1>
                    <a href="/tousLesArticles" class="fw-bold ms-1 text-end  d-flex align-items-center text-gym">
                        <span class="span ">Voir tout les Articles</span>
                        <i class="fa-solid fa-circle-plus text-gym"></i>
                    </a>
                </div>
                <div class="slider">
                    @foreach ($carouselle as $post)
                        <div> <!-- Chaque div ici est une diapositive du carrousel -->
                            <div class="d-flex justify-content-center h-100" style="width: 100%; display: inline-block;">
                                <div style="box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.4);" class="d-flex flex-column justify-content-between h-100 mx-2 bg-white text-body news-card">
                                    <div>
                                        @php
                                            preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $post->contenu, $image);
                                            $firstImage = !empty($image['src']) ? $image['src'] : 'default-image.jpg';
                                        @endphp             
                                        <a href="/blog/{{ $post->id_blog_post_primaire }}">
                                            <div style="background-image: url('{{ $firstImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;" class="bg-cover news-img"></div>
                                        </a>

                                        <div class="px-3 pt-3">
                                            <small class="d-inline-block fw-bold text-danger mb-3">
                                                <span class="fw-black">{{ \Carbon\Carbon::parse($post->date_post)->format('Y-m-d') }}</span>
                                                @if($post->categories)
                                                @foreach($post->categories as $category)
                                                    <span> | {{ $category->nom_categorie }}</span>
                                                @endforeach
                                            @endif

                                            </small>

                                            <a href="/blog/{{ $post->id_blog_post_primaire }}">
                                                <h5 class="fw-black text-danger news-title">
                                                    {{ $post->titre }}
                                                </h5>
                                            </a>

                                            <p>{!! Str::limit(strip_tags($post->contenu), 250, '...') !!}</p>
                                        </div>
                                    </div>

                                    <a href="/blog/{{ $post->id_blog_post_primaire }}" class="px-3 pb-3 fw-bold text-danger read-more-link">
                                        Lire la suite
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('.slider').slick({ // Target the correct class
            dots: true,
            infinite: false,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
</script>
@endsection
<section class="position-relative d-block container-fluid bg-primary text-white  py-5  slice-news-testimony" >
    <div class="container-xxl">
        <div class="row d-flex justify-content-center reveal reveal-visible">
            <div class="col-xl-11 p-0 col-heading">
                <div class="d-flex align-items-center justify-content-between px-md-5 mb-2">
                    <h1 class=" fw-black h1">Articles</h1>
                    <a href="/tousLesArticles" class="fw-bold ms-1 text-white text-end plus-link d-flex align-items-center">
                        <span class="span">Voir tous les Articles</span>
                        <i class="fa-solid fa-circle-plus" style="color: #ffffff;"></i>
                      </a>
                      
                </div>
                <div class="slider">
                    @foreach ($posts as $post)
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
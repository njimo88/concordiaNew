@extends('layouts.app')

@section('content')
<style>
    .page-link {
    color: #482683;
    background-color: #fff;
    border: 1px solid #482683;
}

.page-link:hover {
    color: #fff;
    background-color: #482683;
    border-color: #482683;
}

.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #482683;
    border-color: #482683;
}


.top-cta {
            display: none;
            z-index: 150;
            position: fixed;
            right: 1.5rem;
            bottom: 30px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            color: #fff;
            background-color: #482683;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, background-color 0.2s;
        }

        .top-cta:hover {
            transform: translateY(-5px);
            background-color: #d40214;
            color: #fff !important;
        }

        .top-cta i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
        }
</style>

<section style="background-image: url('https://static-cse.canva.com/blob/558907/blogtraffic.jpg');" class=" bg-light position-relative bg-cover top-banner-page">
        <img loading="lazy" src="{{ asset("/assets/images/gymm.jpg") }}" alt="Blog" title="Blog" class="d-none">
        <div class="dark-overlay"></div>
        <div class="position-absolute bottom-0 start-50 translate-middle-x container-xl z-100">
            <div class="d-flex flex-column justify-content-end mb-3">
                <h1 class="h2 fw-black text-white">Blog</h1>
            </div>
        </div>
</section>

<section class="bg-light">
    <button type="button" class="top-cta">
        <i class="fas fa-chevron-up"></i>
    </button>
<div class="container">
    <div class="row">
        <h3 class="text-danger fw-black mb-3">{{ $posts->total() }} articles trouvés </h3>
        @foreach ($posts as $post)
            <div class="col-md-4 my-4" data-aos="fade-up">
                <div class="d-flex justify-content-center h-100" style="width: 100%; display: inline-block;">
                    <div style="box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.4);" class="d-flex flex-column justify-content-between h-100 mx-2 bg-white text-body news-card">
                        <div>
                            @php
                                preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $post->contenu, $image);
                                $firstImage = !empty($image['src']) ? $image['src'] : 'default-image.jpg';
                            @endphp             
                            <a href="/blog/{{ $post->id_blog_post_primaire }}" target="_blank">
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
        <div class="mt-5 d-flex justify-content-center" style="align-self: center">
            <!-- Pagination -->
            {{ $posts->links() }}
        </div>
    </div>
    
    
</div>
</section>
<script>
    AOS.init({
        offset: 200, 
        delay: 0, 
        duration: 500 
    });
    

  </script>


@endsection
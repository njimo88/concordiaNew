@extends('layouts.app')

@section('content')
<!-- ======= Header ======= -->
<header id="header" class="header d-flex align-items-center">

  <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
    @guest
      <a href="{{ route('A_blog') }}" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>Gym<span>Concordia</span></h1>
      </a>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="{{ route('A_blog') }}">Accueil</a></li>
          <li class="dropdown"><a href="#"><span>Le Club</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a href="#">Le Club</a></li>
              <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 2</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>Achats</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a href="#">Drop Down 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 2</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
            </ul>
          </li>
          
          <li><a href="#contact">Recherche</a></li>
          @if (Route::has('login'))
            <li><a href="{{ route('login') }}">Connectez-vous<i style="color: white; font-size:19px !important;" class='bx bxs-user'></i></a></li>
          @endif
          @if (Route::has('register'))
            <li><a  href="{{ route('register') }}">Inscrivez-vous</a></li>
          @endif 
        </ul>
      </nav><!-- .navbar -->

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
    @else
      <a href="{{ route('A_blog') }}" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>Gym<span>Concordia</span></h1>
      </a>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="{{ route('A_blog') }}">Accueil</a></li>
          <li class="dropdown"><a href="#"><span>Le Club</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a href="#">Le Club</a></li>
              <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 2</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>Achats</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a href="#">Drop Down 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 2</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
            </ul>
          </li>
          
          <li><a href="#contact">Recherche</a></li>
          <li class="dropdown"><a href="#"><span>Djilly N</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li><a href="{{ route('logout') }}"onclick="event.preventDefault();document.getElementById('logout-form').submit();">Déconnecter</a></li>
              <li><a href="{{ route('users.edit-profil') }}">Mon Profil</a></li>
              <li><a href="{{ route('users.family') }}">Ma famille</a></li>
              <li><a href="{{ route('admin.index') }}">Administration</a></li>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </ul>
          </li>
        </ul>
      </nav><!-- .navbar -->

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
    @endguest
  </div>
</header>


<!-- ======= Hero Section ======= -->
<section id="hero" class="hero">
  <div class="container position-relative">
    <div class="row gy-5" data-aos="fade-in">
      <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start">
        <h2>Welcome to <span>Impact</span></h2>
        <p>Sed autem laudantium dolores. Voluptatem itaque ea consequatur eveniet. Eum quas beatae cumque eum quaerat.</p>
        <div class="d-flex justify-content-center justify-content-lg-start">
          <a href="#about" class="btn-get-started">Get Started</a>
          <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Watch Video</span></a>
        </div>
      </div>
      <div class="col-lg-5 order-1 order-lg-2">
        <img src="{{ asset('assets\images\gym.svg') }}" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="100">
      </div>
    </div>
  </div>

  <div class="icon-boxes position-relative">
    <div class="container position-relative">
      <div class="row gy-4 mt-5">

        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="icon-box">
            <div class="icon"><i class="bi bi-easel"></i></div>
            <h4 class="title"><a href="" class="stretched-link">Lorem Ipsum</a></h4>
          </div>
        </div>
        <!--End Icon Box -->

        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
          <div class="icon-box">
            <div class="icon"><i class="bi bi-gem"></i></div>
            <h4 class="title"><a href="" class="stretched-link">Sed ut perspiciatis</a></h4>
          </div>
        </div>
        <!--End Icon Box -->

        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
          <div class="icon-box">
            <div class="icon"><i class="bi bi-geo-alt"></i></div>
            <h4 class="title"><a href="" class="stretched-link">Magni Dolores</a></h4>
          </div>
        </div>
        <!--End Icon Box -->

        <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="500">
          <div class="icon-box">
            <div class="icon"><i class="bi bi-command"></i></div>
            <h4 class="title"><a href="" class="stretched-link">Nemo Enim</a></h4>
          </div>
        </div>
        <!--End Icon Box -->

      </div>
    </div>
  </div>

  </div>
</section>
@endsection
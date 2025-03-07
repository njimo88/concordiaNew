<?php
use Illuminate\Support\Facades\DB;
use App\Models\Shop_category;
use Illuminate\Support\Facades\Route;
?>
@if (!in_array(Route::currentRouteName(), ['register', 'login']))
    <!-- ======= Header ======= -->
    @php
        $categories = Shop_category::select('id_shop_category', 'name', 'image')->where('active', 1)->get();

    @endphp
    @if (session()->has('success'))
        {{-- <script>
                          // create temporary div
                          var tempDiv = document.createElement('div');
                          tempDiv.id = 'temp-div';

                          // add message to div
                          tempDiv.innerHTML =  'votre message a été envoyé avec succès!';
                        
                          // add div to page
                          document.body.appendChild(tempDiv);
                          setTimeout(function() {
                                                  document.body.removeChild(tempDiv);
                                            }, 5000); // remove div after 5 seconds

                        
                  </script> --}}
    @endif

    <style>
        #temp-div {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            border: 1px solid #cccccc;
            box-shadow: 0px 0px 10px #cccccc;
            padding: 20px;
        }

        .logo-container {
            position: relative;
            width: 200px;
            height: 100px;
        }

        .logo-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            z-index: 1;
        }

        .logo-container img:last-child {
            z-index: 2;
        }
    </style>
    <!-- Search Modal -->
    <div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="search-modal-label"
        aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background-color: #f2f2f2; outline: 0 none;">
                <div class="modal-header d-flex justify-content-center">
                    <div class="col-lg-11">
                        <select id="search-type" name="search-type" class="form-control custom-select-with-icons"
                            style="text-align-last:center;">
                            <option value="blog" selected="">Rechercher un article du blog</option>
                            <option value="shop">Rechercher un article de la boutique</option>
                        </select>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12" style="padding:5px">
                            <form id="search-form" class="searchForm" style=" margin: 0 auto" autocomplete="off">
                                <input type="text" id="search-query" name="search-query" class="form-control"
                                    placeholder="Rechercher un article...">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                        <div id="search-results" style="margin:0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="contactModal" tabindex="-1" style="z-index:100000" data-backdrop="static"
        data-keyboard="false" aria-labelledby="contactModalLabel" aria-hidden="true" data-target="#myModal">




        <div class="modal-dialog  modal-lg">
            <div style="background-color: #d8e3ff" class="modal-content">
                <div class="modal-header border m-4 mb-0" style="background-color: white;">
                    <h5 class="modal-title" id="contactModalLabel"
                        style=" font-size: 1.5em;font-weight: 400; font-family: Arial, Helvetica, sans-serif">Envoyer un
                        Message</h5>
                    <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('traitement_prendre_contact') }}" id="sendMailForm" method="post"
                    class="form-example">
                    @csrf
                    <div class="modal-body p-5 py-3">
                        <fieldset class="form-group">
                            <div class="form cf form-group row">

                                <div class="plan cf form-group col-sm-10">

                                    <input type="radio" name="send_me" id="bureau" value="1">
                                    <label class="bureau-label four col" for="bureau">
                                        Bureau
                                    </label>

                                    <input type="radio" name="send_me" id="tresorier" value="2">
                                    <label class="tresorier-label four col" for="tresorier">
                                        Trésorier
                                    </label>

                                    <input type="radio"name="send_me" id="president" value="3">
                                    <label class="president-label four col" for="president">
                                        Président
                                    </label>

                                </div>
                            </div>
                        </fieldset>
                        <div class="form-row row mt-5">

                            @if (!auth()->user())
                                <div class="form-group col-md-5">
                                    <label class="text-dark" for="name">&nbsp;Prénom - Nom</label>
                                    <input placeholder="Prénom - Nom" class="form-control mt-2" type="text"
                                        name="name" value="" required>
                                </div>
                                <div class="form-group col-md-7">
                                    <label class="text-dark" for="email">&nbsp;Email</label>
                                    <input placeholder="Email" class="form-control mt-2" type="email" name="email"
                                        value="" required>
                                </div>
                            @else
                                <div class="form-group col-md-5">
                                    <label class="text-dark" for="name">&nbsp;Prénom - Nom</label>
                                    <input placeholder="Prénom - Nom" class="form-control mt-2" type="text"
                                        name="name"
                                        value="{{ auth()->user()->lastname }} {{ auth()->user()->name }} " required
                                        readonly style="background-color: grey; color:#fff">
                                </div>

                                <div class="form-group col-md-7">
                                    <label class="text-dark" for="email">&nbsp;Email</label>
                                    <input placeholder="Email" class="form-control mt-2" type="email"
                                        name="email" value="{{ auth()->user()->email }}" required readonly
                                        style="background-color: grey; color:#ffffff">
                                </div>
                            @endif
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="text-dark" for="message">&nbsp;Votre Message</label>
                                <textarea style="width: 100%; height: 150px;" placeholder="Votre message" class="form-control mt-2" required
                                    name="message" value=""></textarea>
                            </div>
                        </div>
                        <div class='form-row mt-3'>
                            <div class='form-group col-md-12' style="text-align:-webkit-center">
                                <div class='g-recaptcha' name="captchaTest"
                                    data-sitekey='6Lf8zLIoAAAAAJtjcI7Xi6Lo5v07zwS6bnmCXS1g'></div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-between m-1 mt-4">
                            <div class="col-3 d-flex justify-content-center">
                                <button type="button" class="cancel btn btn-secondary"
                                    data-dismiss="modal">Annuler</button>
                            </div>
                            <div class="col-3 d-flex justify-content-center">
                                <button type="submit" class="submit btn btn-primary">Envoyer</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <header id="header" class="header d-flex align-items-center">

        <div
            class="container-fluid container-xl d-flex align-items-center justify-content-between justify-content-lg-center">

            @guest

                <a href="{{ route('A_blog') }}" class="logo d-flex align-items-center">
                    <div class="logo-container">
                        <img src="{{ asset('assets\images\LogoHB.png') }}" alt="">
                        <img src="{{ asset('assets\images\NumTel-960px.png') }}" style="display:none;" alt="">
                    </div>
                </a>



                <nav id="navbar" class="navbar">
                    <ul style="order: 2">
                        @if (Route::has('login'))
                            <li style="order: -1;"><a href="{{ route('login') }}"
                                    class="btn btn-danger text-white mx-2 btn-arrow btn-hover-negatif">
                                    Connexion <i style="color: white; font-size:19px !important;" class='bx bxs-user'></i>
                                </a></li>
                        @endif
                        <li><a href="{{ route('A_blog') }}"><span>&nbsp;Accueil</span></a></li>
                        <li class="dropdown"><a href="#"><span>&nbsp;Le Club</span> <i
                                    class="bi bi-chevron-down dropdown-indicator"></i></a>
                            <ul>
                                <li class="">
                                    <a href="{{ route('Simple_Post', 13792) }}"><span>&nbsp;Organisation</span></a>
                                </li>
                                <li class="">
                                    <a href="{{ route('Simple_Post', 13002) }}"><span>&nbsp;Partenaires</span></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#"><span>&nbsp;Communication</span> <i
                                            class="bi bi-chevron-down dropdown-indicator"></i></a>
                                    <ul>
                                        <li><a target="_blank" href="https://www.facebook.com/GymConcordia"><span><i
                                                        class="bi bi-facebook"></i>&nbsp;&nbsp;Facebook</span></a></li>
                                        <li><a target="_blank" href="https://www.instagram.com/gym_concordia"><span><i
                                                        class="bi bi-instagram"></i>&nbsp;&nbsp;Instagram</span></a></li>
                                        <li><a target="_blank" href="https://www.youtube.com/user/GymConcordia"><span><i
                                                        class="bi bi-youtube"></i>&nbsp;&nbsp;Youtube</span></a></li>
                                        <li><a target="_blank"
                                                href="https://www.tiktok.com/discover/gym-concordia-schiltigheim?lang=fr"><span><i
                                                        class="bi bi-tiktok"></i>&nbsp;&nbsp;TikTok</span></a></li>
                                    </ul>

                                </li>
                                <li class="">
                                    <a href="{{ route('Simple_Post', 13019) }}"><span>&nbsp;Questions Fréquentes</span></a>
                                </li>
                                <li class="">
                                    <a href="{{ route('index_mentions_legales') }}"><span>&nbsp;Mentions
                                            Légales</span></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="liens" data-toggle="modal"
                                        data-target="#contactModal"><span>&nbsp;Prendre contact</span></a>
                                </li>

                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="{{ route('index_categorie') }}">
                                <span>&nbsp;Achats</span>
                                @if (
                                    $categories->filter(function ($category) {
                                            return strlen($category->id_shop_category) === 1;
                                        })->count() > 0)
                                    <i class="bi bi-chevron-down dropdown-indicator"></i>
                                @endif
                            </a>
                            <ul>
                                @foreach ($categories->filter(function ($category) {
            return strlen($category->id_shop_category) === 1;
        }) as $category)
                                    @php
                                        $subCategoryCount = $categories
                                            ->filter(function ($subCategory) use ($category) {
                                                return strlen($subCategory->id_shop_category) === 3 &&
                                                    strpos(
                                                        $subCategory->id_shop_category,
                                                        $category->id_shop_category,
                                                    ) === 0;
                                            })
                                            ->count();
                                    @endphp

                                    <li class="{{ $subCategoryCount > 0 ? 'dropdown' : '' }}">
                                        <a href="{{ route('sous_categorie', ['id' => $category->id_shop_category]) }}">
                                            <span><img src="{{ $category->image }}"
                                                    width="24">&nbsp;{{ $category->name }}</span>
                                            @if ($subCategoryCount > 0)
                                                <i class="bi bi-chevron-down dropdown-indicator"></i>
                                            @endif
                                        </a>
                                        <!-- Second level dropdown: categories with three digits id -->
                                        @if ($subCategoryCount > 0)
                                            <ul>
                                                @foreach ($categories->filter(function ($subCategory) use ($category) {
            return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
        }) as $subCategory)
                                                    @php
                                                        $subSubCategoryCount = $categories
                                                            ->filter(function ($subSubCategory) use ($subCategory) {
                                                                return strlen($subSubCategory->id_shop_category) ===
                                                                    4 &&
                                                                    strpos(
                                                                        $subSubCategory->id_shop_category,
                                                                        $subCategory->id_shop_category,
                                                                    ) === 0;
                                                            })
                                                            ->count();
                                                    @endphp

                                                    <li class="{{ $subSubCategoryCount > 0 ? 'dropdown' : '' }}">
                                                        <a
                                                            href="{{ route('sous_categorie', ['id' => $subCategory->id_shop_category]) }}">
                                                            <span><img src="{{ $subCategory->image }}"
                                                                    width="24">&nbsp;{{ $subCategory->name }}</span>
                                                            @if ($subSubCategoryCount > 0)
                                                                <i class="bi bi-chevron-down dropdown-indicator"></i>
                                                            @endif
                                                        </a>
                                                        <!-- Third level dropdown: categories with four digits id -->
                                                        @if ($subSubCategoryCount > 0)
                                                            <ul>
                                                                @foreach ($categories->filter(function ($subSubCategory) use ($subCategory) {
            return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
        }) as $subSubCategory)
                                                                    <li>
                                                                        <a
                                                                            href="{{ route('sous_categorie', ['id' => $subSubCategory->id_shop_category]) }}">
                                                                            <span><img src="{{ $subSubCategory->image }}"
                                                                                    width="24">&nbsp;{{ $subSubCategory->name }}</span>
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                        </li>

                        <li class="dropdown"><a href="#"><span>&nbsp;Nos Activités</span> <i
                                    class="bi bi-chevron-down dropdown-indicator"></i></a>
                            <ul>
                                <li class="dropdown"><a href="#"><span>&nbsp;Nos Sections&nbsp;<i
                                                class="bi bi-chevron-down dropdown-indicator"></i></span></a>
                                    <!-- First level dropdown: categories with one digit id -->
                                    @php
                                        $category = Shop_category::select('id_shop_category', 'name', 'image')
                                            ->where('id_shop_category', 1)
                                            ->first();
                                    @endphp

                                    <ul>
                                        @foreach ($categories->filter(function ($subCategory) use ($category) {
            return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
        }) as $subCategory)
                                            <li class="{{ $subCategoryCount > 0 ? 'dropdown' : '' }}">
                                                <a
                                                    href="{{ route('sous_categorie', ['id' => $subCategory->id_shop_category]) }}">
                                                    <span><img src="{{ $subCategory->image }}"
                                                            width="24">&nbsp;{{ $subCategory->name }}</span>
                                                    @if (
                                                        $categories->filter(function ($subSubCategory) use ($subCategory) {
                                                                return strlen($subSubCategory->id_shop_category) === 4 &&
                                                                    strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
                                                            })->count() > 0)
                                                        <i class="bi bi-chevron-down dropdown-indicator"></i>
                                                    @endif
                                                </a>
                                                <!-- Third level dropdown: categories with four digits id -->
                                                <ul>
                                                    @foreach ($categories->filter(function ($subSubCategory) use ($subCategory) {
            return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
        }) as $subSubCategory)
                                                        <li>
                                                            <a
                                                                href="{{ route('sous_categorie', ['id' => $subSubCategory->id_shop_category]) }}">
                                                                <span><img src="{{ $subSubCategory->image }}"
                                                                        width="24">&nbsp;{{ $subSubCategory->name }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li><a href="{{ route('Simple_Post', 13224) }}"><span>&nbsp;Sport-Etudes</span></a>
                                <li><a href="{{ route('Simple_Post', 13005) }}"><span>&nbsp;Horaires Bureau</span></a>
                            </ul>
                        </li>
                        <li><a href="#" data-toggle="modal"
                                data-target="#search-modal"><span>&nbsp;Recherche</span></a></li>

                    </ul>
                </nav><!-- .navbar -->

                <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
                <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
            @else
                <a href="{{ route('A_blog') }}" class="logo d-flex align-items-center">
                    <img style="max-width: 200px !important" src="{{ asset('assets\images\LogoHB.png') }}"
                        alt="">
                </a>
                <nav id="navbar" class="navbar">
                    <ul>
                        <li><a href="{{ route('A_blog') }}"><span>&nbsp;Accueil</span></a></li>
                        <li class="dropdown">
                            <a href="#">
                                @if (auth()->user()->image)
                                    {{-- Check for user image --}}
                                    <img src="{{ auth()->user()->image }}" class="rounded-circle"
                                        alt="{{ auth()->user()->name }}">
                                @elseif(auth()->user()->gender == 'male')
                                    {{-- Check for male gender --}}
                                    <img src="{{ asset('assets/images/user.jpg') }}" class="rounded-circle"
                                        alt="{{ auth()->user()->name }}">
                                @else
                                    {{-- Assuming female for any other case --}}
                                    <img src="{{ asset('assets/images/femaleuser.png') }}" class="rounded-circle"
                                        alt="{{ auth()->user()->name }}">
                                @endif
                                <span>{{ auth()->user()->lastname }} {{ auth()->user()->name }}</span>
                                <i class="bi bi-chevron-down dropdown-indicator"></i>
                            </a>

                            <ul class="user-options">
                                @if (auth()->user()->role >= 20)
                                    <li><a href="{{ route('admin.index') }}"><span>&nbsp;Administration</span></a></li>
                                @endif

                                <li><a href="{{ route('users.family') }}"><span>&nbsp;Ma Famille</span></a></li>
                                <li><a href="{{ route('users.FactureUser') }}"><span>&nbsp;Mes Factures/Devis</span></a>
                                </li>
                                <li><a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();document.getElementById('logout-form').submit();"><span>&nbsp;Déconnecter</span></a>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#"><span>&nbsp;Le Club</span> <i
                                    class="bi bi-chevron-down dropdown-indicator"></i></a>
                            <ul>
                                <li class="">
                                    <a href="{{ route('Simple_Post', 13792) }}"><span>&nbsp;Organisation</span></a>
                                </li>
                                <li class="">
                                    <a href="{{ route('Simple_Post', 13002) }}"><span>&nbsp;Partenaires</span></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#"><span>&nbsp;Communication</span> <i
                                            class="bi bi-chevron-down dropdown-indicator"></i></a>
                                    <ul>
                                        <li><a target="_blank" href="https://www.facebook.com/GymConcordia"><span><i
                                                        class="bi bi-facebook"></i>&nbsp;&nbsp;Facebook</span></a></li>
                                        <li><a target="_blank" href="https://www.instagram.com/gym_concordia"><span><i
                                                        class="bi bi-instagram"></i>&nbsp;&nbsp;Instagram</span></a></li>
                                        <li><a target="_blank" href="https://www.youtube.com/user/GymConcordia"><span><i
                                                        class="bi bi-youtube"></i>&nbsp;&nbsp;Youtube</span></a></li>
                                        <li><a target="_blank"
                                                href="https://www.tiktok.com/discover/gym-concordia-schiltigheim?lang=fr"><span><i
                                                        class="bi bi-tiktok"></i>&nbsp;&nbsp;TikTok</span></a></li>
                                    </ul>

                                </li>
                                <li class="">
                                    <a href="{{ route('Simple_Post', 13019) }}"><span>&nbsp;Questions Fréquentes</span></a>
                                </li>
                                <li class="">
                                    <a href="{{ route('index_mentions_legales') }}"><span>&nbsp;Mentions
                                            Légales</span></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="liens" data-toggle="modal"
                                        data-target="#contactModal"><span>&nbsp;Prendre contact</span></a>
                                </li>

                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="{{ route('index_categorie') }}">
                                <span>&nbsp;Achats</span>
                                @if (
                                    $categories->filter(function ($category) {
                                            return strlen($category->id_shop_category) === 1;
                                        })->count() > 0)
                                    <i class="bi bi-chevron-down dropdown-indicator"></i>
                                @endif
                            </a>
                            <ul>
                                @foreach ($categories->filter(function ($category) {
            return strlen($category->id_shop_category) === 1;
        }) as $category)
                                    @php
                                        $subCategoryCount = $categories
                                            ->filter(function ($subCategory) use ($category) {
                                                return strlen($subCategory->id_shop_category) === 3 &&
                                                    strpos(
                                                        $subCategory->id_shop_category,
                                                        $category->id_shop_category,
                                                    ) === 0;
                                            })
                                            ->count();
                                    @endphp

                                    <li class="{{ $subCategoryCount > 0 ? 'dropdown' : '' }}">
                                        <a href="{{ route('sous_categorie', ['id' => $category->id_shop_category]) }}">
                                            <span><img src="{{ $category->image }}"
                                                    width="24">&nbsp;{{ $category->name }}</span>
                                            @if ($subCategoryCount > 0)
                                                <i class="bi bi-chevron-down dropdown-indicator"></i>
                                            @endif
                                        </a>
                                        <!-- Second level dropdown: categories with three digits id -->
                                        @if ($subCategoryCount > 0)
                                            <ul>
                                                @foreach ($categories->filter(function ($subCategory) use ($category) {
            return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
        }) as $subCategory)
                                                    @php
                                                        $subSubCategoryCount = $categories
                                                            ->filter(function ($subSubCategory) use ($subCategory) {
                                                                return strlen($subSubCategory->id_shop_category) ===
                                                                    4 &&
                                                                    strpos(
                                                                        $subSubCategory->id_shop_category,
                                                                        $subCategory->id_shop_category,
                                                                    ) === 0;
                                                            })
                                                            ->count();
                                                    @endphp

                                                    <li class="{{ $subSubCategoryCount > 0 ? 'dropdown' : '' }}">
                                                        <a
                                                            href="{{ route('sous_categorie', ['id' => $subCategory->id_shop_category]) }}">
                                                            <span><img src="{{ $subCategory->image }}"
                                                                    width="24">&nbsp;{{ $subCategory->name }}</span>
                                                            @if ($subSubCategoryCount > 0)
                                                                <i class="bi bi-chevron-down dropdown-indicator"></i>
                                                            @endif
                                                        </a>
                                                        <!-- Third level dropdown: categories with four digits id -->
                                                        @if ($subSubCategoryCount > 0)
                                                            <ul>
                                                                @foreach ($categories->filter(function ($subSubCategory) use ($subCategory) {
            return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
        }) as $subSubCategory)
                                                                    <li>
                                                                        <a
                                                                            href="{{ route('sous_categorie', ['id' => $subSubCategory->id_shop_category]) }}">
                                                                            <span><img src="{{ $subSubCategory->image }}"
                                                                                    width="24">&nbsp;{{ $subSubCategory->name }}</span>
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                        </li>

                        <li class="dropdown"><a href="#"><span>&nbsp;Nos Activités</span> <i
                                    class="bi bi-chevron-down dropdown-indicator"></i></a>
                            <ul>
                                <li class="dropdown"><a href="#"><span>&nbsp;Nos Sections&nbsp;<i
                                                class="bi bi-chevron-down dropdown-indicator"></i></span></a>
                                    <!-- First level dropdown: categories with one digit id -->
                                    @php
                                        $category = Shop_category::select('id_shop_category', 'name', 'image')
                                            ->where('id_shop_category', 1)
                                            ->first();
                                    @endphp
                                    <ul>
                                        @foreach ($categories->filter(function ($subCategory) use ($category) {
            return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
        }) as $subCategory)
                                            <li class="{{ $subCategoryCount > 0 ? 'dropdown' : '' }}">
                                                <a
                                                    href="{{ route('sous_categorie', ['id' => $subCategory->id_shop_category]) }}">
                                                    <span><img src="{{ $subCategory->image }}"
                                                            width="24">&nbsp;{{ $subCategory->name }}</span>
                                                    @if (
                                                        $categories->filter(function ($subSubCategory) use ($subCategory) {
                                                                return strlen($subSubCategory->id_shop_category) === 4 &&
                                                                    strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
                                                            })->count() > 0)
                                                        <i class="bi bi-chevron-down dropdown-indicator"></i>
                                                    @endif
                                                </a>
                                                <!-- Third level dropdown: categories with four digits id -->
                                                <ul>
                                                    @foreach ($categories->filter(function ($subSubCategory) use ($subCategory) {
            return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
        }) as $subSubCategory)
                                                        <li>
                                                            <a
                                                                href="{{ route('sous_categorie', ['id' => $subSubCategory->id_shop_category]) }}">
                                                                <span><img src="{{ $subSubCategory->image }}"
                                                                        width="24">&nbsp;{{ $subSubCategory->name }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li><a href="{{ route('Simple_Post', 13224) }}"><span>&nbsp;Sport-Etudes</span></a>
                                <li><a href="{{ route('Simple_Post', 13005) }}"><span>&nbsp;Horaires Bureau</span></a>
                            </ul>
                        </li>
                        <li><a href="#" data-toggle="modal"
                                data-target="#search-modal"><span>&nbsp;Recherche</span></a></li>
                        <style>
                            @media (max-width: 768px) {
                                .nav-list .dropdown {
                                    order: -1;
                                }
                            }

                            .dropdown img {
                                width: 50px;
                                height: 50px;
                                object-fit: cover;
                                margin-right: 10px;
                            }
                        </style>
                        <!-- HTML -->

                        @php
                            $paniers = DB::table('basket')
                                ->select('basket.id')
                                ->where('basket.user_id', '=', auth()->user()->user_id)
                                ->get();
                        @endphp

                        @if (count($paniers) > 0)
                            <li><a href="{{ route('basket', auth()->user()->user_id) }}"style="color:red"><i
                                        style="color:red;font-size:1.2rem "
                                        class="mx-1 fa-sharp fa-regular fa-cart-shopping"></i>Panier
                                    ({{ count($paniers) }})</a></li>
                        @else
                            <li><a href="{{ route('basket', auth()->user()->user_id) }}"><i
                                        style="color:white;font-size:1.2rem "
                                        class="mx-1 fa-sharp fa-regular fa-cart-shopping"></i>Panier</a></li>
                        @endif
                    </ul>
                </nav><!-- .navbar -->

                <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
                <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
            @endguest
        </div>
    </header>
    <style>
        /* FORM */

        /* COLUMNS */

        .col {
            display: block;
            float: left;
            margin: 1% 0 1% 1.6%;
        }

        .col:first-of-type {
            margin-left: 0;
        }

        .four {
            width: 32.26%;
            max-width: 32.26%;
        }

        .form .plan input {
            display: none;
        }

        .form label {
            position: relative;
            color: #fff;
            background-color: #aaa;
            font-size: 16px;
            text-align: center;
            height: 100px;
            line-height: 100px;
            display: block;
            cursor: pointer;
            border: 3px solid transparent;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .form .plan input:checked+label {
            border: 3px solid #333;
            background-color: #3399FF;
        }

        .form .plan input:checked+label:after {
            content: "\2713";
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 100%;
            border: 2px solid #333;
            background-color: #3399FF;
            z-index: 999;
            position: absolute;
            top: -10px;
            right: -10px;
        }

        .submit {
            padding: 10px 40px;
            display: inline-block;
            border: none;
            margin: 14px 0;
            background-color: #2fcc71;
            color: #fff;
            border: 2px solid #333;
            font-size: 18px;
            -webkit-transition: transform 0.3s ease-in-out;
            -o-transition: transform 0.3s ease-in-out;
            transition: transform 0.3s ease-in-out;
        }

        .submit:hover {
            cursor: pointer;
            transform: rotateX(360deg);
        }

        .cancel {
            padding: 10px 40px;
            display: inline-block;
            border: none;
            margin: 14px 0;
            background-color: red;
            color: #fff;
            border: 2px solid #333;
            font-size: 18px;
            -webkit-transition: transform 0.3s ease-in-out;
            -o-transition: transform 0.3s ease-in-out;
            transition: transform 0.3s ease-in-out;
        }

        .cancel:hover {
            cursor: pointer;
            transform: rotateX(360deg);
        }

        .cf:before,
        .cf:after {
            content: " ";
            display: table;
        }

        .cf:after {
            clear: both;
        }

        .cf {
            *zoom: 1;
        }


        @media (max-width: 768px) {

            /* Add this CSS for responsive ordering */
            .navbar ul {
                display: flex;
                flex-direction: column;
            }

            .navbar li.dropdown {
                order: -1;
                /* This will move the specified <li> to the top */
            }

            .navbar li:not(.dropdown) {
                order: 1;
                /* Reset the order for other <li> elements */
            }
        }

        @media screen and (min-width: 1250px) {

            .taille-caract {
                font-size: 14px;
                justify-content: center;
            }
        }

        @media screen and (max-width: 1250px) {

            .taille-caract {
                font-size: 10px;
                justify-content: center;
            }
        }

        .liens:hover {
            text-decoration: none;
            color: gray;
            transition: all .1s ease-out;
        }


        @media (max-width: 768px) {
            .navbar {
                display: flex;
                flex-direction: row;
            }

            .navbar li {
                order: 1;
            }

            .navbar .login-first {
                order: -1;
            }
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    @if (!empty(Session::get('sent')) && Session::get('sent') == true)
        <script>
            $(function() {
                $('#myModal').modal('show');
            });
        </script>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const $searchType = $('#search-type');
            const $searchQuery = $('#search-query');
            const $searchResults = $('#search-results');

            $searchQuery.on('input', function() {
                const searchType = $searchType.val();
                const searchQuery = $searchQuery.val();

                if (searchQuery.length < 3) {
                    $searchResults.empty();
                    return;
                }

                let searchUrl = '';

                if (searchType === 'blog') {
                    searchUrl = '/search/blog';
                } else if (searchType === 'shop') {
                    searchUrl = '/search/shop';
                }

                $.ajax({
                    url: searchUrl,
                    method: 'GET',
                    data: {
                        query: searchQuery
                    },
                    success: function(data) {
                        $searchResults.empty();

                        if (data.length === 0) {
                            $searchResults.append('<p>Aucun résultat trouvé</p>');
                        } else {
                            data.forEach(function(item) {
                                const result = searchType === 'blog' ? item.titre : item
                                    .title;
                                const itemId = searchType === 'blog' ? item
                                    .id_blog_post_primaire : item.id_shop_article;
                                let saison;

                                if (searchType === 'blog') {
                                    saison = new Date(item.date_post).getFullYear();
                                } else if (searchType === 'shop') {
                                    saison = item.saison;
                                }

                                let url = '';

                                if (searchType === 'blog') {
                                    url = `/Simple_Post/${itemId}`;
                                } else if (searchType === 'shop') {
                                    url = `/details_article/${itemId}`;
                                }
                                let saisonn = saison + '-' + (saison + 1);
                                $searchResults.append(
                                    '<div class="border border-dark"><a class="aSearch p-2" href="' +
                                    url + '">[' + saisonn + '] - ' + result +
                                    '</a></div>');
                            });
                        }
                    }
                });
            });

            // Handle form submission
            $('#search-form').on('submit', function(e) {
                e.preventDefault();

                const searchType = $searchType.val();
                const searchQuery = $searchQuery.val();

                // Redirect the user to the search results page with the query as a URL parameter
                window.location.href =
                    `/search-results?type=${searchType}&query=${encodeURIComponent(searchQuery)}`;
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var logoContainer = $('.logo-container');
            var logoWidth = logoContainer.width();
            var logoImages = logoContainer.find('img');
            var currentImageIndex = 0;

            // Fonction pour changer l'image et faire l'animation
            function changeLogoImage() {
                // Si c'est la première image, la cacher et afficher la deuxième image
                if (currentImageIndex == 0) {
                    logoImages.eq(currentImageIndex).fadeOut(500, function() {
                        // Changer l'image actuelle
                        currentImageIndex++;
                        logoImages.eq(currentImageIndex).fadeIn(500);
                    });
                }
                // Si c'est la deuxième image, la cacher et afficher à nouveau le logo
                else {
                    logoImages.eq(currentImageIndex).fadeOut(500, function() {
                        // Changer l'image actuelle
                        currentImageIndex = 0;
                        logoImages.eq(currentImageIndex).fadeIn(500);
                    });
                }
            }

            // Lancer l'animation toutes les 5 secondes
            setInterval(changeLogoImage, 5000);
        });
    </script>
@endif

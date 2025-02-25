<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.adminheader')
<style>
    #logout-button {
        position: relative;
        /* Make sure the ::after pseudo-element is positioned relative to this element */
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #654321;
        transition: all 0.5s;
    }

    #logout-icon {
        max-width: 100%;
        height: auto;
        transform-origin: 50% 100%;
    }

    #logout-button:hover #logout-icon {
        animation: wave 0.7s infinite;
    }


    #logout-button::after {
        content: attr(data-text);
        position: absolute;
        opacity: 0;
        transition: opacity 0.5s;
        bottom: -20px;
        color: black;
    }

    #logout-button:hover::after {
        opacity: 1;
        /* Show the text on hover */
    }


    @keyframes wave {

        0%,
        100% {
            transform: rotate(0deg);
        }

        50% {
            transform: rotate(-30deg);
        }
    }
</style>
@php
    use App\Models\SystemSetting;
@endphp

<body>

    <div class="modal fade" id="settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Paramètres Système</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">

                                    <div class="stat-icon dib"><i style="color: #2770e6; display:inline-block"
                                            class="fa fa-solid fa-door-open mr-1"></i>

                                        <form id="form">

                                            <input id="checkbox1" type="checkbox" class="checkbox"
                                                {{ SystemSetting::getValue(2) == 1 ? 'checked' : '' }}>
                                            <input type="hidden" id="url" name="URL" value="/admin">
                                            <div class="stat-text" style="font-size : 12px !important;">Portes ouvertes
                                            </div>
                                            <label for="checkbox1" class="switch">
                                                <span class="switch__circle">
                                                    <span class="switch__circle-inner"></span>
                                                </span>
                                                <span class="switch__left">Off</span>
                                                <span class="switch__right">On</span>
                                            </label>
                                            <input hidden="" name="redirect" value="0">

                                        </form>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!--
            <div class="col-lg-3">
                <div class="card">
                    <div class="stat-widget-one">
                        <div class="stat-icon dib"><i style="color: #2770e6; display:inline-block" class="fa fa-solid fa-envelope mr-1"></i>
                            <form  id="form">
                            
                                <input id="checkbox2"  type="checkbox" class="checkbox">
                                <div class="stat-text" style="font-size : 12px !important;">Mails inscription</div>
                                <label for="checkbox2" class="switch">
                                 <span class="switch__circle">
                                     <span class="switch__circle-inner"></span>
                                 </span>
                                 <span class="switch__left">Off</span>
                                 <span class="switch__right">On</span>
                             </label>
                                                           <input hidden="" name="redirect" value="0">
    
                            </form>
                        </div>
                    </div>
    
                </div>
    
            </div>
            -->
                        <div class="col-lg-3">
                            <div class="card">
                                <form id="form">
                                    <input id="checkbox3" type="checkbox" class="checkbox"
                                        {{ SystemSetting::getValue(3) == 1 ? 'checked' : '' }}>
                                    <input type="hidden" id="Etat_Switch" name="Etat_Switch" value="0">
                                    <div class="stat-text" style="font-size : 12px !important;">Message Général Shop
                                    </div>
                                    <label for="checkbox3" class="switch">
                                        <span class="switch__circle">
                                            <span class="switch__circle-inner"></span></span>
                                        <span class="switch__left">Off</span>
                                        <span class="switch__right">On</span>
                                    </label> <input hidden="" name="redirect" value="0">

                                </form>
                            </div>
                            <br>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <form id="form">
                                    <input id="checkbox5" type="checkbox" class="checkbox"
                                        {{ SystemSetting::getValue(5) == 1 ? 'checked' : '' }}>
                                    <input type="hidden" id="Etat_Vente" name="Etat_Vente" value="1">
                                    <div class="stat-text" style="font-size : 12px !important;">Cours en Vente
                                    </div>
                                    <label for="checkbox5" class="switch">
                                        <span class="switch__circle">
                                            <span class="switch__circle-inner"></span></span>
                                        <span class="switch__left">Off</span>
                                        <span class="switch__right">On</span>
                                    </label> <input hidden="" name="redirect" value="0">

                                </form>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <form id="form">
                                    <input id="checkbox12" type="checkbox" class="checkbox"
                                        {{ SystemSetting::getValue(12) == 1 ? 'checked' : '' }}>
                                    <input type="hidden" id="Etat_Vente" name="Etat_Vente" value="1">
                                    <div class="stat-text" style="font-size : 12px !important;">Cours en Vente pour
                                        membres
                                    </div>
                                    <label for="checkbox12" class="switch">
                                        <span class="switch__circle">
                                            <span class="switch__circle-inner"></span></span>
                                        <span class="switch__left">Off</span>
                                        <span class="switch__right">On</span>
                                    </label> <input hidden="" name="redirect" value="0">

                                </form>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">

                                    <div class="stat-icon dib"><img class=""
                                            src="{{ asset('assets/images/work.png') }}" alt="">

                                        <form class="pt-0" id="form">

                                            <input id="checkbox7" type="checkbox" class="checkbox"
                                                {{ SystemSetting::getValue(7) == 1 ? 'checked' : '' }}>
                                            <input type="hidden" id="url" name="URL" value="/admin">
                                            <div class="stat-text" style="font-size : 12px !important;">Maintenance
                                            </div>
                                            <label for="checkbox7" class="switch">
                                                <span class="switch__circle">
                                                    <span class="switch__circle-inner"></span>
                                                </span>
                                                <span class="switch__left">Off</span>
                                                <span class="switch__right">On</span>
                                            </label>
                                            <input hidden="" name="redirect" value="0">

                                        </form>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one row">
                                    <div class="stat-icon dib col-4">
                                        <i class="fa fa-pencil-alt" style="color: #2770e6;"></i>
                                    </div>
                                    <div class="col-8 ">
                                        <span class="stat-text" style="font-size : 12px !important;">Message
                                            Accueil</span><br>
                                        <a href="{{ route('edit_blog_index', ['id' => 1]) }}"
                                            class="btn btn-primary btn-sm mt-1">Editer</a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i style="color: #2770e6; display:inline-block"
                                            class="fa-solid fa-people-roof mr-1"></i>

                                        <form id="form">

                                            <input id="checkbox11" type="checkbox" class="checkbox"
                                                {{ SystemSetting::getValue(11) == 1 ? 'checked' : '' }}>
                                            <input type="hidden" id="url" name="URL" value="/admin">
                                            <div class="stat-text" style="font-size : 12px !important;">Réduction
                                                Famille</div>
                                            <label for="checkbox11" class="switch">
                                                <span class="switch__circle">
                                                    <span class="switch__circle-inner"></span>
                                                </span>
                                                <span class="switch__left">Off</span>
                                                <span class="switch__right">On</span>
                                            </label>
                                            <input hidden="" name="redirect" value="0">

                                        </form>
                                    </div>
                                </div>

                            </div>

                        </div>


                    </div>
                    <button onclick="window.location.href='{{ route('generate.combined.pdf') }}'">Télécharger le PDF
                        combiné</button>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            #submenu1 .fa {
                color: red;
            }

            .gc-rouge {
                color: red;
            }

            #submenu2 .fa {
                color: cyan;
            }

            .gc-cyan {
                color: cyan;
            }

            #submenu3 .fa {
                color: green;
            }

            .gc-green {
                color: green;
            }

            #submenu4 .fa {
                color: orange;
            }

            .gc-orange {
                color: orange;
            }

            #submenu5 .fa {
                color: pink;
            }

            .gc-pink {
                color: pink;
            }

            #submenu6 .fa {
                color: lime;
            }

            .gc-lime {
                color: lime;
            }

            #submenu7 .fa {
                color: yellow;
            }

            .gc-yellow {
                color: yellow;
            }

            #submenu8 .fa {
                color: violet;
            }

            .gc-violet {
                color: violet;
            }

            #submenu9 .fa {
                color: red;
            }

            .gc-blue {
                color: red;
            }

            #form {
                position: relative;
                bottom: 5px;
                left: 10px;
                display: inline-block;
            }

            .switch {
                align-items: center;
                background-color: gray;
                border-radius: 500px;
                cursor: pointer;
                display: flex;
                height: 40px;
                justify-content: space-between;
                padding: 0 10px;
                position: relative;
                user-select: none;
                width: 80px;
            }

            .checkbox:checked~.switch {
                background-color: blue;
            }

            .switch__left,
            .switch__right {
                color: white;
                font-weight: bold;
                text-transform: uppercase;
            }

            .checkbox:checked~.switch .switch__left {
                visibility: hidden;
            }

            .checkbox:not(:checked)~.switch .switch__right {
                visibility: hidden;
            }

            .switch__circle {
                height: 40px;
                padding: 5px;
                position: absolute;
                transition: all 0.1s linear;
                width: 40px;
            }

            .checkbox:checked~.switch .switch__circle {
                left: 0;
                right: calc(100% - 40px);
            }

            .checkbox:not(:checked)~.switch .switch__circle {
                left: calc(100% - 40px);
                right: 0;
            }

            .switch__circle-inner {
                background-color: white;
                border-radius: 50%;
                display: block;
                height: 100%;
                width: 100%;
            }

            .card-body {
                padding: 0;
            }

            .card {
                background: #ffffff;
                margin: 7.5px 0;
                padding: 20px;
                border: 1px solid #e7e7e7;
                border-radius: 3px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            }

            .card-subtitle {
                font-size: 12px;
                margin: 10px 0;
            }

            .card-title {
                font-weight: 400;
                font-size: 18px;
                margin: 0;
            }

            .card-title h4 {
                display: inline-block;
                font-weight: 400;
                font-size: 18px;
            }

            .card-title p {
                font-family: 'Roboto', sans-serif;
                margin-bottom: 12px;
            }

            .card-header-right-icon {
                display: inline-block;
                float: right;
            }

            .card-header-right-icon li {
                float: right;
                padding-left: 14px;
                color: #868e96;
                cursor: pointer;
                vertical-align: middle;
                transition: all 0.5s ease-in-out;
                display: inline-block;
            }

            .card-header-right-icon li .ti-close {
                font-size: 12px;
            }

            .card-option {
                position: relative;
            }

            .card-option-dropdown {
                display: none;
                left: auto;
                right: 0;
            }

            .card-option-dropdown li {
                display: block;
                float: left;
                width: 100%;
                padding: 0;
            }

            .card-option-dropdown li a {
                line-height: 25px;
                font-size: 12px;
            }

            .card-option-dropdown li a i {
                margin-right: 10px;
            }

            .stat-widget-one .stat-icon {
                vertical-align: top;
            }

            .stat-widget-one .stat-icon i {
                font-size: 30px;
                border-width: 3px;
                border-style: solid;
                border-radius: 100px;
                padding: 15px;
                font-weight: 900;
                display: inline-block;
            }

            .stat-widget-one .stat-content {
                margin-left: 30px;
                margin-top: 7px;
            }

            .stat-widget-one .stat-text {
                font-size: 14px;
                color: #868e96;
            }

            .stat-widget-one .stat-digit {
                font-size: 24px;
                color: #373757;
            }

            .checkbox {
                display: none;
            }

            @media (min-width: 800px) {
                .titlecolor {}
            }

            @media (max-width: 800px) {
                .titlecolor {
                    background-color: #343a40 !important;
                }
            }
        </style>
    </div>

    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between"> <a href="{{ route('admin.index') }}"
                class="logo d-flex align-items-center"> <img src="{{ asset('assets/images/LogoHB.png') }}"
                    alt=""> <span class="d-none d-lg-block">Admin</span> </a> <i
                class="bi bi-list toggle-sidebar-btn"></i></div>
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                        class="rounded-circle" id="logout-button" data-text="Déconnexion">
                        <img src="{{ asset('assets/images/goodbye.png') }}" alt="" id="logout-icon">
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                <li class="nav-item dropdown pe-3">


                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        @if (auth()->user()->image)
                            <img class="rounded-circle" src="{{ auth()->user()->image }}">
                        @elseif (auth()->user()->gender == 'male')
                            <img class="rounded-circle" src="{{ asset('assets\images\user.jpg') }}" alt="male">
                        @elseif (auth()->user()->gender == 'female')
                            <img class="rounded-circle" src="{{ asset('assets\images\femaleuser.png') }}"
                                alt="female">
                        @endif
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6> {{ auth()->user()->name }} {{ auth()->user()->lastname }}</h6>
                        </li>
                        <li> <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal"
                                data-target="#settings"> <i class="bi bi-gear"></i> <span>Paramètres</span> </a></li>
                        <li> <a class="dropdown-item d-flex align-items-center" href="{{ route('A_blog') }}"> <i
                                    class="bi bi-box-arrow-right"></i> <span>Retour au blog</span> </a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item"> <a class="nav-link " href="{{ route('admin.index') }}"> <i
                        class="bi bi-grid"></i>
                    <span>Accueil</span> </a></li>
            @if (auth()->user()->roles->estAutoriserDeVoirMembres ||
                    auth()->user()->roles->estAutoriserDeVoirClickAsso ||
                    (SystemSetting::getValue(2) == 1 && auth()->user()->roles->estAutoriserDeVoirPortesOuvertes))
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse"
                        href="#"><span style="margin-right: 5px; color:#007b00;"
                            class="fa fa-users fa-fw mr-2 gc-green"></span>
                        <span>Utilisateurs</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                    <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        @if (auth()->user()->roles->estAutoriserDeVoirMembres)
                            <li> <a href="{{ route('utilisateurs.members') }}"> <i style="color: #007b00"
                                        class="fa-solid fa-user"></i><span>Membres</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirClickAsso)
                            <li> <a href="{{ route('users.clickasso') }}">
                                    <i style="color: #007b00" class="fa-light fa-at"></i><span>ClickAsso</span> </a>
                            </li>
                        @endif
                        @if (SystemSetting::getValue(2) == 1 && auth()->user()->roles->estAutoriserDeVoirPortesOuvertes)
                            <li> <a href="{{ route('portesOuvertes') }}"> <i style="color: #007b00"
                                        class="fa-sharp fa-solid fa-door-open"></i><span>Portes Ouvertes</span> </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (auth()->user()->roles->estAutoriserDeVoirArticles ||
                    auth()->user()->roles->estAutoriserDeRedigerArticle ||
                    auth()->user()->roles->estAutoriserDeVoirCategories ||
                    auth()->user()->roles->estAutoriserDeditCarroussel)
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse"
                        href="#"> <i style="color: #0ef9de" class="bi bi-journal-text"></i><span>Blog</span><i
                            class="bi bi-chevron-down ms-auto"></i></a>
                    <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        @if (auth()->user()->roles->estAutoriserDeVoirArticles)
                            <li> <a href="{{ route('index') }}"> <i style="color: #0ef9de"
                                        class="bi bi-journal-text"></i><span>Articles</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeRedigerArticle)
                            <li> <a href="{{ route('index_article_redaction') }}"> <i style="color: #0ef9de"
                                        class="fa-solid fa-pen"></i><span>Rédiger un article</span> </a></li>
                        @endif

                        {{-- @if (auth()->user()->roles->estAutoriserDeGererSlider)
                          <li> <a href="forms-editors.html"> <i style="color: #0ef9de" class="fa-sharp fa-regular fa-images"></i><span>Gérer le slider</span> </a></li>
                      @endif  --}}


                        @if (auth()->user()->roles->estAutoriserDeVoirCategories)
                            <li> <a href="{{ route('index_article_category') }}"> <i style="color: #0ef9de"
                                        class="fa-solid fa-list-ol"></i><span>Catégories</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeditCarroussel)
                            <li> <a href="{{ route('carroussel.edit') }}">
                                    <i style="color: #0ef9de" class="fa-regular fa-edit"></i>
                                    <span>Modifier carroussel</span>
                                </a></li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (auth()->user()->roles->estAutoriserDeVoirFacture || auth()->user()->roles->estAutoriserDeVoirReduction)
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse"
                        href="#"> <i style="color: #ff0000"
                            class="fa-solid fa-money-bill"></i><span>Paiement</span><i
                            class="bi bi-chevron-down ms-auto"></i> </a>
                    <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        @if (auth()->user()->roles->estAutoriserDeVoirFacture)
                            <li> <a href="{{ route('paiement.facture') }}"><i style="color: #ff0000"
                                        class="fa-solid fa-euro-sign"></i><span>Facture</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirReduction)
                            <li> <a href="{{ route('paiement.reduction') }}"><i style="color: #ff0000"
                                        class="fa-solid fa-ticket"></i></span><span>Réduction</span> </a></li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (auth()->user()->roles->estAutoriserDeVoirArticleBoutique ||
                    auth()->user()->roles->estAutoriserDeVoirCategorieBoutique)
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse"
                        href="#"> <i style="color: #f7bac5" class="bi bi-gem"></i><span>Boutique</span><i
                            class="bi bi-chevron-down ms-auto"></i> </a>
                    <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        {{-- ancienne article @if (auth()->user()->roles->estAutoriserDeVoirArticleBoutique)<li> <a href="{{ route('index_article') }}"><span
                        style="color: #f7bac5; margin-right:8px"
                        class="fa fa-basket-shopping fa-fw mr-1"></span><span>Article</span> </a>
            </li>@endif --}}
                        @if (auth()->user()->roles->estAutoriserDeVoirArticleBoutique)
                            <li> <a href="{{ route('article') }}"><span style="color: #f7bac5; margin-right:8px"
                                        class="fa fa-basket-shopping fa-fw mr-1"></span><span>Article</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirCategorieBoutique)
                            <li> <a href="{{ route('A_Categorie') }}"><span style="color: #f7bac5; margin-right:8px"
                                        class="fa fa-coins fa-fw mr-1"></span><span>Catégories</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirModeStrict)
                            <li> <a href="{{ route('mode_strict') }}"><span style="color: #f7bac5; margin-right:8px"
                                        class="fa-solid fa-user-ninja mr-1"></span><span>Mode
                                        Strict</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirParametrage)
                            <li> <a href="{{ route('payment_selection') }}"><span
                                        style="color: #f7bac5; margin-right:8px"
                                        class="fa fa-cog mr-1"></span><span>Paramètrage</span> </a></li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (auth()->user()->roles->estAutoriserDeVoirPreparationLogistique ||
                    auth()->user()->roles->estAutoriserDeVoirDistributionLogistique)
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#logistics-nav" data-bs-toggle="collapse"
                        href="#">
                        <i style="color: #4CAF50;" class="bi bi-truck"></i><span>Logistique</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="logistics-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        @if (auth()->user()->roles->estAutoriserDeVoirPreparationLogistique)
                            <li> <a href="{{ route('logistique.preparation') }}"><span
                                        style="color: #4CAF50; margin-right:8px"
                                        class="fa fa-box-open fa-fw mr-1"></span><span>Préparation</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirDistributionLogistique)
                            <li> <a href="{{ route('logistique.distribution') }}"><span
                                        style="color: #4CAF50; margin-right:8px"
                                        class="fa fa-route fa-fw mr-1"></span><span>Distribution</span> </a></li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (auth()->user()->roles->estAutoriserDeVoirMessages || auth()->user()->roles->estAutoriserDeVoirHistorique)
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse"
                        href="#"> <i style="color: #f80000;"
                            class="fa-regular fa-message"></i><span>Communication</span><i
                            class="bi bi-chevron-down ms-auto"></i> </a>
                    <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        @if (auth()->user()->roles->estAutoriserDeVoirMessages)
                            <li> <a href="{{ route('index_communication') }}"><span
                                        style="color: #f80000; margin-right:8px"
                                        class="fa fa-envelope fa-fw mr-1"></span><span>Envoi de mail</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirHistorique)
                            <li> <a href="{{ route('historique') }}"><span style="color: #f80000; margin-right:8px"
                                        class="fa fa-clock-rotate-left fa-fw mr-1"></span><span>Historique</span> </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (auth()->user()->roles->estAutoriserDeVoirCours ||
                    auth()->user()->roles->estAutoriserDeVoirProduitsClub ||
                    auth()->user()->roles->estAutoriserDeVoirAdhesionsClub ||
                    auth()->user()->roles->estAutoriserDeVoirStatsExports ||
                    auth()->user()->roles->estAutoriserDeVoirValiderCertificats)
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#club-nav" data-bs-toggle="collapse"
                        href="#"><span style="color: #f59f00; margin-right:10px"
                            class="fa fa-medal fa-fw mr-2 gc-orange"></span><span>Le
                            Club @php
                                $certificatsEnAttente = \App\Models\MedicalCertificates::where('validated', 0)->count();
                            @endphp
                            @if ($certificatsEnAttente > 0)
                                <span class="badge bg-danger"
                                    style="margin-left: 5px;">{{ $certificatsEnAttente }}</span>
                            @endif
                        </span>
                        <i class="bi bi-chevron-down ms-auto"></i> </a>
                    <ul id="club-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        @if (auth()->user()->roles->estAutoriserDeVoirCours)
                            <li> <a href="{{ route('index_cours') }}"><span style="color: #f59f00; margin-right:10px"
                                        class="fa fa-person-running fa-fw mr-1"></span><span>Cours</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirProduitsClub)
                            <li> <a href="{{ route('index_produits') }}"><span
                                        style="color: #f59f00; margin-right:10px"
                                        class="fa fa-box-open fa-fw mr-1"></span><span>Produits</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirAdhesionsClub)
                            <li> <a href="{{ route('index_adhesions') }}"><span
                                        style="color: #f59f00; margin-right:10px"
                                        class="fa fa-users fa-fw mr-1"></span><span>Adhésions</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirStatsExports)
                            <li> <a href="{{ route('StatsExports') }}"><span
                                        style="color: #f59f00; margin-right:10px"
                                        class="fa fa-chart-line fa-fw mr-1"></span><span>Stats-Exports</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirValiderCertificats)
                            <li> <a href="{{ route('validationCertificats') }}"><span
                                        style="color: #f59f00; margin-right:10px"
                                        class="fa fa-stamp fa-fw mr-1"></span><span>Valider Certificats
                                        @php $certificatsEnAttente = \App\Models\MedicalCertificates::where('validated', 0)->count(); @endphp
                                        @if ($certificatsEnAttente > 0)
                                            <span class="badge bg-danger"
                                                style="margin-left: 5px;">{{ $certificatsEnAttente }}</span>
                                        @endif
                                    </span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirHistroriqueClub)
                            <li> <a href="{{ route('history_index') }}"><span
                                        style="color: #f59f00; margin-right:10px"
                                        class="fa-sharp fa-solid fa-clock-rotate-left"></span><span>Historique</span>
                                </a></li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (auth()->user()->roles->estAutoriserDeVoirGestionProfessionnels ||
                    auth()->user()->roles->estAutoriserDeVoirCalculDesSalaires ||
                    auth()->user()->roles->estAutoriserDeVoirValiderLesHeures ||
                    auth()->user()->roles->estAutoriserDeVoirDeclarerHeure)
                @php
                    $declarationCount = \App\Models\Declaration::where('soumis', 1)->where('valider', 0)->count();
                @endphp

                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#pro-nav" data-bs-toggle="collapse"
                        href="#">
                        <span style="color: #00f900; margin-right:10px"
                            class="fa fa-id-card-clip fa-fw mr-2 gc-lime"></span>
                        <span>Professionnels</span>
                        @if ($declarationCount > 0 && auth()->user()->role >= 90)
                            <span class="badge bg-danger mx-2">{{ $declarationCount }}</span>
                        @endif
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="pro-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        @if (auth()->user()->roles->estAutoriserDeVoirGestionProfessionnels)
                            <li>
                                <a href="{{ route('Professionnels.gestion') }}">
                                    <span style="color: #00f900; margin-right:10px"
                                        class="fa fa-user-tie fa-fw mr-1"></span>
                                    <span>Gestion</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirCalculDesSalaires)
                            <li>
                                <a href="{{ route('proffesional.calculSalary') }}">
                                    <span style="color: #00f900; margin-right:10px"
                                        class="fa fa-euro-sign fa-fw mr-1"></span>
                                    <span>Calcul des salaires</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirValiderLesHeures)
                            <li>
                                <a href="{{ route('proffesional.valideHeure') }}">
                                    <span style="color: #00f900; margin-right:10px"
                                        class="fa fa-clock-rotate-left fa-fw mr-1"></span>
                                    <span>Valider les heures</span>
                                    @if ($declarationCount > 0)
                                        <span class="badge bg-danger mx-2">{{ $declarationCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirDeclarerHeure)
                            <li>
                                <a href="{{ route('Professionnels.declarerHeure') }}">
                                    <span style="color: #00f900; margin-right:10px"
                                        class="fa fa-clock-rotate-left fa-fw mr-1"></span>
                                    <span>Déclarer les heures</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (auth()->user()->roles->estAutoriserDeVoirAnimations)
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#anim-nav" data-bs-toggle="collapse"
                        href="#">
                        <span style="color: #17a2b8; margin-right:10px"
                            class="fa fa-cogs fa-fw mr-2 gc-yellow"></span>
                        <span>Animations</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="anim-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('gestion.animations') }}">
                                <span style="color: #17a2b8; margin-right:10px"
                                    class="fa fa-calendar-check fa-fw mr-1"></span>
                                <span>Séances</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('gestion.categories.animations') }}">
                                <span style="color: #17a2b8; margin-right:10px"
                                    class="fa fa-list-ol fa-fw mr-1"></span>
                                <span>Catégories</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif


            @if (auth()->user()->roles->estAutoriserDeVoirGestionDesDroits ||
                    auth()->user()->roles->estAutoriserDeVoirParametresGeneraux ||
                    auth()->user()->roles->estAutoriserDeVoirSalles ||
                    auth()->user()->roles->estAutoriserDeVoirMessageGeneral)
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#para-nav" data-bs-toggle="collapse"
                        href="#"><span style="color: #f5f503; margin-right:10px"
                            class="fa fa-screwdriver-wrench fa-fw mr-2 gc-yellow"></span><span>Système</span><i
                            class="bi bi-chevron-down ms-auto"></i> </a>
                    <ul id="para-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        @if (auth()->user()->roles->estAutoriserDeVoirGestionDesDroits)
                            <li> <a href="{{ route('index_roles') }}"><span style="color: #f5f503; margin-right:10px"
                                        class="fa fa-user-check fa-fw mr-1"></span><span>Gestion des droits</span> </a>
                            </li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirParametresGeneraux)
                            <li> <a href="{{ route('parametres') }}"><span style="color: #f5f503; margin-right:10px"
                                        class="fa fa-map-location-dot fa-fw mr-1"></span><span>Paramètres</span> </a>
                            </li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirSalles)
                            <li> <a href="{{ route('index_salle') }}"><span style="color: #f5f503; margin-right:10px"
                                        class="fa fa-map-location-dot fa-fw mr-1"></span><span>Salles</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirMessageGeneral)
                            <li> <a href="{{ route('message.general') }}"><i style="color: #f5f503;"
                                        class="fa-regular fa-message"></i></span><span>Message Général</span> </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirFichiers)
                            <li> <a href="{{ route('server.browser') }}"><i style="color: #f5f503;"
                                        class="fa-regular fa-file"></i></span><span>Fichiers Serveur</span>
                                </a></li>
                        @endif
                        @if (auth()->user()->roles->estAutoriserDeVoirMessageMaintenance)
                            <li> <a href="{{ route('message.maintenance.see') }}"><i style="color: #f5f503;"
                                        class="fa-regular fa-message"></i></span><span>Message de maintenance</span>
                                </a></li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </aside>
    @yield('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function updateSetting(checkbox, settingId) {
                let settingValue = checkbox.is(':checked') ? 1 : 0;
                $.ajax({
                    url: '{{ route('update_system_setting') }}',
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'setting_id': settingId,
                        'setting_value': settingValue
                    },
                    success: function(data) {
                        console.log('Success:', data.message);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error:', textStatus, errorThrown);
                    }
                });
            }

            $('#checkbox1').on('change', function() {
                updateSetting($(this), 2);
            });

            $('#checkbox11').on('change', function() {
                updateSetting($(this), 11);
            });

            $('#checkbox3').on('change', function() {
                updateSetting($(this), 3);
            });

            $('#checkbox12').on('change', function() {
                updateSetting($(this), 12);
            });

            $('#checkbox5').on('change', function() {
                updateSetting($(this), 5);
            });

            $('#checkbox7').on('change', function() {
                updateSetting($(this), 7);
            });
        });
    </script>
    <script src="{{ asset('assetsAdmin/js/tinymce.min.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/validate.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/echarts.min.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets\js\bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/apexcharts.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="//g.tutorialjinni.com/mojoaxel/bootstrap-select-country/dist/js/bootstrap-select-country.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
    <script src="../r_js/jquery.nestable.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assetsAdmin/js/main.js') }}"></script>
    <script src="../r_js/style.js"></script>


    @livewireScripts
</body>

</html>

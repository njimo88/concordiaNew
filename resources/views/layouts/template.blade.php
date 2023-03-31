<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.adminheader')
<body>

   <div class="modal fade" id="settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    
                        <div class="stat-icon dib"><i style="color: #2770e6; display:inline-block" class="fa fa-solid fa-door-open mr-1"></i>
    
                            <form  id="form" >
                            
                                <input id="checkbox1"  type="checkbox" class="checkbox">
                                <input type="hidden" id="url" name="URL" value="/admin">
                                <div class="stat-text" style="font-size : 12px !important;">Portes ouvertes</div>
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
            <div class="col-lg-3">
                <div class="card">
                    <form  id="form" >
                    <input id="checkbox3"  type="checkbox" class="checkbox">
                        <input type="hidden" id="Etat_Switch" name="Etat_Switch" value="0">
                        <div class="stat-text" style="font-size : 12px !important;">Message Général Shop
                        </div>
                        <label for="checkbox3" class="switch">
                            <span class="switch__circle">
                            <span class="switch__circle-inner"></span></span>
                            <span class="switch__left">Off</span>
                            <span class="switch__right">On</span>
                        </label>                    <input hidden="" name="redirect" value="0">
    
                    </form>
            </div>
            <br>
          </div>
          <div class="col-lg-3">
                <div class="card">
                    <form  id="form" >
                    <input id="checkbox5"  type="checkbox" class="checkbox" checked="">
                        <input type="hidden" id="Etat_Vente" name="Etat_Vente" value="1">
                        <div class="stat-text" style="font-size : 12px !important;">Cours en Vente
                        </div>
                        <label for="checkbox5" class="switch">
                            <span class="switch__circle">
                            <span class="switch__circle-inner"></span></span>
                            <span class="switch__left">Off</span>
                            <span class="switch__right">On</span>
                        </label>                    <input hidden="" name="redirect" value="0">
    
                    </form>
            </div>
          </div>
         </div>
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
        #form{
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
      .checkbox{
          display: none;
      }
    
      @media (min-width: 800px) {
        .titlecolor{
        }
      }
      @media (max-width: 800px) {
        .titlecolor{
            background-color:#343a40!important;
        }
      }
    </style>
    </div>

    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between"> <a href="{{ route('admin.index') }}" class="logo d-flex align-items-center"> <img src="{{ asset('assets/images/logo.png') }}" alt=""> <span class="d-none d-lg-block">Admin</span> </a> <i class="bi bi-list toggle-sidebar-btn"></i></div>
        <nav class="header-nav ms-auto">
           <ul class="d-flex align-items-center">
              <li class="nav-item dropdown pe-3">
                 <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                  @if(auth()->user()->image)
                     <img class="rounded-circle" src="{{  auth()->user()->image }}" >
                  @elseif (auth()->user()->gender == 'male')
                     <img class="rounded-circle" src="{{ asset('assets\images\user.jpg') }}" alt="male">
                  @elseif (auth()->user()->gender == 'female')
                     <img class="rounded-circle" src="{{ asset('assets\images\femaleuser.png') }}" alt="female">
                  @endif
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->name }}</span> 
                  </a>
                 <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                       <h6> {{ auth()->user()->name }} {{ auth()->user()->lastname }}</h6>
                    </li>
                    <li> <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal" data-target="#settings"> <i class="bi bi-gear"></i> <span>Paramètres</span> </a></li>
                    <li> <a class="dropdown-item d-flex align-items-center" href="{{ route('A_blog') }}"> <i class="bi bi-box-arrow-right"></i> <span>Retour au blog</span> </a></li>
                 </ul>
              </li>
           </ul>
        </nav>
     </header>
     <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
           <li class="nav-item"> <a class="nav-link " href="{{ route('admin.index') }}"> <i class="bi bi-grid"></i> <span>Acceuil</span> </a></li>
           @if(auth()->user()->roles->estAutoriserDeVoirMembres || auth()->user()->roles->estAutoriserDeVoirClickAsso)
               <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#"><span style="margin-right: 5px; color:#007b00;" class="fa fa-users fa-fw mr-2 gc-green"></span> <span>Utilisateurs</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                  <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                     @if(auth()->user()->roles->estAutoriserDeVoirMembres)<li> <a href="{{route('utilisateurs.members')}}"> <i style="color: #007b00" class="fa-solid fa-user"></i><span>Membres</span> </a></li>@endif
                     @if (auth()->user()->roles->estAutoriserDeVoirClickAsso)<li> <a href="{{route('utilisateurs.members')}}"> <i style="color: #007b00" class="fa-light fa-at"></i><span>ClickAsso</span> </a></li>@endif
                  </ul>
               </li>
            @endif
            @if ( auth()->user()->roles->estAutoriserDeVoirArticles || auth()->user()->roles->estAutoriserDeRedigerArticles || auth()->user()->roles->estAutoriserDeGererSlider || auth()->user()->roles->estAutoriserDeVoirCategories)
               <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#"> <i style="color: #0ef9de" class="bi bi-journal-text"></i><span>Blog</span><i class="bi bi-chevron-down ms-auto"></i></a>
                  <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                     @if (auth()->user()->roles->estAutoriserDeVoirArticles)
                        <li> <a href="{{route('index')}}"> <i style="color: #0ef9de" class="bi bi-journal-text"></i><span>Articles</span> </a></li>
                     @endif
                     @if ( auth()->user()->roles->estAutoriserDeRedigerArticles)
                        <li> <a href="{{route('index_article_redaction')}}"> <i style="color: #0ef9de" class="fa-solid fa-pen"></i><span>Rédiger un article</span> </a></li>
                     @endif
                     @if ( auth()->user()->roles->estAutoriserDeGererSlider)
                        <li> <a href="forms-editors.html"> <i style="color: #0ef9de" class="fa-sharp fa-regular fa-images"></i><span>Gérer le slider</span> </a></li>
                     @endif
                     @if ( auth()->user()->roles->estAutoriserDeVoirCategories)
                        <li> <a href="{{route('index_article_category')}}"> <i style="color: #0ef9de" class="fa-solid fa-list-ol"></i><span>Catégories</span> </a></li>
                     @endif
                  </ul>
               </li>
            @endif
            @if (auth()->user()->roles->estAutoriserDeVoirFacture || auth()->user()->roles->estAutoriserDeVoirReduction)
               <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#"> <i style="color: #ff0000" class="fa-solid fa-money-bill"></i><span>Paiement</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                  <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                     @if (auth()->user()->roles->estAutoriserDeVoirFacture)
                           <li> <a href="{{route('paiement.facture')}}"><i style="color: #ff0000"  class="fa-solid fa-euro-sign"></i><span>Facture</span> </a></li>
                     @endif
                     @if (auth()->user()->roles->estAutoriserDeVoirReduction)
                           <li> <a href="tables-data.html"><i style="color: #ff0000"  class="fa-solid fa-ticket"></i></span><span>Réduction</span> </a></li>
                        @endif
                  </ul>
               </li>
           @endif
           @if ( auth()->user()->roles->estAutoriserDeVoirArticleBoutique || auth()->user()->roles->estAutoriserDeVoirCategorieBoutique)
               <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#"> <i style="color: #f7bac5"  class="bi bi-gem"></i><span>Boutique</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                  <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                     @if( auth()->user()->roles->estAutoriserDeVoirArticleBoutique )<li> <a href="{{ route('index_article') }}"><span style="color: #f7bac5; margin-right:8px" class="fa fa-basket-shopping fa-fw mr-1"></span><span>Article</span> </a></li>@endif
                     @if( auth()->user()->roles->estAutoriserDeVoirCategorieBoutique )<li> <a href="{{ route('A_Categorie') }}"><span style="color: #f7bac5; margin-right:8px" class="fa fa-coins fa-fw mr-1"></span><span>Catégories</span> </a></li>@endif
                  </ul>
               </li>
            @endif
            @if ( auth()->user()->roles->estAutoriserDeVoirMessages || auth()->user()->roles->estAutoriserDeVoirHistorique)
               <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#"> <i style="color: #f80000;" class="fa-regular fa-message"></i><span>Communication</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                  <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                     @if (auth()->user()->roles->estAutoriserDeVoirMessages)<li> <a href="{{route('index_communication')}}"><span style="color: #f80000; margin-right:8px" class="fa fa-envelope fa-fw mr-1"></span><span>Envoie de mail</span> </a></li>@endif
                     @if (auth()->user()->roles->estAutoriserDeVoirHistorique)<li> <a href="charts-apexcharts.html"><span style="color: #f80000; margin-right:8px" class="fa fa-clock-rotate-left fa-fw mr-1"></span><span>Historique</span> </a></li> @endif
                  </ul>
               </li>
            @endif
            @if ( auth()->user()->roles->estAutoriserDeVoirCours || auth()->user()->roles->estAutoriserDeVoirAnimations || auth()->user()->roles->estAutoriserDeVoirStatsExports || auth()->user()->roles->estAutoriserDeVoirValiderCertificats)
               <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#club-nav" data-bs-toggle="collapse" href="#"><span style="color: #f59f00; margin-right:10px" class="fa fa-medal fa-fw mr-2 gc-orange"></span><span>Le Club</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                  <ul id="club-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                     @if (auth()->user()->roles->estAutoriserDeVoirCours)<li> <a href="{{route('index_cours')}}"><span style="color: #f59f00; margin-right:10px" class="fa fa-person-running fa-fw mr-1"></span><span>Cours</span> </a></li>@endif
                     @if (auth()->user()->roles->estAutoriserDeVoirAnimations)<li> <a href="club-apexclub.html"><span style="color: #f59f00; margin-right:10px" class="fa fa-person-swimming fa-fw mr-1"></span><span>Animations</span> </a></li>@endif 
                     @if (auth()->user()->roles->estAutoriserDeVoirStatsExports)<li> <a href="club-apexclub.html"><span style="color: #f59f00; margin-right:10px" class="fa fa-chart-line fa-fw mr-1"></span><span>Stats-Exports</span> </a></li>@endif
                     @if (auth()->user()->roles->estAutoriserDeVoirValiderCertificats)<li> <a href="club-apexclub.html"><span style="color: #f59f00; margin-right:10px" class="fa fa-stamp fa-fw mr-1"></span><span>Valider Certificats</span> </a></li>@endif
                  </ul>
               </li>
            @endif
            @if ( auth()->user()->roles->estAutoriserDeVoirGestionProfessionnels || auth()->user()->roles->estAutoriserDeVoirCalculDesSalaires || auth()->user()->roles->estAutoriserDeVoirValiderLesHeures)
               <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#pro-nav" data-bs-toggle="collapse" href="#"><span style="color: #00f900; margin-right:10px" class="fa fa-id-card-clip fa-fw mr-2 gc-lime"></span><span>Professionnels</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                  <ul id="pro-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                     @if (auth()->user()->roles->estAutoriserDeVoirGestionProfessionnels)<li> <a href="{{ route('Professionnels.gestion') }}"><span style="color: #00f900; margin-right:10px" class="fa fa-user-tie fa-fw mr-1"></span><span>Gestion</span> </a></li> @endif
                     @if (auth()->user()->roles->estAutoriserDeVoirCalculDesSalaires)<li> <a href="{{ route('proffesional.calculSalary') }}"><span style="color: #00f900; margin-right:10px" class="fa fa-euro-sign fa-fw mr-1"></span><span>Calcul des salaires</span> </a></li> @endif
                     @if (auth()->user()->roles->estAutoriserDeVoirValiderLesHeures) <li> <a href="{{ route('proffesional.valideHeure') }}"><span style="color: #00f900; margin-right:10px" class="fa fa-clock-rotate-left fa-fw mr-1"></span><span>Valider les heures</span> </a></li> @endif
                  </ul>
               </li>
            @endif
            @if ( auth()->user()->roles->estAutoriserDeVoirGestionDesDroits || auth()->user()->roles->estAutoriserDeVoirParametresGeneraux || auth()->user()->roles->estAutoriserDeVoirSalles || auth()->user()->roles->estAutoriserDeVoirMessageGeneral )
               <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#para-nav" data-bs-toggle="collapse" href="#"><span style="color: #f5f503; margin-right:10px" class="fa fa-screwdriver-wrench fa-fw mr-2 gc-yellow"></span><span>Paramètres</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                  <ul id="para-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                     @if (auth()->user()->roles->estAutoriserDeVoirGestionDesDroits)<li> <a href="pro-chartjs.html"><span style="color: #f5f503; margin-right:10px" class="fa fa-user-check fa-fw mr-1"></span><span>Gestion des droits</span> </a></li>@endif
                     @if (auth()->user()->roles->estAutoriserDeVoirParametresGeneraux)<li> <a href="pro-apexclub.html"><span style="color: #f5f503; margin-right:10px" class="fa fa-map-location-dot fa-fw mr-1"></span><span>Paramètres</span> </a></li>@endif
                     @if (auth()->user()->roles->estAutoriserDeVoirSalles)<li> <a href="pro-apexclub.html"><span style="color: #f5f503; margin-right:10px" class="fa fa-map-location-dot fa-fw mr-1"></span><span>Salles</span> </a></li>@endif
                     @if (auth()->user()->roles->estAutoriserDeVoirMessageGeneral)               <li> <a href="club-apexclub.html"><i style="color: #f5f503;" class="fa-regular fa-message"></i></span><span>Message Général</span> </a></li>@endif
                  </ul>
               </li>
            @endif
        </ul>
     </aside>
    @yield('content')
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('assetsAdmin/js/tinymce.min.js')}}"></script>
<script src="{{asset('assetsAdmin/js/validate.js')}}"></script>
<script src="{{asset('assetsAdmin/js/echarts.min.js')}}"></script>
<script src="{{asset('assetsAdmin/js/chart.min.js')}}"></script>
<script src="{{ asset('assets\js\bootstrap.bundle.min.js') }}"></script>
<script src="{{asset('assetsAdmin/js/apexcharts.min.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="//g.tutorialjinni.com/mojoaxel/bootstrap-select-country/dist/js/bootstrap-select-country.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
<script src="../r_js/jquery.nestable.js"></script>
<script src="{{asset('assetsAdmin/js/main.js')}}"></script>
<script src="../r_js/style.js"></script>

    @livewireScripts
</body>
</html>
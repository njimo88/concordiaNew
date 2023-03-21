<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.adminheader')
<body>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between"> <a href="{{ route('admin.index') }}" class="logo d-flex align-items-center"> <img src="{{ asset('assets/images/logoc.png') }}" alt=""> <span class="d-none d-lg-block">Admin</span> </a> <i class="bi bi-list toggle-sidebar-btn"></i></div>
        <nav class="header-nav ms-auto">
           <ul class="d-flex align-items-center">
              <li class="nav-item dropdown pe-3">
                 <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                  @if($user->image)
                     <img class="rounded-circle" src="{{  $user->image }}" >
                  @elseif ($user->gender == 'male')
                     <img class="rounded-circle" src="{{ asset('assets\images\user.jpg') }}" alt="male">
                  @elseif ($user->gender == 'female')
                     <img class="rounded-circle" src="{{ asset('assets\images\femaleuser.png') }}" alt="female">
                  @endif
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user->name }}</span> 
                  </a>
                 <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                       <h6> {{ $user->name }} {{ $user->lastname }}</h6>
                    </li>
                    
                    <li> <a class="dropdown-item d-flex align-items-center" href="users-profile.html"> <i class="bi bi-person"></i> <span>My Profile</span> </a></li>
                    
                    <li> <a class="dropdown-item d-flex align-items-center" href="users-profile.html"> <i class="bi bi-gear"></i> <span>Account Settings</span> </a></li>
                    
                    <li> <a class="dropdown-item d-flex align-items-center" href="{{ route('A_blog') }}"> <i class="bi bi-box-arrow-right"></i> <span>Retour au blog</span> </a></li>
                 </ul>
              </li>
           </ul>
        </nav>
     </header>
     <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
           <li class="nav-item"> <a class="nav-link " href="{{ route('admin.index') }}"> <i class="bi bi-grid"></i> <span>Acceuil</span> </a></li>
           <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#"><span style="margin-right: 5px; color:#007b00;" class="fa fa-users fa-fw mr-2 gc-green"></span> <span>Utilisateurs</span><i class="bi bi-chevron-down ms-auto"></i> </a>
              <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                 <li> <a href="{{route('utilisateurs.members')}}"> <i style="color: #007b00" class="fa-solid fa-user"></i><span>Membres</span> </a></li>
                 <li> <a href="{{route('utilisateurs.members')}}"> <i style="color: #007b00" class="fa-light fa-at"></i><span>ClickAsso</span> </a></li>
              </ul>
           </li>
           <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#"> <i style="color: #0ef9de" class="bi bi-journal-text"></i><span>Blog</span><i class="bi bi-chevron-down ms-auto"></i></a>
              <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                 <li> <a href="{{route('index')}}"> <i style="color: #0ef9de" class="bi bi-journal-text"></i><span>Articles</span> </a></li>
                 <li> <a href="{{route('index_article_redaction')}}"> <i style="color: #0ef9de" class="fa-solid fa-pen"></i><span>Rédiger un article</span> </a></li>
                 <li> <a href="forms-editors.html"> <i style="color: #0ef9de" class="fa-sharp fa-regular fa-images"></i><span>Gérer le slider</span> </a></li>
                 <li> <a href="{{route('index_article_category')}}"><span style="margin-right: 10px; color: #0ef9de" class="fa fa-list-ol fa-fw mr-1"></span><span>Catégories</span> </a></li>
              </ul>
           </li>
           <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#"> <i style="color: #ff0000" class="fa-solid fa-money-bill"></i><span>Paiement</span><i class="bi bi-chevron-down ms-auto"></i> </a>
              <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                 <li> <a href="{{route('paiement.facture')}}"><i style="color: #ff0000"  class="fa-solid fa-euro-sign"></i><span>Facture</span> </a></li>
                 <li> <a href="tables-data.html"><i style="color: #ff0000"  class="fa-solid fa-ticket"></i></span><span>Réduction</span> </a></li>
              </ul>
           </li>
           <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#"> <i style="color: #f7bac5"  class="bi bi-gem"></i><span>Boutique</span><i class="bi bi-chevron-down ms-auto"></i> </a>
              <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                 <li> <a href="{{ route('index_article') }}"><span style="color: #f7bac5; margin-right:8px" class="fa fa-basket-shopping fa-fw mr-1"></span><span>Article</span> </a></li>
                 <li> <a href="{{ route('A_Categorie') }}"><span style="color: #f7bac5; margin-right:8px" class="fa fa-coins fa-fw mr-1"></span><span>Catégories</span> </a></li>
              </ul>
           </li>
           <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#"> <i style="color: #f80000;" class="fa-regular fa-message"></i><span>Communication</span><i class="bi bi-chevron-down ms-auto"></i> </a>
            <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
               <li> <a href="charts-chartjs.html"><span style="color: #f80000; margin-right:8px" class="fa fa-envelope fa-fw mr-1"></span><span>Envoie de mail</span> </a></li>
               <li> <a href="charts-apexcharts.html"><span style="color: #f80000; margin-right:8px" class="fa fa-clock-rotate-left fa-fw mr-1"></span><span>Historique</span> </a></li>
            </ul>
           </li>
           <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#club-nav" data-bs-toggle="collapse" href="#"><span style="color: #f59f00; margin-right:10px" class="fa fa-medal fa-fw mr-2 gc-orange"></span><span>Le Club</span><i class="bi bi-chevron-down ms-auto"></i> </a>
            <ul id="club-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
               <li> <a href="{{route('index_cours')}}"><span style="color: #f59f00; margin-right:10px" class="fa fa-person-running fa-fw mr-1"></span><span>Cours</span> </a></li>
               <li> <a href="club-apexclub.html"><span style="color: #f59f00; margin-right:10px" class="fa fa-person-swimming fa-fw mr-1"></span><span>Animations</span> </a></li>
               <li> <a href="club-apexclub.html"><span style="color: #f59f00; margin-right:10px" class="fa fa-chart-line fa-fw mr-1"></span><span>Stats-Exports</span> </a></li>
               <li> <a href="club-apexclub.html"><span style="color: #f59f00; margin-right:10px" class="fa fa-stamp fa-fw mr-1"></span><span>Valider Certificats</span> </a></li>
            </ul>
           </li>
           <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#pro-nav" data-bs-toggle="collapse" href="#"><span style="color: #00f900; margin-right:10px" class="fa fa-id-card-clip fa-fw mr-2 gc-lime"></span><span>Professionnels</span><i class="bi bi-chevron-down ms-auto"></i> </a>
            <ul id="pro-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
               <li> <a href="{{ route('Professionnels.gestion') }}"><span style="color: #00f900; margin-right:10px" class="fa fa-user-tie fa-fw mr-1"></span><span>Gestion</span> </a></li>
               <li> <a href="{{ route('proffesional.calculSalary') }}"><span style="color: #00f900; margin-right:10px" class="fa fa-euro-sign fa-fw mr-1"></span><span>Calcul des salaires</span> </a></li>
               <li> <a href="club-apexclub.html"><span style="color: #00f900; margin-right:10px" class="fa fa-stamp fa-fw mr-1"></span><span>Valider les heures</span> </a></li>
            </ul>
           </li>
           <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#para-nav" data-bs-toggle="collapse" href="#"><span style="color: #f5f503; margin-right:10px" class="fa fa-screwdriver-wrench fa-fw mr-2 gc-yellow"></span><span>Paramètres</span><i class="bi bi-chevron-down ms-auto"></i> </a>
            <ul id="para-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
               <li> <a href="pro-chartjs.html"><span style="color: #f5f503; margin-right:10px" class="fa fa-user-check fa-fw mr-1"></span><span>Gestion des droits</span> </a></li>
               <li> <a href="pro-apexclub.html"><span style="color: #f5f503; margin-right:10px" class="fa fa-map-location-dot fa-fw mr-1"></span><span>Paramètres</span> </a></li>
               <li> <a href="club-apexclub.html"><span style="color: #f5f503; margin-right:10px" class="fa fa-map-location-dot fa-fw mr-1"></span><span>Salles</span> </a></li>
               <li> <a href="club-apexclub.html"><i style="color: #f5f503;" class="fa-regular fa-message"></i></span><span>Message Général</span> </a></li>
            </ul>
           </li>
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
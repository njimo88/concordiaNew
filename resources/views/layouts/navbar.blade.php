
<?php
use Illuminate\Support\Facades\DB;
use App\Models\Shop_category;
?>
@if (!in_array(Route::currentRouteName(), ['register', 'login']))
    <!-- ======= Header ======= -->
    @php
      $categories = Shop_category::select('id_shop_category', 'name', 'image')
               ->where('active', 1)
               ->get();
    
    @endphp
     <div class="modal fade" id="contactModal" tabindex="-1" style="z-index:100000" data-backdrop="static" data-keyboard="false" aria-labelledby="contactModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-lg">
          <div style="background-color: #d8e3ff" class="modal-content">
              <div class="modal-header border m-4 mb-0" style="background-color: white;">
                  <h5 class="modal-title" id="contactModalLabel" style=" font-size: 1.5em;font-weight: 400; font-family: Arial, Helvetica, sans-serif">Envoyer un Message</h5>
                  <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form action="" id="sendMailForm" method="post" class="form-example">
                  <div class="modal-body p-5 py-3">
                  <fieldset class="form-group">
                              <div class="form cf form-group row">

                              <div class="plan cf form-group col-sm-10">

                                  <input type="radio" name="emailcontact[]" id="bureau" value="1" checked>
                                  <label class="bureau-label four col" for="bureau">
                                      Bureau
                                  </label>

                                  <input type="radio" name="emailcontact[]" id="tresorier" value="2">
                                  <label class="tresorier-label four col" for="tresorier">
                                      Trésorier
                                  </label>

                                  <input type="radio" name="emailcontact[]" id="president" value="3">
                                  <label class="president-label four col" for="president">
                                      Président
                                  </label>

                              </div>
                              </div>
                          </fieldset>
                          <div class="form-row row mt-5">
                              <div class="form-group col-md-5">
                              <label class="text-dark" for="name">&nbsp;Prénom - Nom</label>
                              <input placeholder="Prénom - Nom" class="form-control mt-2" type="text" name="name" value="" required>
                              </div>
                              <div class="form-group col-md-7">
                              <label class="text-dark" for="email">&nbsp;Email</label>
                              <input placeholder="Email" class="form-control mt-2" type="email" name="email" value="" required>
                              </div>
                          </div>
                              <div class="form-row">
                          <div class="form-group col-md-12">
                              <label class="text-dark" for="message">&nbsp;Votre Message</label>
                              <textarea style="width: 100%; height: 150px;" placeholder="Votre message" class="form-control mt-2" required name="message" value=""></textarea>
                          </div>
                      </div>
                      <div class='form-row mt-3'>
                          <div class='form-group col-md-12' style="text-align:-webkit-center">
                              <div class='g-recaptcha' name="captchaTest" data-sitekey='6Lft5c8UAAAAAORHh9eDop9d3C8R2IJfrBqc0Sx3'></div>
                          </div>
                      </div>
                      <div class="row d-flex justify-content-between m-1 mt-4">
                        <div class="col-3 d-flex justify-content-center">
                          <button type="button" class="cancel btn btn-secondary" data-dismiss="modal">Annuler</button>
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

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      @guest
      <a href="{{ route('A_blog') }}" class="logo d-flex align-items-center">
        <img style="max-width: 140px !important" src="{{ asset('assets\images\logoc.png') }}" alt="">
      </a>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="{{ route('A_blog') }}"><span><img src="{{ asset("/assets/images/Accueil.png") }}" width="24">&nbsp;Acceuil</span></a></li>
          <li class="dropdown"><a href="#"><span><img src="{{ asset("/assets/images/Club.png") }}" width="24">&nbsp;Le Club</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li class="dropdown">
                <a href="{{ route('Simple_Post',13000) }}"><span><img src="{{ asset("/assets/images/Comite.png") }}" width="24">&nbsp;Comité Directeur</span></a>
              </li>
              <li class="dropdown">
                <a href="{{ route('Simple_Post',13002) }}"><span><img src="{{ asset("/assets/images/Partenaires.png") }}" width="24">&nbsp;Partenaires</span></a>
              </li>
              <li class="dropdown">
                <a href="#"><span><img src="{{ asset("/assets/images/Communication.png") }}" width="24">&nbsp;Communication</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                <ul>
                  <li><a target="_blank" href="https://www.facebook.com/GymConcordia"><span><i class="bi bi-facebook"></i>&nbsp;&nbsp;Facebook</span></a></li>
                  <li><a target="_blank" href="https://www.instagram.com/gym_concordia"><span><i class="bi bi-instagram"></i>&nbsp;&nbsp;Instagram</span></a></li>
                  <li><a target="_blank" href="https://www.youtube.com/user/GymConcordia"><span><i class="bi bi-youtube"></i>&nbsp;&nbsp;Youtube</span></a></li>
                  <li><a target="_blank" href="#"><span><i class="bi bi-camera-video"></i>&nbsp;&nbsp;Videos</span></a></li>
                  <li><a target="_blank" href="#"><span><i class="bi bi-newspaper"></i>&nbsp;&nbsp;Presse</span></a></li>
                </ul>
                
              </li>
              <li class="dropdown">
                <a href="#"><span><img src="{{ asset("/assets/images/Reglements.png") }}" width="24">&nbsp;Mentions Légales</span></a>
              </li>
              <li class="dropdown">
                <a href="#" class="liens"  data-toggle="modal" data-target="#contactModal"><span><img src="{{ asset("/assets/images/message.png") }}" width="24">&nbsp;Prendre contact</span></a>
              </li>
              
            </ul>
          </li>
          <li class="dropdown">
                <a href="{{ route('index_categorie') }}">
                    <span><img src="{{ asset("/assets/images/Inscriptions.png") }}" width="24">&nbsp;Achats</span>
                    @if($categories->filter(function ($category) {
                            return strlen($category->id_shop_category) === 1;
                        })->count() > 0)
                        <i class="bi bi-chevron-down dropdown-indicator"></i>
                    @endif
                </a>
                <ul>
                    <!-- First level dropdown: categories with one digit id -->
                    @foreach($categories->filter(function ($category) {
                        return strlen($category->id_shop_category) === 1;
                    }) as $category)
                    <li class="dropdown">
                        <a href="{{ route('sous_categorie', ['id' =>  $category->id_shop_category]) }}">
                            <span><img src="{{ $category->image }}" width="24">&nbsp;{{ $category->name }}</span>
                            @if($categories->filter(function ($subCategory) use ($category) {
                                    return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
                                })->count() > 0)
                                <i class="bi bi-chevron-down dropdown-indicator"></i>
                            @endif
                        </a>
                        <!-- Second level dropdown: categories with three digits id -->
                        <ul>
                            @foreach($categories->filter(function ($subCategory) use ($category) {
                                return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
                            }) as $subCategory)
                            <li class="dropdown">
                                <a href="{{ route('sous_categorie', ['id' =>  $subCategory->id_shop_category]) }}">
                                    <span><img src="{{ $subCategory->image }}" width="24">&nbsp;{{ $subCategory->name }}</span>
                                    @if($categories->filter(function ($subSubCategory) use ($subCategory) {
                                            return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
                                        })->count() > 0)
                                        <i class="bi bi-chevron-down dropdown-indicator"></i>
                                    @endif
                                </a>
                                <!-- Third level dropdown: categories with four digits id -->
                                <ul>
                                    @foreach($categories->filter(function ($subSubCategory) use ($subCategory) {
                                        return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
                                    }) as $subSubCategory)
                                    <li>
                                        <a href="{{ route('sous_categorie', ['id' =>  $subSubCategory->id_shop_category]) }}">
                                            <span><img src="{{ $subSubCategory->image }}" width="24">&nbsp;{{ $subSubCategory->name }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </li>
        
          <li class="dropdown"><a href="#"><span><img src="{{ asset("/assets/images/Informations.png") }}" width="24">&nbsp;Nos Activités</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
            <ul>
              <li class="dropdown"><a href="#"><span><img src="{{ asset("/assets/images/Sections.png") }}" width="24">&nbsp;Nos Sections&nbsp;<i class="bi bi-chevron-down dropdown-indicator"></i></span></a>
                  <!-- First level dropdown: categories with one digit id -->
                  @php
                      $category = Shop_category::select('id_shop_category', 'name', 'image')
                                ->where('id_shop_category', 1)
                                ->first();
                  @endphp
                    <ul>
                        @foreach($categories->filter(function ($subCategory) use ($category) {
                            return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
                        }) as $subCategory)
                        <li class="dropdown">
                            <a href="{{ route('sous_categorie', ['id' =>  $subCategory->id_shop_category]) }}">
                                <span><img src="{{ $subCategory->image }}" width="24">&nbsp;{{ $subCategory->name }}</span>
                                @if($categories->filter(function ($subSubCategory) use ($subCategory) {
                                        return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
                                    })->count() > 0)
                                    <i class="bi bi-chevron-down dropdown-indicator"></i>
                                @endif
                            </a>
                            <!-- Third level dropdown: categories with four digits id -->
                            <ul>
                                @foreach($categories->filter(function ($subSubCategory) use ($subCategory) {
                                    return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
                                }) as $subSubCategory)
                                <li>
                                    <a href="{{ route('sous_categorie', ['id' =>  $subSubCategory->id_shop_category]) }}">
                                        <span><img src="{{ $subSubCategory->image }}" width="24">&nbsp;{{ $subSubCategory->name }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
              </li>
              <li><a href="#"><span><img src="{{ asset("/assets/images/sport-etudes.png") }}" width="24">&nbsp;Sport-Etudes</span></a>
              <li><a href="#"><span><img src="{{ asset("/assets/images/HorairesBureau.png") }}" width="24">&nbsp;Horaires Bureau</span></a>
            </ul>
          </li>
          <li><a href="#"><span><img src="{{ asset("/assets/images/Reglements (1).png") }}" width="24">&nbsp;Recherche</span></a></li>
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
          <img style="max-width: 140px !important" src="{{ asset('assets\images\logoc.png') }}" alt="">
        </a>
        <nav id="navbar" class="navbar">
          <ul>
            <li><a href="{{ route('A_blog') }}"><span><img src="{{ asset("/assets/images/Accueil.png") }}" width="24">&nbsp;Acceuil</span></a></li>
            <li class="dropdown"><a href="#"><span><img src="{{ asset("/assets/images/Club.png") }}" width="24">&nbsp;Le Club</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
              <ul>
                <li class="dropdown">
                  <a href="{{ route('Simple_Post',13000) }}"><span><img src="{{ asset("/assets/images/Comite.png") }}" width="24">&nbsp;Comité Directeur</span></a>
                </li>
                <li class="dropdown">
                  <a href="{{ route('Simple_Post',13002) }}"><span><img src="{{ asset("/assets/images/Partenaires.png") }}" width="24">&nbsp;Partenaires</span></a>
                </li>
                <li class="dropdown">
                  <a href="#"><span><img src="{{ asset("/assets/images/Communication.png") }}" width="24">&nbsp;Communication</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                  <ul>
                    <li><a target="_blank" href="https://www.facebook.com/GymConcordia"><span><i class="bi bi-facebook"></i>&nbsp;&nbsp;Facebook</span></a></li>
                    <li><a target="_blank" href="https://www.instagram.com/gym_concordia"><span><i class="bi bi-instagram"></i>&nbsp;&nbsp;Instagram</span></a></li>
                    <li><a target="_blank" href="https://www.youtube.com/user/GymConcordia"><span><i class="bi bi-youtube"></i>&nbsp;&nbsp;Youtube</span></a></li>
                    <li><a target="_blank" href="#"><span><i class="bi bi-camera-video"></i>&nbsp;&nbsp;Videos</span></a></li>
                    <li><a target="_blank" href="#"><span><i class="bi bi-newspaper"></i>&nbsp;&nbsp;Presse</span></a></li>
                  </ul>
                  
                </li>
                <li class="dropdown">
                  <a href="#"><span><img src="{{ asset("/assets/images/Reglements.png") }}" width="24">&nbsp;Mentions Légales</span></a>
                </li>
                <li class="dropdown">
                  <a href="#" class="liens"  data-toggle="modal" data-target="#contactModal"><span><img src="{{ asset("/assets/images/message.png") }}" width="24">&nbsp;Prendre contact</span></a>
                </li>
                
              </ul>
            </li>
            <li class="dropdown">
                  <a href="{{ route('index_categorie') }}">
                      <span><img src="{{ asset("/assets/images/Inscriptions.png") }}" width="24">&nbsp;Achats</span>
                      @if($categories->filter(function ($category) {
                              return strlen($category->id_shop_category) === 1;
                          })->count() > 0)
                          <i class="bi bi-chevron-down dropdown-indicator"></i>
                      @endif
                  </a>
                  <ul>
                      <!-- First level dropdown: categories with one digit id -->
                      @foreach($categories->filter(function ($category) {
                          return strlen($category->id_shop_category) === 1;
                      }) as $category)
                      <li class="dropdown">
                          <a href="{{ route('sous_categorie', ['id' =>  $category->id_shop_category]) }}">
                              <span><img src="{{ $category->image }}" width="24">&nbsp;{{ $category->name }}</span>
                              @if($categories->filter(function ($subCategory) use ($category) {
                                      return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
                                  })->count() > 0)
                                  <i class="bi bi-chevron-down dropdown-indicator"></i>
                              @endif
                          </a>
                          <!-- Second level dropdown: categories with three digits id -->
                          <ul>
                              @foreach($categories->filter(function ($subCategory) use ($category) {
                                  return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
                              }) as $subCategory)
                              <li class="dropdown">
                                  <a href="{{ route('sous_categorie', ['id' =>  $subCategory->id_shop_category]) }}">
                                      <span><img src="{{ $subCategory->image }}" width="24">&nbsp;{{ $subCategory->name }}</span>
                                      @if($categories->filter(function ($subSubCategory) use ($subCategory) {
                                              return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
                                          })->count() > 0)
                                          <i class="bi bi-chevron-down dropdown-indicator"></i>
                                      @endif
                                  </a>
                                  <!-- Third level dropdown: categories with four digits id -->
                                  <ul>
                                      @foreach($categories->filter(function ($subSubCategory) use ($subCategory) {
                                          return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
                                      }) as $subSubCategory)
                                      <li>
                                          <a href="{{ route('sous_categorie', ['id' =>  $subSubCategory->id_shop_category]) }}">
                                              <span><img src="{{ $subSubCategory->image }}" width="24">&nbsp;{{ $subSubCategory->name }}</span>
                                          </a>
                                      </li>
                                      @endforeach
                                  </ul>
                              </li>
                              @endforeach
                          </ul>
                      </li>
                      @endforeach
                  </ul>
              </li>
          
            <li class="dropdown"><a href="#"><span><img src="{{ asset("/assets/images/Informations.png") }}" width="24">&nbsp;Nos Activités</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
              <ul>
                <li class="dropdown"><a href="#"><span><img src="{{ asset("/assets/images/Sections.png") }}" width="24">&nbsp;Nos Sections&nbsp;<i class="bi bi-chevron-down dropdown-indicator"></i></span></a>
                    <!-- First level dropdown: categories with one digit id -->
                    @php
                        $category = Shop_category::select('id_shop_category', 'name', 'image')
                                  ->where('id_shop_category', 1)
                                  ->first();
                    @endphp
                      <ul>
                          @foreach($categories->filter(function ($subCategory) use ($category) {
                              return strlen($subCategory->id_shop_category) === 3 && strpos($subCategory->id_shop_category, $category->id_shop_category) === 0;
                          }) as $subCategory)
                          <li class="dropdown">
                              <a href="{{ route('sous_categorie', ['id' =>  $subCategory->id_shop_category]) }}">
                                  <span><img src="{{ $subCategory->image }}" width="24">&nbsp;{{ $subCategory->name }}</span>
                                  @if($categories->filter(function ($subSubCategory) use ($subCategory) {
                                          return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
                                      })->count() > 0)
                                      <i class="bi bi-chevron-down dropdown-indicator"></i>
                                  @endif
                              </a>
                              <!-- Third level dropdown: categories with four digits id -->
                              <ul>
                                  @foreach($categories->filter(function ($subSubCategory) use ($subCategory) {
                                      return strlen($subSubCategory->id_shop_category) === 4 && strpos($subSubCategory->id_shop_category, $subCategory->id_shop_category) === 0;
                                  }) as $subSubCategory)
                                  <li>
                                      <a href="{{ route('sous_categorie', ['id' =>  $subSubCategory->id_shop_category]) }}">
                                          <span><img src="{{ $subSubCategory->image }}" width="24">&nbsp;{{ $subSubCategory->name }}</span>
                                      </a>
                                  </li>
                                  @endforeach
                              </ul>
                          </li>
                          @endforeach
                      </ul>
                </li>
                <li><a href="#"><span><img src="{{ asset("/assets/images/sport-etudes.png") }}" width="24">&nbsp;Sport-Etudes</span></a>
                <li><a href="#"><span><img src="{{ asset("/assets/images/HorairesBureau.png") }}" width="24">&nbsp;Horaires Bureau</span></a>
              </ul>
            </li>
            <li><a href="#"><span><img src="{{ asset("/assets/images/Reglements (1).png") }}" width="24">&nbsp;Recherche</span></a></li>
            <li class="dropdown"><a href="#"><span>{{ $user->name }}</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
              <ul>
                @if (auth()->user()->role >= 90)
                <li><a href="{{ route('admin.index') }}">Administration</a></li>
             @endif
              
                <li><a href="{{ route('users.family') }}">Ma famille</a></li>
                <li><a href="{{ route('users.FactureUser') }}">Mes Factures/Devis</a></li>
                <li><a href="{{ route('logout') }}"onclick="event.preventDefault();document.getElementById('logout-form').submit();">Déconnecter</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </ul>
            </li>
            <li><a href="{{ route('panier',auth()->user()->user_id) }}"><i style="color:white;font-size:1.2rem " class="mx-1 fa-sharp fa-regular fa-cart-shopping"></i>Panier</a></li>
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
      float:left;
      margin: 1% 0 1% 1.6%;
    }
    
    .col:first-of-type { margin-left: 0; }
    
    .four { width: 32.26%; max-width: 32.26%;}
    
    .form .plan input {
      display: none;
    }
    
    .form label{
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
    
    .form .plan input:checked + label {
      border: 3px solid #333;
      background-color: #3399FF;
    }
    
    .form .plan input:checked + label:after {
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
    
    .submit{
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
    
    .submit:hover{
      cursor: pointer;
      transform: rotateX(360deg);
    }
    
    .cancel{
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
    
    .cancel:hover{
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
    
    
    
    
        @media screen and (min-width: 1250px) {
          
            .taille-caract {
              font-size : 14px;
              justify-content: center;
            }
        }
    
        @media screen and (max-width: 1250px) {
           
            .taille-caract {
              font-size : 10px;
              justify-content: center;
            }
        }
        .liens:hover {
            text-decoration: none;
            color: gray;
            transition: all .1s ease-out;
        }
    
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

@endif
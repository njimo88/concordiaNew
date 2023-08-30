@extends('layouts.app')

@section('content')
@php
    require_once(app_path().'/fonction.php');
@endphp
<!-- Modal -->
<div class="modal fade " id="commanderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content text-center" id="commanderModalContainer">
            
        </div>
        <!--/.Content-->
      </div>
  </div>

 <!-- main --> 
<div class="main-wrapper">
    <div class="container">

        <div class="product-div">
            <div class="product-div-left">
                <div class="left-content"> <!-- New container -->
                    @if($articl->type_article == 1)
                        <div class="img-container">
                            @foreach($teachers as $teacher)
                                <div class="teacher-card">
                                    <img class="teacher-image" src="{{ $teacher->image }}" alt="{{ $teacher->name }} {{ $teacher->lastname }}">
                                    <span class="teacher-name">{{ $teacher->name }} {{ $teacher->lastname }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="schedule-section">
                            <strong>Horaires</strong>
                            <ul class="schedule-list">
                                @foreach($schedules as $schedule)
                                    <li>{{ $schedule }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="locations-section">
                            <strong>Lieu :</strong>
                            <ul class="location-list">
                                @foreach($locations as $location)
                                    <li><a style="color: #182881" href="https://www.google.com/maps/search/{{ urlencode($location) }}" target="_blank">{{ $location }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                    @elseif($articl->type_article == 2)
                        <!-- Ici, vous affichez l'image directement comme vous l'avez mentionné -->
                        <img style="max-height: 400px" src="{{ $articl->image }}" alt="{{ $articl->name }}">
                    @endif
                    <!-- Inserted user-status-section here -->
                <div class="user-status-section mt-2">
                    <div class="card user-card">
                        <div class="card-body">
                            @guest
                                <h4 class="card-title">{{ $articl->type_article == 2 ? 'Commander' : 'Inscrire' }}</h4>
                                <p class="info-message">Se connecter pour commander</p>
                                <a href="{{ route('login') }}" class="btn">Se connecter</a>
                            @else
                                <h4 class="card-title">{{ $articl->type_article == 2 ? 'Commander' : 'Inscrire' }}</h4>
                                @if (count($selectedUsers) > 0)
                                    <select onchange="updatePriceToDisplay()" class="select-form" name="buyers" id="buyers">
                                        @foreach ($selectedUsers as $user)
                                            <option value="{{ $user->user_id }}">{{ $user->lastname }} {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <button data-shop-id="{{ $articl->id_shop_article }}" class="btn commanderModal">Commander</button>
                                @else
                                    <p class="info-message">Votre famille ne correspond pas à cet article.</p>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
                </div>
            </div>
            
            <div class="product-div-right">
                <span class="product-name">{{ $articl->title }}</span>
                <span class="product-price">
                    @php
                        $reducedPrice = getReducedPriceGuest($articl->id_shop_article, $articl->totalprice);
                        $priceToDisplay = $reducedPrice ? $reducedPrice : $articl->totalprice;
                        $DescReduc = getFirstReductionDescriptionGuest($articl->id_shop_article);
                    @endphp
                    @if ($reducedPrice && $reducedPrice != $articl->totalprice)
                        <span class="original-price">{{ number_format($articl->totalprice, 2, ',', ' ') }} €</span>
                        <span class="reduced-price">{{ number_format($priceToDisplay, 2, ',', ' ') }} €</span>
                        @if ($DescReduc)
                            <div class="desc-reduc-ribbon">{{ $DescReduc }}</div>
                        @endif
                    @else
                        <span class="reduced-price">{{ number_format($articl->totalprice, 2, ',', ' ') }} €</span>
                    @endif
                </span>
                <div style="margin-top: 20px;">
                  @include('availability', ['data' => $articl])
                </div>
                <div class="product-rating">
                    <span><i class="fas fa-star"></i></span>
                    <span><i class="fas fa-star"></i></span>
                    <span><i class="fas fa-star"></i></span>
                    <span><i class="fas fa-star"></i></span>
                    <span><i class="fas fa-star-half-alt"></i></span>
                    <span>(350 votes)</span>
                </div>
                
                <p class="product-description">{!! $articl->description !!}</p>
                
                
            </div>
        </div>

        <div class="row">

            <div class="col-md-3 date-section">
                @if(isset($Data_lesson['start_date'][0]))
                    @php
                        $startDate = $Data_lesson['start_date'][0];
                    @endphp
                    <div class="resume-date">
                        Date de reprise : <span class="start-date">{{ date('d/m/Y', strtotime($startDate)) }}</span>
                    </div>
                @endif
            </div>

            
            <div class="col-md-6 additional-info-section">
                @if(isset($articl->information_additionel))
                    <strong>Informations additionnelles :</strong>
                    <p>{!! $articl->information_additionel !!}</p>
                @endif
            </div>

        </div>
    </div>
</div>

<style>
    /* Reset some default styles */
    body, html {
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
    }

    .user-status-section {
        width: 80%;
    }

    .user-card {
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .user-card:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    /* Styling for card body */
    .card-body {
        padding: 20px;
        background-color: #f9f9f9;
        text-align: center;
    }

    /* Title styling */
    .card-title {
        margin-bottom: 15px;
        color: #333;
    }

    
    
    .teacher-name {
        text-align: center;
        margin: 0 auto;
        display: block;
    }

    /* Select form styling */
    .select-form {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
        appearance: none;
    }

    /* Message styling */
    .info-message {
        margin: 10px 0;
        color: #888;
    }
    /* Main wrapper styles */
    .main-wrapper {
        min-height: 100vh;
        background-color: #f1f1f1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .original-price {
    text-decoration: line-through;
    opacity: 0.6;
    margin-right: 5px;
}

.reduced-price {
    color: #9e050e;
    font-size: 1.2em;
    font-weight: bold;
}

.desc-reduc-ribbon {
    display: inline-block;
    background-color: #9e050e;
    color: white;
    padding: 2px 10px;
    margin-left: 5px;
    border-radius: 5px;
    font-size: 0.9em;
    position: relative;
    top: 3px;  /* Slightly offset to visually align with prices */
    box-shadow: 1px 1px 3px rgba(0,0,0,0.15); /* Optional: Adds a slight shadow for depth */
}



    /* Container styles */
    .container {
        max-width: 1200px;
        padding: 0 1rem;
        margin: 0 auto;
    }

    /* Product div styles */
    .product-div {
        margin: 1rem 0;
        padding: 2rem 0;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        background-color: #fff;
        border-radius: 3px;
        column-gap: 10px;
    }

    /* Product div left styles */
    .product-div-left {
        padding: 20px;
    }

    /* Product div right styles */
    .product-div-right {
        padding: 20px;
    }

    
    .img-container img {
    display: block; 
    width: 200px;
    margin: 0 auto; 
}

.left-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px; 
}

.schedule-section, .locations-section {
    width: 80%; 
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.schedule-list, .location-list {
    list-style-type: none;
    padding: 0;
    margin: 10px 0;
}


    .hover-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        margin-top: 32px;
    }

    .hover-container div {
        border: 2px solid rgba(252, 160, 175, 0.7);
        padding: 1rem;
        border-radius: 3px;
        margin: 0 4px 8px 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

   
    .hover-container div img {
        width: 50px;
        cursor: pointer;
    }

    /* Product name styles */
    .product-name {
        font-size: 26px;
        margin-bottom: 22px;
        font-weight: 700;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    /* Product price styles */
    .product-price {
        font-weight: 500;
        font-size: 24px;
        opacity: 0.9;
    }

    .product-price span {
        display: block;
    }

    .original-price {
        text-decoration: line-through;
        color: #414040;
        font-size: 1.3rem;
    }

    .reduced-price {
        color: #007BFF;
        font-size: 2rem;
        font-weight: bold;
        margin: 10px 0;
    }

    .price {
        font-size: 2rem;
        font-weight: bold;
    }

    /* Product rating styles */
    .product-rating {
        display: flex;
        align-items: center;
        margin-top: 12px;
    }

    .product-rating span {
        margin-right: 6px;
    }

    /* Product description styles */
    .product-description {
        font-weight: 300;
        line-height: 1.6;
        opacity: 0.9;
        margin-top: 22px;
    }

    /* Button groups styles */
    .btn-groups {
        margin-top: 22px;
    }

    .btn-groups button {
        display: inline-block;
        font-size: 16px;
        font-family: inherit;
        text-transform: uppercase;
        padding: 15px 16px;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-groups button .fas {
        margin-right: 8px;
    }

    .add-cart-btn {
        background-color: #FF9F00;
        border: 2px solid #FF9F00;
        margin-right: 8px;
    }

    .add-cart-btn:hover {
        background-color: #fff;
        color: #FF9F00;
    }

    .buy-now-btn {
        background-color: #000;
        border: 2px solid #000;
    }

    .buy-now-btn:hover {
        background-color: #fff;
        color: #000;
    }
    @media screen and (max-width: 992px) {  /* Ajustez le seuil selon vos besoins */
    .product-div-right {
        grid-row: 1;
        grid-column: 1 / span 2; /* Cela prend toute la largeur */
    }

    .product-div-left {
        grid-row: 2;
        grid-column: 1 / span 2; /* Cela prend toute la largeur */
    }
}

    /* Media queries */
    @media screen and (max-width: 992px) {
        .product-div {
            grid-template-columns: 100%;
        }

        .product-div-right {
            text-align: justify;
        }

        .product-rating {
            justify-content: center;
        }

        .product-description {
            max-width: 400px;
            margin-right: auto;
            margin-left: auto;
        }
    }

    @media screen and (max-width: 400px) {
        .btn-groups button {
            width: 100%;
            margin-bottom: 10px;
        }
    }

    @media (max-width: 768px) {
    .product-price {
        font-size: 18px;
    }

    .product-name {
        font-size: 20px;
        margin-bottom: 15px;
    }

    .original-price {
        font-size: 1.1rem;
    }

    .reduced-price, .price {
        font-size: 1.5rem;
    }
}
</style>




<script>
    function updatePriceToDisplay() {
          var select = document.getElementById("buyers");
          var selectedUserId = select.options[select.selectedIndex].value;
          var url = "/get-reduced-price/" + selectedUserId + "/" +  {{ $articl->id_shop_article }} ;
          var xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
          var reducedPrice = JSON.parse(this.responseText);
          var priceHTML = "";
          
          if ({{ $articl->totalprice }} != reducedPrice) {
          priceHTML = "<span style=\"text-decoration: line-through;\">";
          priceHTML += ({{ $articl->totalprice }}).toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
          priceHTML += " </span>";
          priceHTML += "<span style=\"color: red; font-size: x-large; font-weight: bold;\">";
          priceHTML += reducedPrice.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
          priceHTML += " </span>";
          priceHTML = "<span class='px-2' style='color: red; font-size: small;'>";
          priceHTML += "{{ $DescReduc }}";
          priceHTML += " </span>";
          } else {
          priceHTML += priceHTML += '<span style="color: red; font-size: x-large; font-weight: bold;">' + ({{ $articl->totalprice }}).toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
          priceHTML += " </span>";
          
          }
          
          
          document.getElementById("priceToDisplay").innerHTML = priceHTML;
          }
          };
          xhttp.open("GET", url, true);
          xhttp.send();
          }
</script>

@endsection

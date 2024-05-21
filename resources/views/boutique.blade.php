@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="breadcrumb">
                <a class="breadcrumb__step breadcrumb__step--active" href="{{ route('shop_categories') }}">Categories</a>
    
                @foreach($breadcrumb as $crumb)
                    <a class="breadcrumb__step" href="{{ route('sub_shop_categories', ['id' => $crumb->id_shop_category]) }}">
                        {{$crumb->name}}
                    </a>
                @endforeach
            </div>
        </div>
        
        <div class="col-lg-6 col-md-12 mt-lg-0 mt-3 d-flex justify-content-lg-end">
            @if(auth()->user() && auth()->user()->role >= 90)
                <a href="{{ route('generatePDFboutique', ['id' => $info2->id_shop_category]) }}" target="_blank">
                    <img src="{{ asset('assets/images/pdf.png') }}" alt="Télécharger en PDF" class="btn-img">
                </a>
            @endif
       </div>
    </div>

    @if($message_general)
        {!! $message_general !!}
    @endif
    
    <h2 class="heading  mt-3">{{ $info2->name }}</h2>
    <div style="display: block">
        <p class="sub-heading mb-5"> {!! $info2->description !!} </p>
    </div>  
    <hr>
    <div class="box-container mt-5">
        @foreach($articles as $article)
            <div class="box">
        <a href="{{ route('singleProduct',['id' => $article->id_shop_article]) }}" target="_blank">

            <div class="badge-container">
                @if($article->nouveaute == 1)
                    <div class="new-badge"><img src="{{ asset('assets\images\Nouveau.png') }}" alt=""></div>
                @endif
            </div>

                <img src="{{ asset($article->image) }}" alt="{{ $article->title }}">
                <h3>{{ $article->title }}</h3>
                @if ($article->stock_actuel > 0 && $article->stock_actuel <= $article->alert_stock || $article->stock_actuel <= 0)
                    <div class="availability-badge @if ($article->stock_actuel > 0 && $article->stock_actuel <= $article->alert_stock) availability-warning @else availability-danger @endif">
                        @if ($article->stock_actuel > 0 && $article->stock_actuel <= $article->alert_stock)
                                {{$article->stock_actuel}} restant(s)
                        @elseif ($article->stock_actuel <= 0)
                                Complet
                        @endif
                    </div>
                @endif
                
                <style>
                   
                </style>
                @php
                    $reducedPrice = getReducedPriceGuest($article->id_shop_article, $article->totalprice);
                    $priceToDisplay = $reducedPrice ? $reducedPrice : $article->totalprice;
                    $DescReduc = getFirstReductionDescriptionGuest($article->id_shop_article);
                @endphp
                <div class="price-section">
                    @if ($reducedPrice && $reducedPrice != $article->totalprice)
                        <span style="text-decoration: line-through;">{{ number_format($article->totalprice, 2, ',', ' ') }} €</span>
                        <span style="color: #007BFF; font-size: x-large; font-weight: bold;">
                            {{ number_format($priceToDisplay, 2, ',', ' ') }} €</span>
                    @else
                    <div class="price">{{ number_format($article->totalprice, 2) }} €</div>
                    @endif
                    @if ($DescReduc)
                        <div class="discount-description">{{ $DescReduc }}</div>
                    @endif
                </div>
    
                <a  href="{{ route('singleProduct',['id' => $article->id_shop_article]) }}" class="btn mt-2">Acheter</a>
            </div>
        </a>
        @endforeach
    </div>
    

</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap');

    *{
        font-family: 'Poppins', sans-serif;
        margin:0; padding:0;
        box-sizing: border-box;
        outline: none; border:none;
        text-decoration: none;
        text-transform: capitalize;
        transition: .3s ease;
    }
    .availability-badge {
  position: absolute;
  transform: rotate(-45deg);
  top: 10px;
    left: -25px;
  color: white;
  font-size: 14px;
  padding: 5px 15px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  z-index: 1;
}

.availability-warning {
    background-color: #FFC107;
}

.availability-danger {
    background-color: #ef0514;
}

 /*   .container .box-container .box .new-badge {
    position: absolute;
    top: 10px;
    left: -25px;
    background-color: #e7f00d;  
    color: black;
    font-size: 14px;
    padding: 5px 15px;
    transform: rotate(-45deg);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    z-index: 1;
}
*/

.badge-container {
    position: absolute;
    top: -15px;
right: -9px;
    z-index: 1;
}

.new-badge img {
    width: 100px    !important; 
    height: auto !important; 
}
    .container{
    background: #f6f8fa;
    padding:15px 9%;
    padding-bottom: 100px;
    max-width: 100% !important;
    min-height: 100vh;
}


.price-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    margin-top: 10px;  /* adjust as needed */
    margin-bottom: 20px;  /* adjust as needed */
}

.discount-description {
    font-size: x-small;
    color: red;
    padding: 4px;
    text-align: center;
    margin-bottom: 30px;
}


    .container .heading{
        text-align: center;
        padding-bottom: 5px;
        color:#6C63FF;
        text-shadow: 0 5px 10px rgba(0,0,0,.15);
        font-size: 40px;
    }

    .container .sub-heading{
        text-align: center !important;
        color: #79797a;
        font-size: 16px;
        margin-bottom: 50px !important;
    margin-left: auto; /* centrage horizontal gauche */
    margin-right: auto; /* centrage horizontal droit */
    }

    .container .box-container{
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap:25px;
    }
    .container .box-container .box{
        box-shadow: 0 8px 15px rgba(0,0,0,.12);
        border-radius: 10px;
        background: #fff;
        text-align: center;
        padding:30px 20px;
        cursor: pointer;
        position: relative;
        min-height: auto;
        padding-top: 50px;
        border: solid 1px gray;
    }

    .container .box-container .box img{
        height: 200px;
        margin-bottom: 15px;
    }

    .container .box-container .box h3{
        color:#444;
        font-size: 18px;
        padding:10px 0;
    }

    .container .box-container .box p{
        color:#777;
        font-size: 16px;
        line-height: 1.9;
        height: 150px;
        overflow: hidden;
    }

    .container .box-container .box .price{
        font-size: 24px;
        color: #6C63FF;
        padding: 15px 0;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .container .box-container .box .btn{
        margin-top: 15px;
        display: inline-block;
        background:#6C63FF;
        color:#fff;
        font-size: 17px;
        border-radius: 7px;
        padding: 10px 30px;
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
    }

    .container .box-container .box .btn:hover{
        background: #555;
        letter-spacing: 1px;
    }

    .container .box-container .box:hover{
        box-shadow: 0 10px 25px rgba(0,0,0,.18);
        transform: translateY(-5px);
    }

    
    @media (max-width:768px){
    .container{
        padding:20px;
    }
    .container .box-container{
        grid-template-columns: 1fr; 
    }
    .container .box-container .box{
        min-height: auto; 
    }

    .container .box-container .box h3 {
        font-size: 18px; 
    }

    .container .box-container .box .price {
        font-size: 18px;
    }
}


</style>
@endsection

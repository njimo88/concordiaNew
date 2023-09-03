<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700&display=swap');
        * {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-transform: capitalize;
        }
        .container {
            padding: 15px 9%;
            max-width: 800px;
            min-height: 100vh;
        }
        .container .heading {
            text-align: center;
            padding-bottom: 5px;
            color: #6C63FF;
            font-size: 20px;
        }
        .container table {
            width: 100%;
            border-collapse: collapse;
        }
        .container td {
            width: 33%;
            padding: 10px;
            vertical-align: top;
        }
        .box {
            border: 1px solid #ccc;
            border-radius: 10px;
            background: #fff;
            text-align: center;
            padding: 15px;
            height: 215px;
            width: 250px;
        }
        .box h3 {
            color: #444;
            font-size: 15px;
            padding: 10px 0;
        }
        .box .price {
            font-size: 18px;
            color: #6C63FF;
            padding: 15px 0;
            font-weight: bold;
        }
        .availability-badge {
            color: red;
            font-style: italic;
        }
        .container .box-container .box img {
            height: 30px;
            width: 30px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="heading">{{ $info2->name }}</h2>
        <table>
            <tr>
                @foreach($articles as $key => $article)
                    <td>
                        <div class="box">
                            <h3>{{ $article->title }}</h3>
                            <img style="height: 70px; width: 70px;" src="{{ asset($article->image) }}" alt="{{ $article->title }}">
                            @if ($article->stock_actuel <= 0 || ($article->stock_actuel > 0 && $article->stock_actuel <= $article->alert_stock))
                                <div class="availability-badge">
                                    @if ($article->stock_actuel <= 0) 
                                        Complet 
                                    @else 
                                        {{$article->stock_actuel}} restant(s) 
                                    @endif
                                </div>
                            @endif
                            @php
                                $reducedPrice = getReducedPriceGuest($article->id_shop_article, $article->totalprice);
                                $priceToDisplay = $reducedPrice ? $reducedPrice : $article->totalprice;
                                $DescReduc = getFirstReductionDescriptionGuest($article->id_shop_article);
                            @endphp
                            <div class="price-section">
                                @if ($reducedPrice && $reducedPrice != $article->totalprice)
                                    <span style="text-decoration: line-through;">{{ number_format($article->totalprice, 2, ',', ' ') }} €</span>
                                    <span style="color: #007BFF; font-size: x-large; font-weight: bold;">
                                        {{ number_format($priceToDisplay, 2, ',', ' ') }} €
                                    </span>
                                @else
                                    <div class="price">{{ number_format($article->totalprice, 2) }} €</div>
                                @endif
                                @if ($DescReduc)
                                    <div class="discount-description">{{ $DescReduc }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    @if(($key + 1) % 3 == 0 && $key != 0) </tr><tr> @endif
                @endforeach
            </tr>
        </table>
    </div>
</body>
</html>

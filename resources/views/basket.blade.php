@extends('layouts.app')

@section('content')
<style>

.title{
    margin-bottom: 5vh;
}
.card{
    margin: auto;
    max-width: 1350px;
    width: 90%;
    box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    border-radius: 1rem;
    border: transparent;
}
@media(max-width:767px){
    .card{
        margin: 3vh auto;
    }
}
.cart{
    background-color: #fff;
    padding: 4vh 5vh;
    border-bottom-left-radius: 1rem;
    border-top-left-radius: 1rem;
}
@media(max-width:767px){
    .cart{
        padding: 4vh;
        border-bottom-left-radius: unset;
        border-top-right-radius: 1rem;
    }
}
.summary{
    background-color: #e9e9e9;
    border-top-right-radius: 1rem;
    border-bottom-right-radius: 1rem;
    padding: 4vh;
    color: rgb(65, 65, 65);
}
@media(max-width:767px){
    .summary{
    border-top-right-radius: unset;
    border-bottom-left-radius: 1rem;
    }
}
.summary .col-2{
    padding: 0;
}
.summary .col-10
{
    padding: 0;
}.row{
    margin: 0;
}
.title b{
    font-size: 1.5rem;
}
.main{
    margin: 0;
    padding: 2vh 0;
}
.col-2, .col{
    padding: 0 1vh;
}
a{
    padding: 0 1vh;
}
.close{
    margin-left: auto;
    font-size: 0.7rem;
}

.back-to-shop{
    margin-top: 4.5rem;
}
h5{
    margin-top: 4vh;
}
hr{
    margin-top: 1.25rem;
}
form{
    padding: 2vh 0;
}
select{
    border: 1px solid rgba(0, 0, 0, 0.137);
    padding: 1.5vh 1vh;
    margin-bottom: 4vh;
    outline: none;
    width: 100%;
    background-color: rgb(247, 247, 247);
}
input{
    border: 1px solid rgba(0, 0, 0, 0.137);
    padding: 1vh;
    margin-bottom: 4vh;
    outline: none;
    width: 100%;
    background-color: rgb(247, 247, 247);
}
input:focus::-webkit-input-placeholder
{
      color:transparent;
}

.btn:hover{
    color: white !important;
    background-color:  #c20012 !important;
}
a{
    color: black; 
}

 #code{
    background-image: linear-gradient(to left, rgba(255, 255, 255, 0.253) , rgba(255, 255, 255, 0.185)), url("https://img.icons8.com/small/16/000000/long-arrow-right.png");
    background-repeat: no-repeat;
    background-position-x: 95%;
    background-position-y: center;
}



.btn-danger {
    background-color: #c20012;
    border-color: #c20012;
    color: white;
}

.btn-danger:hover{
    color: white !important;
    background-color: #272e5c !important;  
}

.custom-alert {
        border-radius: 5px;
        padding: 15px;
        margin-top: 20px;
        font-weight: bold;
        text-align: center;
        color: #333;
        background-color: #f5f5f5;
        border: 1px solid #d1d1d1;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease-in-out;
    }

    .custom-alert:hover {
        box-shadow: 0 6px 10px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
.btn-success {
    background-color: #129e3c;
    border-color: #129e3c;
    color: white;
}
</style>
<main style="background-color: #ebf5ff !important; min-height: 90vh; " class="main" id="main ">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="card my-5">
    <div class="row">
        <div class="col-md-8 cart">
            <div class="title">
                <div class="row">
                    <div class="col"><h4><b>Panier d'achat</b></h4></div>
                </div>
            </div>    

            <div>
                @if ($paniers->isEmpty())
                    <div class="custom-alert">
                        Votre panier est vide !
                    </div>
                @else
                    @foreach ($paniers as $key => $panier)
                    @if ($key == 0 || ($panier->name != $paniers[$key-1]->name || $panier->lastname != $paniers[$key-1]->lastname))
                    <div class="row border-top border-bottom table-secondary">
                        <h6 class="col-12"><b>{{ $panier->lastname }} {{ $panier->name }}</b></h6>
                    </div>
                    @endif

                    <div class="row border-top border-bottom">
                        <div class="row main align-items-center">
                            <div class="col-2">
                                <img width="70px" class="img-fluid" src="{{ $panier->image }}">
                            </div>
                            <div class="col">
                                <div class="row text-dark">
                                    {{ $panier->title }}
                                    @if ($panier->declinaison_libelle != null)
                                        [{{ $panier->declinaison_libelle }}]
                                    @endif
                                    @if ($panier->reduction != null)
                                        <span class="text-danger">({{ $panier->reduction }})</span>
                                    @endif
                                </div>
                                <div class="row text-muted">{{ $panier->reff }}</div>
                            </div>
                            <div class="col">
                                <p class="text-muted small">{{ $panier->total_qte }} x {{ number_format($panier->prix, 2, ',', ' ') }}&nbsp;€</p>
                            </div>
                            <div class="col">
                                {{ number_format($panier->prix*$panier->total_qte, 2, ',', ' ') }}&nbsp;€ 
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

            <div class="back-to-shop"><a href="/sub_shop_categories/5">&leftarrow;<span class="text-muted">&nbsp Retour à la boutique</span></a></div>
        </div>
        <div class="col-md-4 summary">
            <div><h5><b>Résumé</b></h5></div>
            <hr>
            <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                <div class="col">PRIX TOTAL</div>
                <div class="col text-right">{{ number_format($total, 2, ',', ' ') }}&nbsp;€</div>
            </div>
            <a href="{{ route('paiement') }}" class="btn btn-success">VALIDER</a>
            <a href="{{ route('Vider_panier', auth()->user()->user_id) }}" class="btn btn-danger my-2 ">Vider le panier</a>
        </div>
    </div>
</div>
</main>


@endsection
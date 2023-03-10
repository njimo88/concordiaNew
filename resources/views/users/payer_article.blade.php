@extends('layouts.app')

@section('content')
<main id="main" class="main" style="padding : 88px 0; background-image: url('{{asset("/assets/images/background.png")}}');">
    <div style="background-color:white;"  class="container rounded" >
      @if (session('success'))
            <div class="alert alert-success col-12 m-3">
                {{ session('success') }}
            </div>
      @endif
        <div  class="row ">
            <div class="widget-title col-12 d-flex justify-content-between align-items-center">
                <span><h5 style="font-weight:bold"  class="text-dark font-weight-bold">Récapitulatif :&nbsp;</h5> Total : {{ number_format($total, 2, ',', ' ') }} € TTC</span>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="fas fa-angle-left mr-2"></i> Retour
                </a>
            </div>
            <div class="row d-flex justify-content-center">
                <div style="min-height: 300px; background-color:#edeeef;" class="col-md-6 mx-3 border border-dark">
                    <h4 class="p-3">Produits Achetés :</h4>
                    <div class="row">
                        @foreach ($paniers as $article)
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <div class="col-4 d-flex justify-content-center">
                                    <span style="font-weight:bold" class="text-dark">{{ $article->lastname }}{{ $article->name }}</span>
                                </div>
                                <div class="col-4 d-flex justify-content-center">
                                    <span>{{ $article->title }}</span>
                                </div>
                                <div class="col-4 d-flex justify-content-center">
                                    <span>{{ number_format($article->price, 2, ',', ' ') }} € TTC</span>
                                </div>
                            </div>
                        <hr>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-end">
                        <span class="text-success p-3" style="font-weight:bold;text-align: right;">Total : {{ number_format($total, 2, ',', ' ') }} € TTC</span>
                    </div>
                </div>
                <div style="background-color:#edeeef;" class="col-md-4 mx-3 border border-dark my-auto">
                    <h4 class="p-3">Adresse de facturation : </h4>
                    <span class="d-flex p-3 pt-0">{{ $adresse->address }} <br> {{ $adresse->zip }} {{ $adresse->city }} <br>{{ $adresse->country }}</span>
                </div>
            </div>
        </div>
        <hr>
        <div class="row d-flex justify-content-center ">
            <h5 style="font-weight:bold"  class="text-dark font-weight-bold p-3">Moyens de paiement :</h5>

            @foreach ($Mpaiement as $Mpaiement)
                <div  class="col-md-5  row mx-2 d-flex justify-content-center mb-5">
                    <div style="background-color:#edeeef;" class="col-12 d-flex justify-content-center m-2 p-1 border">
                        <img style="width : 30px" src="{{ $Mpaiement->image}}" alt=""><h5 class="mx-3">{{ $Mpaiement->payment_method}}</h5>
                    </div>
                    <div class="col-11 d-flex justify-content-center m-2">
                        <img style="max-width : 200px" src="{{ $Mpaiement->image}}" alt="">
                    </div>

                </div>
            @endforeach
        </div>
</main>
@endsection

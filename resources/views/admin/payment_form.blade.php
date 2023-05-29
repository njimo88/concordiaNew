@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://static.scelliuspaiement.labanquepostale.fr/static/js/krypton-client/V4.0/ext/neon-reset.css">

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-image: url("{{ asset('assets/images/background.png') }}");

        color: #333;
    }

    .kr-embedded {
        max-width: 500px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0px 2px 4px rgba(0,0,0,0.1);
        border-radius: 5px;
    }

    .payment-logos {
        display: flex;
        justify-content: space-around;
        margin-bottom: 20px;
    }

    .bank-logo {
        display: block;
        margin: 0 auto 20px;
    }
</style>

<script type="text/javascript"
    src="https://static.scelliuspaiement.labanquepostale.fr/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js" 
    kr-public-key="{{ env('API_PUBLIC_KEY') }}"
    kr-get-url-success="{{ route('detail_paiement', ['id' => 1, 'nombre_cheques' => 1]) }}";>
</script>

<script type="text/javascript" src="https://static.scelliuspaiement.labanquepostale.fr/static/js/krypton-client/V4.0/ext/neon.js"></script>
<main class="main mt-4" id="main">
<!-- Insert your logo -->
<img width="80%" class="bank-logo" src="{{ asset("assets/images/BP.png") }}" alt="Logo de La Banque Postale">

<div class="container p-4 border border-dark shadow-lg" style="        background-color: #fefefe;
" >

<div class="row">
    <div class="col-md-7">
        <h3>Les moyens de paiement possible :</h3>
        <img style="width : 80%" src="{{ asset("assets/images/Cc.png") }}" alt="Instructions">
    </div>
    <div class="col-md-5 row d-flex justify-content-center">
        <div class="col-md-12 d-flex justify-content-center">
             <img style="width: 100px" src="{{ asset("assets/images/pst.jpg") }}" alt="Instructions">
        </div>
        <div class="kr-embedded col-md-11" kr-form-token="{{ $formToken }}">
        </div>
    </div>
</div>

</div>

</div>
</main>
@endsection

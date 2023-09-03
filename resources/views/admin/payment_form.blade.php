@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://static.scelliuspaiement.labanquepostale.fr/static/js/krypton-client/V4.0/ext/neon-reset.css">

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #ebf5ff;
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
@php
    require_once(app_path().'/fonction.php');

@endphp



<main class="main vh-100 mt-4" id="main" sty>
<!-- Insert your logo -->
<img width="80%" class="bank-logo" src="{{ asset("assets/images/BP.png") }}" alt="Logo de La Banque Postale">

<div class="container p-4 border border-dark shadow-lg mb-5" style="        background-color: #fefefe;
" >

<div class="row">
    <div class="col-md-7">
        <h3>Les moyens de paiement possible :</h3>
        <img style="width : 80%" src="{{ asset("assets/images/Cc.png") }}" alt="Instructions">
    </div>
    <div class="col-md-5 row d-flex justify-content-center">
        <div class="col-md-12 d-flex justify-content-center">
             <img style="width: 200px;height:200px;" src="{{ asset("assets/images/pst.jpg") }}" alt="Instructions">
        </div>
        <div class="d-flex justify-content-center mb-5">
            <form method="POST" action="https://scelliuspaiement.labanquepostale.fr/vads-payment/">
                <input type="hidden" name="vads_action_mode" value="INTERACTIVE" />
                <input type="hidden" name="vads_amount" value="{{ $total*100 }}" />
                <input type="hidden" name="vads_currency" value="978" />
                <input type="hidden" name="vads_cust_id" value="{{ $user->user_id }}" />
                <input type="hidden" name="vads_cust_email" value="{{ $user->email }}" />
                <input type="hidden" name="vads_cust_first_name" value="{{ remove_accents($user->name) }}" />
                <input type="hidden" name="vads_cust_last_name" value="{{ remove_accents($user->lastname) }}" />
                <input type="hidden" name="vads_cust_phone" value="{{ $user->phone }}" />
                <input type="hidden" name="vads_cust_address" value="{{ remove_accents($user->address) }}" />
                <input type="hidden" name="vads_cust_zip" value="{{ remove_accents($user->zip) }}" />
                <input type="hidden" name="vads_cust_city" value="{{ remove_accents($user->city) }}" />
                <input type="hidden" name="vads_cust_country" value="{{ remove_accents($user->country) }}" />
                <input type="hidden" name="vads_ctx_mode" value="PRODUCTION" />
                <input type="hidden" name="vads_order_id" value="{{ $orderId  }}" />
                <input type="hidden" name="vads_page_action" value="PAYMENT" />
                <input type="hidden" name="vads_payment_cards" value="VISA;MASTERCARD" />
                <input type="hidden" name="vads_payment_config" value="{{ $payment_config }}" /> 
                <input type="hidden" name="vads_site_id" value="31118669" />
                <input type="hidden" name="vads_url_cancel" value="{{ route('basket', ['message' => 'Transaction annulée']) }}" />
                <input type="hidden" name="vads_url_error" value="{{ route('basket', ['message' => 'Erreur lors de la transaction']) }}" />
                <input type="hidden" name="vads_url_refused" value="{{ route('basket', ['message' => 'Transaction refusée']) }}" />
                <input type="hidden" name="vads_url_success" value="{{ route('detail_paiement', ['id' => 1, 'nombre_cheques' => $nombre_virment]) }}" />
                <input type="hidden" name="vads_trans_date" value="{{ $utcDate }}" />
                <input type="hidden" name="vads_trans_id" value="{{ $vads_trans_id }}" />
                <input type="hidden" name="vads_version" value="V2" />
                <input type="hidden" name="signature" value="{{ $signature }}"/>
                
                <!-- Ajout des champs pour le retour automatique -->
                <input type="hidden" name="vads_redirect_success_timeout" value="0" />
                <input type="hidden" name="vads_redirect_error_timeout" value="0" />
                <input type="hidden" name="vads_return_mode" value="GET" />
            
                <button type="submit" class="btn">
                    <i class="fas fa-credit-card"></i>
                    <span>Payer {{ number_format($total, 2, ',', ' ') }} €</span>
                </button>
            </form>
            
            
            
        </div>


<style>
.btn-pay {
    font-size: 1rem;
    padding: 10px 20px;
    color: #fff;
    background: #f44336; /* red */
    border: none;
    border-radius: .25rem;
    transition: background 0.5s;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
    line-height: 1.5;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
}

.btn-pay:hover {
    animation: pulse 1s ease-in-out infinite;
    background: #969cc1; /* indigo */
}

.btn-pay i {
    margin-right: .5rem;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>

        </div>
    </div>
</div>

</div>

</div>
</main>
@endsection

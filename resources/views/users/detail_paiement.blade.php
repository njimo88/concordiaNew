
@extends('layouts.app')

@section('content')
<?php
 function englishToFrench(){
setlocale(LC_ALL, 'fr_FR.UTF-8');
date_default_timezone_set('Europe/Paris');

// Define an array of English to French translations for month and day names
$englishToFrench = [
    'January' => 'janvier',
    'February' => 'février',
    'March' => 'mars',
    'April' => 'avril',
    'May' => 'mai',
    'June' => 'juin',
    'July' => 'juillet',
    'August' => 'août',
    'September' => 'septembre',
    'October' => 'octobre',
    'November' => 'novembre',
    'December' => 'décembre',
    'Monday' => 'Lundi',
    'Tuesday' => 'Mardi',
    'Wednesday' => 'Mercredi',
    'Thursday' => 'Jeudi',
    'Friday' => 'Vendredi',
    'Saturday' => 'Samedi',
    'Sunday' => 'Dimanche',
];

// Use the strtr function to replace the English month and day names with their French equivalents
$formattedDate = strtr(now()->isoFormat('dddd D MMMM YYYY à HH:mm:ss'), $englishToFrench);

// Output the formatted date
echo $formattedDate;
}
?>
<main id="main" class="main" style="min-height : 100vh; padding : 2px 0; background-image: url('{{asset("/assets/images/background.png")}}');">
    <div style="background-color:white;"  class="container rounded px-2 mt-5" >
        @if (session('success'))
            <div style="display: -webkit-inline-box !important;" class="alert alert-success mt-3 col-12">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div style="    display: -webkit-inline-box;" class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="row">
            <div class=" p-3" style=" background-color: white; padding:16px; border-radius:10px">
                <div class="row justify-content-between">
                    <div class="col-6"><span style="color: #008000">Merci pour votre commande !</span></div>
                    <div class="col-6 d-flex justify-content-end"><a href="{{ route("users.FactureUser") }}" class="btn btn-sm btn-danger mx-2">Retour aux Factures</a></div>
                </div><br><br>
                <b>Un email contenant toutes les informations ci-dessous vient de vous être envoyé.</b><br><br>
                Nous avons bien enregistré votre commande {{ $bill->ref }} du @php englishToFrench()@endphp  (NB : Celle-ci ne sera validée qu'à la réception de votre paiement).<br><br>
                Vous avez choisi de régler votre commande par {{ $payment }}, merci de nous transmettre votre règlement :<br>
                &nbsp;&nbsp;&nbsp;&nbsp;- D'un montant total de <b>{{ number_format($total, 2, ',', ' ') }} €</b> &nbsp;&nbsp;&nbsp;&nbsp;        <br>&nbsp;&nbsp;&nbsp;&nbsp;- A l'ordre de <b>"Gym Concordia"</b><br><br>Mode de paiement : <b>{{ $payment }}</b><br>En cas d'envoi, merci de le transmettre à cette adresse : <b>Trésorier Gym Concordia - 30, Rue de gambsheim - 67300 Schiltigheim</b><br><br>
                {!! $text->text !!} 
                <fieldset class="large-8 left">
                    <legend>Dates d'Encaissements</legend>
                    <?php
                    setlocale(LC_ALL, 'fr_FR.UTF-8');
                    date_default_timezone_set('Europe/Paris');

                    $englishToFrench = [
                        'January' => 'janvier',
                        'February' => 'février',
                        'March' => 'mars',
                        'April' => 'avril',
                        'May' => 'mai',
                        'June' => 'juin',
                        'July' => 'juillet',
                        'August' => 'août',
                        'September' => 'septembre',
                        'October' => 'octobre',
                        'November' => 'novembre',
                        'December' => 'décembre',
                    ];

                    $datetime = new DateTime(now()->format('Y-m-d'));
                    $formattedMonthYear = $datetime->format('F Y');
                    $i = 1;

                    foreach ($nb_paiment as $paiment) {
    $formattedMonthYear = $englishToFrench[$datetime->format('F')] . ' ' . $datetime->format('Y');
    echo '<b>Paiement ' . $i . '</b>: &nbsp;' . number_format($paiment, 2, ',', ' ') . ' €&nbsp;&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp; <b>Echéance : &nbsp;</b>' . $formattedMonthYear . '<br>';
              
    $datetime->add(new DateInterval('P1M')); // add one month to the date
    $i++;
}

                    ?>
                </fieldset>
                
                
                <hr>
                Pour consulter votre commande : <a href="{{ route('user.showBill',$bill->id) }}">cliquer sur ce lien</a><br>
            </div>
        </div>
    </div>
</main>
@endsection

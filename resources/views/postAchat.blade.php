@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
@php
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
@endphp
<style>
 body, html {
    background-color: #f7f9fc;
    font-family: 'Poppins', sans-serif; /* Un choix de police moderne */
    color: #333;
}

.container {
    max-width: 70%; 
    margin: 1rem auto; 
    background-color: #ffffff;
}


.content-wrapper {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    border: 4px solid #272e5c;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
}

.alert {
    border-radius: 7px;
    padding: 15px 20px;
    font-weight: 500;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.thank-you-section {
    padding: 1.5rem 2rem;
    border-radius: 7px;
    background-color: #ffffff; /* Fond blanc pour contraster */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.07);
}

.title {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #c20012; /* Couleur du titre en bleu foncé */
}

.sub-title {
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.green-text {
    color: #28a745;
}

.btn-danger {
    background-color: #d9534f;
    border: none;
    color: #fff;
    padding: 8px 15px;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
}

.btn-danger:hover {
    background-color: #c94442;
}

.legend {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

@media (max-width: 900px) {
    .container {
        max-width: 95%;
    }

    .thank-you-section {
        padding: 1rem;
    }

    .title, .sub-title {
        font-size: 1.2rem;
    }

    .btn-danger {
        padding: 4px 10px;
        font-size: 12px;

    }

    .row.justify-content-between {
        flex-direction: column-reverse;
        gap: 0.5rem;
    }
}
</style>

<div class="container">
    <div class="content-wrapper p-3">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="thank-you-section rounded">
            <div class="row justify-content-between">
                <div class="col-6 title"><span>Merci pour votre commande !</span></div>
                <div class="col-6 d-flex justify-content-end"style="align-self: end;"><a href="{{ route("users.FactureUser") }}" class="btn btn-sm btn-danger mx-2">Retour aux Factures</a></div>
            </div>
            <div class="sub-title">Référence de commande : {{ $bill->ref }}</div>
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
@endsection

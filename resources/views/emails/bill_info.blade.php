<!DOCTYPE html>
<html>
<head>
	<title>Information de facture</title>
	<style type="text/css">
		table {
			width: 100%;
			border-collapse: collapse;
		}
		th, td {
			border: 1px solid #ddd;
			padding: 8px;
			text-align: left;
		}
		th {
			background-color: #f2f2f2;
		}
	</style>
</head>
<body>
	<?php
    if (!function_exists('english')) {
    
    function english(){
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
    }
?>

<main id="main" class="main" style="min-height : 100vh; padding : 88px 0; background-image: url('{{asset("/assets/images/background.png")}}');">
    <img style="width : 100%" src="https://ci4.googleusercontent.com/proxy/QTU8dPuGusSaCF4PR5qwydl0c6D89H-RyHDAbemiAtL5cYDP1VU6yE2cfAl-VwPdiE0sv81t5xyJAO8YazfyfuG5UmU8WJz-AoeZmfBxQTDD_DgPnbB1VC56RmI=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Entete-Gym-Concordia.jpg"
        alt="">

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
                Bonjour <b>{{ auth()->user()->lastname }} {{ auth()->user()->name }}</b>&nbsp;&nbsp;&nbsp;&nbsp;(Commande n°{{ $bill->ref }} ),<br><br>
                Nous avons bien enregistré votre commande #{{ $bill->ref }} du @php english()@endphp  (NB : Celle-ci ne sera validée qu'à la réception de votre paiement).<br><br>
                Vous avez choisi de régler votre commande par {{ $payment }}, merci de nous transmettre votre règlement :<br>
                &nbsp;&nbsp;&nbsp;&nbsp;- D'un montant total de <b>{{ number_format($total, 2, ',', ' ') }} €</b> &nbsp;&nbsp;&nbsp;&nbsp;        <br>&nbsp;&nbsp;&nbsp;&nbsp;- A l'ordre de <b>"Gym Concordia"</b><br><br>Mode de paiement : <b>{{ $payment }}</b><br>En cas d'envoi, merci de le transmettre à cette adresse : <b>Trésorier Gym Concordia - 30, Rue de gambsheim - 67300 Schiltigheim</b><br><br>
                {!! $text->text !!}
                <fieldset class="large-8 left">
                    <legend>Dates d'Encaissements</legend>
                    <?php
                   

                    $englishhToFrenchhh = [
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
    $formattedMonthYear = $englishhToFrenchhh[$datetime->format('F')] . ' ' . $datetime->format('Y');
    echo '<b>Paiement ' . $i . '</b>: &nbsp;' . number_format($paiment, 2, ',', ' ') . ' €&nbsp;&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp; <b>Echéance : &nbsp;</b>' . $formattedMonthYear . '<br>';
              
    $datetime->add(new DateInterval('P1M')); // add one month to the date
    $i++;
}
                    ?>
                    
                </fieldset>
                
                
                <hr> <br>
                Contenu de votre commande :<br><br>
                <table>
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Prix total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paniers as $panier)
                        <tr>
                            <td>{{ $panier->title }}</td>
                            <td>{{ $panier->totalprice }}</td>
                            <td>{{ $panier->qte }}</td>
                            <td>{{ $panier->totalprice * $panier->qte }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" style="text-align: right">Total:</td>
                            <td>{{ $total }}</td>
                        </tr>
                    </tbody>
                </table>
<br>              
                Pour consulter votre commande : <a href="{{ route('user.showBill',$bill->id) }}">cliquer sur ce lien</a><br>
                En vous remerciant pour votre confiance,<br>
                Cordialement.
            </div>
        </div>
    </div>
    <img style="width : 100%"  src="https://ci3.googleusercontent.com/proxy/Rk6xItGCR1jq4vPdEVuZqviLQFbpGqWvg2pw8-Zsq2jTLgCyBy1a0SBwWTGasmelRtAL9xKTK3l9h1cAxM5lFIt2D09bP5b31Rf7b88GQ5443xDcC8Dbmz7VwJ8AI34=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Signature-Site-Internet.png"
        alt="">

</main>
</body>
</html>

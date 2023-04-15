<!DOCTYPE html>
<html>
<head>
	<title>Information de facture</title>
</head>
<body>
<main id="main" class="main" style="min-height : 100vh; padding : 88px 0; background-image: url('{{asset("/assets/images/background.png")}}');">
    <img style="width : 100%" src="https://ci4.googleusercontent.com/proxy/QTU8dPuGusSaCF4PR5qwydl0c6D89H-RyHDAbemiAtL5cYDP1VU6yE2cfAl-VwPdiE0sv81t5xyJAO8YazfyfuG5UmU8WJz-AoeZmfBxQTDD_DgPnbB1VC56RmI=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Entete-Gym-Concordia.jpg"
        alt="">

    <div style="background-color:white;"  class="container rounded px-2 mt-5" >
        <div class="row">
            <div class=" p-3" style=" background-color: white; padding:16px; border-radius:10px">
                Bonjour <b>{{ $bill->lastname }} {{ $bill->name }}</b>&nbsp;&nbsp;&nbsp;&nbsp;(Commande n°{{ $bill->ref }})<br><br>
                @if ($bill->status == 1)
                Votre commande n°{{ $bill->ref }} a été annulée.
                @elseif ($bill->status == 3)
                Votre commande n°{{ $bill->ref }} a été remboursée.
                @elseif ($bill->status == 6)
                Votre commande n°{{ $bill->ref }} est suspendue.
                @elseif ($bill->status == 10)
                Votre commande n°{{ $bill->ref }} est en 2ème rappel de paiement.
                @elseif ($bill->status == 20)
                Votre commande n°{{ $bill->ref }} est en 1er rappel de paiement.
                @elseif ($bill->status == 30)
                Votre commande n°{{ $bill->ref }} est en attente de paiement par chèque.
                @elseif ($bill->status == 32)
                Votre commande n°{{ $bill->ref }} est en attente de paiement en espèces.
                @elseif ($bill->status == 34)
                Votre commande n°{{ $bill->ref }} est en attente de paiement par bons.
                @elseif ($bill->status == 36)
                Votre commande n°{{ $bill->ref }} est en attente de paiement par virement.
                @elseif ($bill->status == 38)
                Votre commande n°{{ $bill->ref }} est en attente de paiement mixte.
                @elseif ($bill->status == 70)
                Un paiement partiel pour votre commande n°{{ $bill->ref }} a été accepté.
                @elseif ($bill->status == 90)
                Une caution pour votre commande n°{{ $bill->ref }} a été acceptée.
                @elseif ($bill->status == 100)
                Le paiement pour votre commande n°{{ $bill->ref }} a été accepté.
                @endif
                
                
<br><br>
Veuillez trouver ci-joint les documents y étant relatifs.
Pour toute question, n'hésitez pas à prendre contact avec l'association.
<br><br><hr><br>   
Vous pouvez accéder au suivi de vos commandes sous la rubrique "{{ $bill->name }} > Mes Factures/Devis" sur notre site <br>           
                Pour consulter votre commande : <a href="{{ route('facture.showBill',$bill->id) }}">cliquer sur ce lien</a><br>
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

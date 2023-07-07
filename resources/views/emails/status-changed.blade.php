<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
        <img  src="https://ci4.googleusercontent.com/proxy/QTU8dPuGusSaCF4PR5qwydl0c6D89H-RyHDAbemiAtL5cYDP1VU6yE2cfAl-VwPdiE0sv81t5xyJAO8YazfyfuG5UmU8WJz-AoeZmfBxQTDD_DgPnbB1VC56RmI=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Entete-Gym-Concordia.jpg"
            alt=""><hr>
            <p>Bonjour,</p>

<p>La facture #{{ $bill->ref }} est passée de statut {{ $oldStatus }} vers statut {{ $newStatus }}.</p>
{!! $$bill->mail_content !!}
<hr>

<p><strong>Date de la facture:</strong> {{ $bill->date_bill }}</p>
<p><strong>Méthode de paiement:</strong> {{ $bill->bill_payment_method }}</p>
<p><strong>Montant total du paiement:</strong> {{ number_format($bill->payment_total_amount, 2, ',', ' ') }} €</p>

<p>Cordialement,</p>
<p>La Gym Concordia</p>

    <img   src="https://ci3.googleusercontent.com/proxy/Rk6xItGCR1jq4vPdEVuZqviLQFbpGqWvg2pw8-Zsq2jTLgCyBy1a0SBwWTGasmelRtAL9xKTK3l9h1cAxM5lFIt2D09bP5b31Rf7b88GQ5443xDcC8Dbmz7VwJ8AI34=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Signature-Site-Internet.png"
        alt="">
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Concordia</title>
</head>
<body>
    <img  src="https://ci4.googleusercontent.com/proxy/QTU8dPuGusSaCF4PR5qwydl0c6D89H-RyHDAbemiAtL5cYDP1VU6yE2cfAl-VwPdiE0sv81t5xyJAO8YazfyfuG5UmU8WJz-AoeZmfBxQTDD_DgPnbB1VC56RmI=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Entete-Gym-Concordia.jpg"

        alt=""><hr>

        <h2>Bonjour {{ $user->name }}, (Commande n°{{ $bill->ref }})</h2>
    <p>Le paiement pour votre commande n°{{ $bill->ref }} a été accepté.</p>
    <p>Veuillez trouver ci-joint les documents y étant relatifs.</p>
    <p>Pour toute question, n'hésitez pas à prendre contact avec l'association.</p><hr>
    <p>Pour consulter votre commande : <a href="{{ route('user.showBill',$bill->id) }}">cliquer sur ce lien</a></p><br>
    <p>En vous remerciant pour votre confiance.</p>
    <p>Cordialement,</p>

    <img style="width : 100%"  src="https://ci3.googleusercontent.com/proxy/Rk6xItGCR1jq4vPdEVuZqviLQFbpGqWvg2pw8-Zsq2jTLgCyBy1a0SBwWTGasmelRtAL9xKTK3l9h1cAxM5lFIt2D09bP5b31Rf7b88GQ5443xDcC8Dbmz7VwJ8AI34=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Signature-Site-Internet.png"
    alt="">
</body>
</html>

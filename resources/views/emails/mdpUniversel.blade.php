<!DOCTYPE html>
<html>
<head>
	<title>Réinitialisation de mot de passe</title>
</head>
<body>
<main id="main" class="main" style="min-height: 100vh; padding: 88px 0; background-image: url('{{ asset("/assets/images/background.png") }}');">
    <img style="width : 100%" src="https://ci4.googleusercontent.com/proxy/QTU8dPuGusSaCF4PR5qwydl0c6D89H-RyHDAbemiAtL5cYDP1VU6yE2cfAl-VwPdiE0sv81t5xyJAO8YazfyfuG5UmU8WJz-AoeZmfBxQTDD_DgPnbB1VC56RmI=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Entete-Gym-Concordia.jpg"
        alt=""><hr>
    Bonjour <b>{{ $users->lastname }} {{ $users->name }}</b>,<br><br>

    Vous avez demandé une réinitialisation de votre mot de passe.<br><br>

    Voici le rappel de vos identifiants :<br><br>
    &nbsp;&nbsp;&nbsp;&nbsp;Utilisateur : <b>{{ $users->username }}</b><br><br>
    &nbsp;&nbsp;&nbsp;&nbsp;Mot de Passe : <b>concordia</b> <br><br>

    Merci de changer votre mot de passe lors de votre prochaine connexion.<br><br>

    Pour toute question, n'hésitez pas à prendre contact avec l'association. <br><br><hr>

    Vous pouvez accéder au suivi de vos commandes sous la rubrique "{{ $users->name }} > Mes Factures/Devis" sur notre site.
    <br><br>

    En vous remerciant de votre confiance.<br>
    Cordialement,<br><img style="width : 100%"  src="https://ci3.googleusercontent.com/proxy/Rk6xItGCR1jq4vPdEVuZqviLQFbpGqWvg2pw8-Zsq2jTLgCyBy1a0SBwWTGasmelRtAL9xKTK3l9h1cAxM5lFIt2D09bP5b31Rf7b88GQ5443xDcC8Dbmz7VwJ8AI34=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Signature-Site-Internet.png"
    alt="">
</main>
</body>
</html>

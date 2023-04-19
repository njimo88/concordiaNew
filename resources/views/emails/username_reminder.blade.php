<!DOCTYPE html>
<html>
<head>
	<title>Information de facture</title>
</head>
<body>
<main id="main" class="main" style="min-height : 100vh; padding : 88px 0; background-image: url('{{asset("/assets/images/background.png")}}');">
    <img  src="https://ci4.googleusercontent.com/proxy/QTU8dPuGusSaCF4PR5qwydl0c6D89H-RyHDAbemiAtL5cYDP1VU6yE2cfAl-VwPdiE0sv81t5xyJAO8YazfyfuG5UmU8WJz-AoeZmfBxQTDD_DgPnbB1VC56RmI=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Entete-Gym-Concordia.jpg"
        alt=""><br><br>

        Madame, Monsieur, <br><br>

        Vous nous avez indiqué avoir oublié vos identifiants. <br>
        Voici l'ensemble des personnes présentes dans notre base de données qui sont associées au mail {{ $email }} : <br><br>
        
        @foreach ($users as $user)
        {{ $user->lastname }} {{ $user->name }} ({{ $user->family_level }})  a comme nom d'utilisateur : <b>{{ $user->username }}</b><br><br>
        @endforeach
        Si vous éprouvez des difficultés de connexion, n'hésitez pas à contacter le bureau de l'association par téléphone ou email.
        <br>
        Cordialement.
        <br>


    <img   src="https://ci3.googleusercontent.com/proxy/Rk6xItGCR1jq4vPdEVuZqviLQFbpGqWvg2pw8-Zsq2jTLgCyBy1a0SBwWTGasmelRtAL9xKTK3l9h1cAxM5lFIt2D09bP5b31Rf7b88GQ5443xDcC8Dbmz7VwJ8AI34=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Signature-Site-Internet.png"
        alt="">

</main>
</body>
</html>

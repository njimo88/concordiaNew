<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mail Recap</title>
</head>
<body>
    <img style="width : 100%" src="https://ci4.googleusercontent.com/proxy/QTU8dPuGusSaCF4PR5qwydl0c6D89H-RyHDAbemiAtL5cYDP1VU6yE2cfAl-VwPdiE0sv81t5xyJAO8YazfyfuG5UmU8WJz-AoeZmfBxQTDD_DgPnbB1VC56RmI=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Entete-Gym-Concordia.jpg"

        alt=""><hr>
    <p>
        Mail envoyé par {{ $user->lastname }} {{ $user->name }} le {{ $mail_history->date }} au groupe {{ $group}}
    </p>
    <p>
        Titre = {{ $mail_history->title }}
    </p>
    <p>
        Message : {!! $mail_history->message !!}
    </p>
        
    <p>
        Destinataires:
    </p>
    <p>
    @foreach ($destinataires as $destinataire)
        {{ $destinataire->lastname }} {{ $destinataire->name }} : ({{ $destinataire->username }}, {{ $destinataire->email }}) <br>
    @endforeach
    </p>
</body>
</html>

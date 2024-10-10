
    <p>
        Mail envoyé par {{ $user->lastname }} {{ $user->name }} le {{ $mail_history->date }}
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
   
  

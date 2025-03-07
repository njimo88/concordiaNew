<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
        <img  src="https://ci4.googleusercontent.com/proxy/QTU8dPuGusSaCF4PR5qwydl0c6D89H-RyHDAbemiAtL5cYDP1VU6yE2cfAl-VwPdiE0sv81t5xyJAO8YazfyfuG5UmU8WJz-AoeZmfBxQTDD_DgPnbB1VC56RmI=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Entete-Gym-Concordia.jpg"
            alt=""><hr>
    <p>Bonjour {{ $name }},</p> 
    
    {!! $content !!}
    
    <p>Cordialement,</p>
    <p>{{ $senderName }}</p>
    @if(is_null( $attachmentLinks ))
     <p><strong>Pièces jointes:</strong> Aucune</p>
    @else
    <div style="margin-top: 20px;">
        <strong>Pièces jointes:</strong><br>
        @foreach($attachmentLinks as $attachment)
        <a href="{{ asset($attachment) }}" download="{{ basename($attachment) }}">
            <i class="fas fa-paperclip"></i>{{ basename($attachment) }}
        </a><br>
        @endforeach
    </div>
    @endif

    @if (file_exists(public_path('uploads/signatures/' . $username . '.png')))
    <img src="{{ asset('uploads/signatures/' . $username . '.png') }}" alt="">
@else
    <img src="https://ci3.googleusercontent.com/proxy/Rk6xItGCR1jq4vPdEVuZqviLQFbpGqWvg2pw8-Zsq2jTLgCyBy1a0SBwWTGasmelRtAL9xKTK3l9h1cAxM5lFIt2D09bP5b31Rf7b88GQ5443xDcC8Dbmz7VwJ8AI34=s0-d-e1-ft#https://www.gym-concordia.com/uploads/signatures/Signature-Site-Internet.png" alt="">
@endif

</body>
</html>

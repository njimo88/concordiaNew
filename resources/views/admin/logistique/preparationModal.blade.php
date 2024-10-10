<ul>
    @foreach ($liaisons as $liaison)
        <li>{{ $liaison->shopArticle->title }} - Destinataire : {{ $liaison->addressee }}</li>
    @endforeach
</ul>

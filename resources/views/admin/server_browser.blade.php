@extends('layouts.template')

@section('content')
<main id="main" class="main">
    <h1 style="text-align: center;">Explorateur de fichiers</h1>

    <div style="margin-top: 20px; height: 85vh; border: 1px solid #ddd; width: 100%;">
        <!-- iframe qui charge elFinder -->
        <iframe src="{{ url("/elfinder/ckeditor") }}" width="100%" height="100%" frameborder="0"></iframe>
    </div>
</main>
@endsection

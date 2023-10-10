@extends('layouts.template')
@section('content')
<style>
    .main {
    justify-content: center;
    align-items: center;
    height: 80vh;
    background-color: #f4f4f8;
}

form {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 300px;
}

form label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}

form select {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    background-color: #482683;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #004aad;
    color: #ffffff;
}

</style>
<main class="main" id="main">
    <h1>Statistiques - Export</h1>
    <form action="{{ route('export.csv') }}" method="POST">
        @csrf
        <label>Choisir la saison :</label>
        <select name="saison" id="saison">
            @foreach($saison_list as $saison)
                <option value="{{ $saison->saison }}">{{ $saison->saison }}</option>
            @endforeach
        </select>
        <button type="submit">Exporter en CSV</button>
    </form>


    <h1 class="mt-5">Statistiques - Export Produit</h1>
    <form action="{{ route('export.csvProduit') }}" method="POST">
        @csrf
        <label>Choisir la saison :</label>
        <select name="saison" id="saison">
            @foreach($saison_list as $saison)
                <option value="{{ $saison->saison }}">{{ $saison->saison }}</option>
            @endforeach
        </select>
        <button type="submit">Exporter en CSV</button>
    </form>
    
</main>
@endsection
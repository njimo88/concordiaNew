@extends('layouts.template')
@section('content')
<style>
    .main {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        background-color: #f4f4f8;
        padding: 20px;
    }

    .form-container {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        width: 400px;
        text-align: center;
    }

    .form-container h1 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #482683;
    }

    form label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        text-align: left;
    }

    form select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .checkbox-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 50px;
    }

    .checkbox-group h3 {
        margin-bottom: 10px;
        text-align: center;
    }

    
    input:focus {
        outline: none;
        box-shadow: none;
        border-color: none;
    }

    #toggleAll {
        margin-bottom: 20px;
        background-color: #482683;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #toggleAll:hover {
        background-color: #004aad;
    }

    .checkbox-container {
        display: flex;
        flex-wrap: wrap; /* Permet le wrapping des éléments */
        gap: 10px; /* Espacement entre les éléments */
        justify-content: center; /* Centre les éléments horizontalement */
        max-width: 800px;
        width: 100%; /* S'adapte à la largeur disponible */
    }

    .checkbox-container label {
        display: flex;
        align-items: center;
        font-weight: normal;
        padding: 5px;
        background: #f9f9f9;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        max-width: 150px; /* Largeur maximale */
        flex: 1 1 auto; /* Ajuste la largeur de manière flexible */
        box-sizing: border-box;
    }

    .checkbox-container input {
        margin-right: 10px;
    }

    button[type="submit"] {
        margin-top: 20px;
        background-color: #482683;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #004aad;
    }


    .checkbox-group label {
        display: flex;
        text-align: center;
        align-items: center;
        margin-bottom: 10px;
        padding-right: 15px;
        cursor: pointer;
        gap: 15px;
    }

    .checkbox-group input[type="checkbox"] {
        margin-right: 0px;
    }

    .button-group {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .button-group button,
    form button {
        background-color: #482683;
        color: #ffffff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    .button-group button:hover,
    form button:hover {
        background-color: #004aad;
    }

    form button {
        width: 100%;
        margin-top: 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #toggleAll {
            width: 100%; /* Le bouton s'étend sur toute la largeur */
        }

        button[type="submit"] {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        h3 {
            font-size: 1.2rem;
        }

        button {
            font-size: 0.9rem;
            padding: 8px 15px;
        }
    }
</style>

<main class="main" id="main">
    <h1 class="mt-5">Statistiques - Export Produit</h1>
    <form action="{{ route('export.csvProduit') }}" method="POST" id="exportForm">
        @csrf
        <label>Choisir la saison :</label>
        <select name="saison" id="saison">
            @foreach($saison_list as $saison)
                <option value="{{ $saison->saison }}">{{ $saison->saison }}</option>
            @endforeach
        </select>

        <div class="checkbox-group">
            <h3>Colonnes à exporter :</h3>
            <button type="button" id="toggleAll">Tout cocher/décocher</button>
            <div class="checkbox-container">
                @foreach($columns as $column => $label)
                    <label>
                        <input type="checkbox" name="columns[]" value='@json(["sql" => $column, "label" => $label])' checked> {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>    

        <button type="submit">Exporter</button>
    </form>
</main>

<script>
    document.querySelector('#exportForm').addEventListener('submit', function (event) {
        const checkboxes = document.querySelectorAll('.checkbox-group input[type="checkbox"]:checked');
        
        if (checkboxes.length === 0) {
            event.preventDefault(); // Empêche la soumission du formulaire
            alert('Veuillez sélectionner au moins une colonne à exporter.');
        }
    });

    document.querySelector('#toggleAll').addEventListener('click', function () {
        const checkboxes = document.querySelectorAll('.checkbox-group input[type="checkbox"]');
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
    });
</script>
@endsection
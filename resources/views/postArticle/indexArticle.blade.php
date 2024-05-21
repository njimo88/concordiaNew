@extends('layouts.template')

@section('content')
<style>
    /* Base container styling */
    .main {
        font-family: 'Montserrat', sans-serif;
        color: #2f184b; /* Dark purple text */
    }

    /* Improved Season Selector with modern design */
    .season-selector {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .season-selector label {
        margin-right: 10px;
    }

    .season-selector select {
        width: auto;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-family: inherit;
        color: #2f184b;
        background-color: #f4effa; /* Light purple background */
    }

    /* Modern Table Design */
    .articles-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        background-color: #f4effa; /* Light purple background */
    }

    .articles-table th, .articles-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .articles-table th {
        background-color: #303f9f; /* Dark purple background */
        color: white;
    }

    .articles-table td {
        color: #2f184b; /* Dark purple text */
    }

    /* Adjusting column width for price-related columns */
    .articles-table th.prix, .articles-table td.prix {
        width: 10%; /* Or any other percentage that fits your content */
    }

    .articles-table td .articleImage {
        width: 100px; /* Adjust based on your needs */
        height: auto;
        border: 1px solid #ddd; /* A subtle border for images */
    }

    /* Button styling */
    button {
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-family: inherit;
        transition: background-color 0.3s ease;
    }

    button.modifier {
        background-color: #303f9f; /* Dark purple background */
        color: white;
    }

    button.supprimer {
        background-color: #2f184b; /* Darker purple background */
        color: white;
    }

    button.modifier:hover {
        background-color: darken(#303f9f, 10%);
    }

    button.supprimer:hover {
        background-color: darken(#2f184b, 10%);
    }
      .button-group {
        display: flex;
        flex-direction: column;
        gap: 5px; /* Spacing between buttons */
    }

    /* Styling for buttons */
    .button {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-family: inherit;
        color: #fff;
        transition: background-color 0.3s ease;
    }

    .button.edit {
        background-color: #3b5998; /* Facebook blue */
    }

    .button.edit:hover {
        background-color: #2d4373;
    }

    .button.delete {
        background-color: #e53935; /* Red */
    }

    .button.delete:hover {
        background-color: #d32f2f;
    }

    .button.duplicate {
        background-color: #00b894; /* Teal */
    }

    .button.duplicate:hover {
        background-color: #00a37b;
    }
</style>

<main class="main" id="main">
    <h1>Gestion des Articles</h1>

    <div class="season-selector">
        <label for="season">Sélectionner la saison :</label>
        <select id="season" class="form-control">
            @foreach($saisons as $saison)
                <option value="{{ $saison->saison }}" @if($saison->saison == $saison_active) selected @endif>
                    {{ $saison->saison }}-{{ intval($saison->saison) + 1 }}
                </option>
            @endforeach
        </select>
    </div>
    <div id="articles-container">
        <!-- Articles initiaux rendus ici -->
        <table class="articles-table" class="">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Référence</th>
                    <th>Titre</th>
                    <th>Statut</th>
                    <th class="prix">Prix TTC</th>
                    <th class="prix">Prix Cumulé</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($articles as $article)
                <tr>
                    <td><img class="articleImage" src="{{ $article->image }}" alt="Image de l'article"></td>
                    <td>{{ $article->ref }}</td>
                    <td>
                        {{ $article->title }}
                        @if($article->sex_limite === null)
                            {{ $article->sex_limite }}
                            <img src="{{ asset('assets/images/genders.png') }}" alt="icône null" />
                        @elseif($article->sex_limite === 'female')
                            {{ $article->sex_limite }}

                            <img src="{{ asset('assets/images/femenine.png') }}" alt="icône female" />
                        @elseif($article->sex_limite === 'male')
                            {{ $article->sex_limite }}

                            <img src="{{ asset('assets/images/male.png') }}" alt="icône male" />
                        @endif
                    </td>
                    <td>
                        @php
                            $now = \Carbon\Carbon::now();
                            $startValidity = \Carbon\Carbon::parse($article->startvalidity);
                            $endValidity = \Carbon\Carbon::parse($article->endvalidity);
                        @endphp

                        @if ($now->between($startValidity, $endValidity))
                            <i class="fa fa-circle text-success"></i>
                        @else
                            <i class="fa fa-circle text-danger"></i>
                        @endif
                    </td>
                    <td class="prix">{{ number_format($article->price, 2, ',', ' ') }} €</td>
                    <td class="prix">{{ number_format($article->totalprice, 2, ',', ' ') }} €</td>
                    <td>{{$article->stock_actuel}}/{{ $article->stock_ini }}</td>
                    <td>
                        <div class="button-group">
                            <a target="_blank" href="{{ route('edit_article', ['id' => $article->id_shop_article]) }}">
                                <button class="button edit" article-title="Edit" article-toggle="modal" article-target="#edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </a>
                            <a href="{{ route('delete_article', ['id' => $article->id_shop_article]) }}">
                                <button class="button delete" article-title="Delete" article-toggle="modal" article-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </a>
                            <a href="{{ route('duplicate_article_index', ['id' => $article->id_shop_article]) }}">
                                <button class="button duplicate" article-title="Edit" article-toggle="modal">
                                    <i class="fa fa-clone"></i>
                                </button>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const seasonSelector = document.getElementById('season');

        seasonSelector.addEventListener('change', () => {
            const selectedSeason = seasonSelector.value;

            // Envoyer une requête Ajax pour récupérer les articles pour la saison sélectionnée.
            fetch(`/articles/fetch?saison=${selectedSeason}`)
                .then(response => response.json())
                .then(articles => updateArticlesTable(articles));
        });
    });

    function updateArticlesTable(articles) {
        const articlesContainer = document.getElementById('articles-container');

        let html = `
            <table class="articles-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Référence</th>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th class="prix">Prix TTC</th>
                        <th class="prix">Prix Cumulé</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        articles.forEach(article => {
            const now = new Date();
            const startValidity = new Date(article.startvalidity);
            const endValidity = new Date(article.endvalidity);

            const isValid = now >= startValidity && now <= endValidity;

            html += `
                <tr>
                    <td><img class="articleImage" src="${article.image}" alt="Image de l'article"></td>
                    <td>${article.ref}</td>
                    <td>
                        ${article.title}
                        ${article.sex_limite === null ? '<img src="assets/images/genders.png" alt="icône null">' : ''}
                        ${article.sex_limite === 'female' ? '<img src="assets/images/femenine.png" alt="icône female">' : ''}
                        ${article.sex_limite === 'male' ? '<img src="assets/images/male.png" alt="icône male">' : ''}
                    </td>
                    <td>${isValid ? '<i class="fa fa-circle text-success"></i>' : '<i class="fa fa-circle text-danger"></i>'}</td>
                    <td class="prix">${article.price.toFixed(2).replace('.', ',')} €</td>
                    <td class="prix">${article.totalprice.toFixed(2).replace('.', ',')} €</td>
                    <td>${article.stock_actuel}/${article.stock_ini}</td>
                    <td>
                        <div class="button-group">
                            <a target="_blank" href="{{ route('edit_article', ['id' => $article->id_shop_article]) }}">
                                <button class="button edit" article-title="Edit" article-toggle="modal" article-target="#edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </a>
                            <a href="{{ route('delete_article', ['id' => $article->id_shop_article]) }}">
                                <button class="button delete" article-title="Delete" article-toggle="modal" article-target="#delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </a>
                            <a href="{{ route('duplicate_article_index', ['id' => $article->id_shop_article]) }}">
                                <button class="button duplicate" article-title="Edit" article-toggle="modal">
                                    <i class="fa fa-clone"></i>
                                </button>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
        `;

        articlesContainer.innerHTML = html;
    }
</script>

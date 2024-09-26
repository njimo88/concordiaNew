@extends('layouts.template')

@section('content')
<style>
    .main {
        font-family: 'Montserrat', sans-serif;
        color: #2f184b; 
    }

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
        background-color: #f4effa;
    }

    .articles-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        background-color: #f4effa; 
    }

    .articles-table th, .articles-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .articles-table th {
        background-color: #303f9f; 
        color: white;
    }

    .articles-table td {
        color: #2f184b;
    }

    .articles-table th.prix, .articles-table td.prix {
        width: 10%; 
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

      .btn-oldArticlesBtn {
      background-color: #303f9f;
      border-color: #303f9f;
      color: white;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  .btn-oldArticlesBtn:hover {
    background-color: #303f9f;
      border-color: #303f9f;
      color: white;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
  }

  .season-selector {
      background-color: #f4f6f9;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
  }
</style>

<main class="main" id="main">
    <h1>Gestion des Articles</h1>

    <div class="container">
        <div class="season-selector row align-items-center">
            <div class="col-md-2">
                <label for="season" class="col-md-12">Sélectionner la saison :</label>
                <select id="season" class="form-control">
                    @foreach($saisons as $saison)
                    <option value="{{ $saison->saison }}" @if($saison->saison == $saison_active) selected @endif>
                        {{ $saison->saison }}-{{ intval($saison->saison) + 1 }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 text-right">
                <button id="oldArticlesBtn" class="btn btn-oldArticlesBtn">Ancien articles</button>
            </div>
        </div>
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
                <tr style="background-color: @if($article->stock_actuel <= 0) rgba(251, 51, 91, 0.8) @elseif($article->stock_actuel <= $article->alert_stock) rgba(255, 165, 0, 0.8) @else rgba(255, 255, 255, 0.8) @endif;">
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
    const oldArticlesBtn = document.getElementById('oldArticlesBtn');
    let showOldArticles = false;

    seasonSelector.addEventListener('change', () => {
        const selectedSeason = seasonSelector.value;
        console.log('Season changed:', selectedSeason, 'Show old articles:', showOldArticles);
        fetchArticles(selectedSeason, showOldArticles);
    });

oldArticlesBtn.addEventListener('click', () => {
    showOldArticles = !showOldArticles; // Basculer entre true et false
    const selectedSeason = seasonSelector.value;

    // Mettre à jour le texte du bouton en fonction de l'état
    oldArticlesBtn.textContent = showOldArticles ? 'Voir articles actuels' : 'Ancien articles';

    console.log('Toggled articles view:', showOldArticles ? 'Showing old articles' : 'Showing current articles');
    
    // Fetch articles en fonction de l'état
    fetchArticles(selectedSeason, showOldArticles);
});


function fetchArticles(selectedSeason, showOldArticles) {
    console.log(`Fetching articles for season: ${selectedSeason} | Show old articles: ${showOldArticles}`);

    fetch(`/articles/fetch?saison=${selectedSeason}&oldArticles=${showOldArticles}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error fetching articles: ${response.statusText}`);
            }
            return response.json();
        })
        .then(articles => {
            console.log('Articles fetched:', articles);
            updateArticlesTable(articles);
        })
        .catch(error => {
            console.error('Error fetching articles:', error);
        });
}


    function updateArticlesTable(articles) {
        const articlesContainer = document.getElementById('articles-container');

        articlesContainer.innerHTML = ''; // Clear existing content

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

        if (articles.length === 0) {
            html += `<tr><td colspan="8">Aucun article trouvé</td></tr>`;
        } else {
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
                                <a target="_blank" href="/edit_article/${article.id_shop_article}">
                                    <button class="button edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                </a>
                                <a href="/delete_article/${article.id_shop_article}">
                                    <button class="button delete" onclick="return confirm('êtes-vous sûr de vouloir supprimer?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </a>
                                <a href="/duplicate_article_index/${article.id_shop_article}">
                                    <button class="button duplicate">
                                        <i class="fa fa-clone"></i>
                                    </button>
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        html += `
                </tbody>
            </table>
        `;

        articlesContainer.innerHTML = html;
    }
});
</script>
@endsection

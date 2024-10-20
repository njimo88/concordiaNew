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
  .CreatArticle:hover {
    background-color: #303f9f;
    color: #303f9f;}

/* Down arrow for the title price ...... */
    th.sortable {
            cursor: pointer;
            position: relative;
        }
        th.sortable:after {
            content: '';
            padding-left: 5px;
        }
        th.sortable.asc:after {
            content: '▲'; /* Up arrow */
            color: white;
        }
        th.sortable.desc:after {
            content: '▼'; /* Down arrow */
            color: white;
        }
/* end Down arrow for the title price ...... */


</style>
@php
require_once(app_path().'/fonction.php');

@endphp

<main class="main" id="main">
    <h1>Gestion des Articles</h1>
   
    <div class="container">
        <div class="season-selector row align-items-center">
            <form action="{{route('Article2_fetchArticles')}}" method="POST" id="autoSubmitFormSaison">
                @csrf
                
                <div class="col-md-6 row">

                    <div class="col">
                        <label for="season" class="col-md-12 text-black"> saison :</label>
                        <select id="season" class="form-control" name="saison" onchange="submitForm()" >
                            @foreach($saisons as $saison)
                            <option value="{{ $saison->saison }}" @if($saison->saison == $saison_active) selected @endif>
                                {{ $saison->saison }}-{{ intval($saison->saison) + 1 }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    

                    <div class="col form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="mySwitch" name="expire" value="something" 
                        @if($oldArticles == true) checked @endif onchange="submitForm()">
                        <label class="form-check-label text-black" for="mySwitch">Anciens Articles</label>
                    </div>
                   

                    <div class=" col btn-group mt-2">
                        <button type="button" class="btn btn-oldArticlesBtn dropdown-toggle " data-bs-toggle="dropdown">crée article </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item CreatArticle" href="{{ route('index_create_article_member') }}">de type membre </a>
                          <a class="dropdown-item CreatArticle" href="{{ route('index_create_article_produit') }}">de type produit</a>
                          <a class="dropdown-item CreatArticle" href="{{ route('index_create_article_lesson') }}">de type cours</a>
                        </div>
                    </div>
                    <div class="col">
                        @if (auth()->user()->roles->supprimer_edit_dupliquer_ajout_article)
                        <button id="updateStock" class="btn btn-oldArticlesBtn">
                            <i class="fas fa-sync"></i> mise a jour de stock
                        </button>
                          @endif
                    </div>
                </div>
                
            </form>

            
            <div class="col-md-3 text-right">
                <!-- <button id="oldArticlesBtn"  class="btn btn-oldArticlesBtn">Ancien articles</button> -->
            </div>
        </div>
    </div> 

    <div id="articles-container">
        <!-- Articles initiaux rendus ici -->
        <table class="articles-table" class="" id="articleTable">
            <thead>
                <tr>
                    <th onclick="sortTable(0); toggleSort('Image')" class="sortable" data-sort="asc">Image</th>
                    <th onclick="sortTable(1); toggleSort('Référence')" class="sortable" data-sort="asc">Référence</th>
                    <th onclick="sortTable(2); toggleSort('Titre')" class="sortable" data-sort="asc">Titre</th>
                    <th >Statut</th>
                    <th onclick="sortTableNumbers(4); toggleSort('Prix TTC')" class="sortable prix" data-sort="asc" >Prix TTC</th>
                    <th onclick="sortTableNumbers(5); toggleSort('Prix Cumulé')" class="sortable prix" data-sort="asc" >Prix Cumulé</th>
                    <th onclick="sortTableNumbers(6); toggleSort('Stock')" class="sortable " data-sort="asc" >Stock</th>
                    <th >Actions</th>
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
                        @if (auth()->user()->roles->supprimer_edit_dupliquer_ajout_article)
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
                        @endif
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>
<script>
    //auto refrech on change select and saison 
    function submitForm() {
        document.getElementById('autoSubmitFormSaison').submit();
    }

</script>


<script> 
    //sort the article table 
    function sortTable(n) {
        
      var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
      table = document.getElementById("articleTable");
      switching = true;
      //Set the sorting direction to ascending:
      dir = "asc"; 
     
      while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        
        for (i = 1; i < (rows.length - 1); i++) {
          //start by saying there should be no switching:
          shouldSwitch = false;
          
          x = rows[i].getElementsByTagName("TD")[n];
          y = rows[i + 1].getElementsByTagName("TD")[n];
          
          if (dir == "asc") {
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
              //if so, mark as a switch and break the loop:
              shouldSwitch= true;
              break;
            }
          } else if (dir == "desc") {
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
              //if so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          }
        }
        if (shouldSwitch) {
          
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          //Each time a switch is done, increase this count by 1:
          switchcount ++;      
        } else {
          
          if (switchcount == 0 && dir == "asc") {
            dir = "desc";
            switching = true;
          }
        }
      }
    }

        //sort numbers 
    function sortTableNumbers(n) {
      var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
      table = document.getElementById("articleTable");
      switching = true;
      //Set the sorting direction to ascending:
      dir = "asc"; 
   
      while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
          //start by saying there should be no switching:
          shouldSwitch = false;
        
          x = rows[i].getElementsByTagName("TD")[n];
          y = rows[i + 1].getElementsByTagName("TD")[n];
     
          let value = x.innerHTML;
          let value2 = y.innerHTML;  // The string you want to process
          let numericValue = value.replace(/[^0-9,.-]/g, '');
          let numericValue2 = value2.replace(/[^0-9,.-]/g, '');
          numericValue = numericValue.replace(',', '.');
          numericValue2 = numericValue2.replace(',', '.');
          let number = parseFloat(numericValue);
          let number2 = parseFloat(numericValue2);

          if (dir == "asc") {
            if (Number(number) > Number(number2)) {
              //if so, mark as a switch and break the loop:
              shouldSwitch= true;
              break;
            }
          } else if (dir == "desc") {
            if (Number(number) < Number(number2)) {
              //if so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          }
        }
        if (shouldSwitch) {
          
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          //Each time a switch is done, increase this count by 1:
          switchcount ++;      
        } else {
       
          if (switchcount == 0 && dir == "asc") {
            dir = "desc";
            switching = true;
          }
        }
      }
    }

    // change the arrow that apear on clicking the title or price ..... 
    function toggleSort(column) {
        const headers = document.querySelectorAll('th.sortable');
        headers.forEach(header => header.classList.remove('asc', 'desc')); // Remove sort classes

        const clickedHeader = document.querySelector(`th[onclick*="${column}"]`);
        const currentSort = clickedHeader.getAttribute('data-sort');

        if (currentSort === 'asc') {
            clickedHeader.classList.add('desc'); // Add descending class
            clickedHeader.setAttribute('data-sort', 'desc'); // Update data-sort attribute
        } else {
            clickedHeader.classList.add('asc'); // Add ascending class
            clickedHeader.setAttribute('data-sort', 'asc'); // Update data-sort attribute
        }
    }
    </script>

    
    
@endsection

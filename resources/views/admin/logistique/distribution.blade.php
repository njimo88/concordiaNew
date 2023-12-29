@extends('layouts.template')

@section('content')
<style>
     .btn-primary{
        background-color: #482683;
        border-color: #482683;
    }
    .btn-danger{
        background-color: #b33a76;
        border-color: #b33a76;
    }
    .user-card {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    padding: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.user-action .btn:hover {
    background-color: #290d58; /* Assombrit le bouton lors du survol */
    color: #fff; /* Change la couleur du texte pour une meilleure visibilité */
}

.user-avatar img:hover {
    transform: scale(1.1); /* Agrandit l'avatar lors du survol */
}

.user-info h5, .user-info p {
    transition: color 0.3s ease; /* Transition pour le changement de couleur */
}

.user-card:hover .user-info h5,
.user-card:hover .user-info p {
    color: #007bff; /* Change la couleur du texte lors du survol de la carte */
}

.user-card:hover {
    transform: translateY(-5px); /* Déplace légèrement la carte vers le haut pour un effet de survol */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Assombrit l'ombre pour une meilleure mise en évidence */
}

.user-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    transition: transform 0.3s ease;
}

.user-info {
    flex-grow: 1;
    padding-left: 15px;
}

.user-name {
    margin: 0;
    font-weight: bold;
}

.user-status {
    margin: 0;
    font-size: .8em;
}

.status-dot {
    height: 10px;
    width: 10px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block;
    margin-right: 5px;
}

.status-dot.active {
    background-color: green;
}

.status-dot.inactive {
    background-color: red;
}

.user-location,
.user-contact {
    margin: 0;
    font-size: .9em;
}

.user-action .btn {
    float: right;
    transition: background-color 0.3s ease;
}

/* Responsive layout for small devices */
@media (max-width: 767.98px) {
    .user-card {
        flex-direction: column;
        text-align: center;
    }

    .user-info {
        padding-left: 0;
        padding-top: 10px;
    }

    .user-action .btn {
        float: none;
        margin-top: 10px;
    }
}
 .tabs-container {
    display: flex;
    justify-content: start;
    margin-bottom: 10px; /* Spacing below the tabs */
}

.tab {
    padding: 10px 20px; /* Vertical and horizontal padding */
    border: 1px solid #ccc;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s; /* Smooth transition for background and text color */
    margin-right: 5px; /* Spacing between tabs */
    border-radius: 5px; /* Rounded corners */
    background-color: transparent; /* Default background */
    color: #000000; /* Default text color */
}

.tab:hover {
    background-color: #482683; /* Background color on hover */
    color: #fff; /* Text color on hover */
}

.tab.active {
    background-color: #482683; /* Active tab background */
    color: white; /* Active tab text color */
    border-color: #482683; /* Active tab border color */
}

.content {
    display: none;
    padding: 20px;
    border: 1px solid #ccc;
    border-top: none;
    background-color: #fff; /* Background for content */
    color: #333; /* Text color for content */
}

.content.active {
    display: block;
}

</style>

<main class="main" id="main">
    <div class="tabs-container">
        <div class="tab active" onclick="changeTab('nonDistribue')">Non Distribués</div>
        <div class="tab" onclick="changeTab('distribue')">Distribués</div>
    </div>

    <div id="nonDistribue" class="content active">
        <h3>Produits Non Distribués</h3>
        @if($articlesForDistribution->isEmpty())
            <div class="alert alert-info">Aucun produit à distribuer pour le moment.</div>
        @else
        <div class="container">
            <div class="row">
                @foreach ($articlesForDistribution as $article)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card user-card">
                            <div class="card-body">
                                <div class="user-avatar m-2">
                                    <img src="{{ $article->image }}" alt="{{ $article->title }}" class="rounded-circle">
                                </div>
                                <div class="user-info">
                                    <h5 class="user-name">{{ $article->title }} {{ optional($article->declinaison_info)->libelle }}</h5>
                                    <p class="user-quantity">Quantité : {{ $article->liaisonShopArticlesBill->sum('quantity') }}</p>
                                    <p class="user-location">Destinataire : {{ $article->liaisonShopArticlesBill->first()->addressee }}</p>
                                    <p class="user-status">Préparé par : {{ $article->prepared_by_name }} le {{ $article->prepared_at }}</p>
                                </div>
                                <div class="user-action m-3">
                                    <button type="button" class="btn btn-primary btn-distribute" data-toggle="modal" data-target="#distributionModal" data-article-title="{{ $article->title }}" data-article-quantity="{{ $article->liaisonShopArticlesBill->first()->quantity }}" data-liaison-id="{{ $article->liaisonShopArticlesBill->first()->id_liaison }}">Distribuer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div id="distribue" class="content">
        <h3>Produits Distribués</h3>
        @if($articlesDistributed->isEmpty())
            <div class="alert alert-info">Aucun produit distribué pour le moment.</div>
        @else
        <div class="container">
            <div class="row">
                @foreach ($articlesDistributed as $liaison)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card user-card">
                            <div class="card-body">
                                <div class="user-avatar m-2">
                                    <img src="{{ $liaison->shopArticle->image }}" alt="{{ $liaison->shopArticle->title }}" class="rounded-circle">
                                </div>
                                <div class="user-info">
                                    <h5 class="user-name">{{ $liaison->shopArticle->title }} @if (isset($liaison->declinaisonName)) [{{ $liaison->declinaisonName }}] @endif</h5>
                                    <p class="user-location">Destinataire : {{ $liaison->addressee }}</p>
                                    <p class="user-status">Préparé par : {{ optional($liaison->preparationConfirmation->user)->lastname . ' ' . optional($liaison->preparationConfirmation->user)->name }} le {{ optional($liaison->preparationConfirmation)->confirmed_at->format('d/m/Y à H:i') }}</p>
                                    <p class="user-status">Distribué par : {{ optional($liaison->distributionDetail->user)->lastname . ' ' . optional($liaison->distributionDetail->user)->name }} le {{ optional($liaison->distributionDetail)->distributed_at->format('d/m/Y à H:i') }}</p>
                                </div>
                                <div class="user-action m-3">
                                    <button type="button" class="btn btn-warning btn-return" data-liaison-id="{{ $liaison->id_liaison }}" data-toggle="modal" data-target="#returnModal">Retourner le Produit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</main>


<div class="modal fade" id="distributionModal" tabindex="-1" role="dialog" aria-labelledby="distributionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="distributionModalLabel">Confirmation de distribution</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Voulez-vous confirmer la distribution de l'article suivant ?</p>
                <p><strong>Article :</strong> <span id="modalArticleTitle"></span></p>
                <p><strong>Quantité :</strong> <span id="modalArticleQuantity"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmDistribution">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnModalLabel">Retourner le Produit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="returnForm">
                    <div class="form-group">
                        <label for="returnReason">Raison du Retour</label>
                        <textarea class="form-control" id="returnReason" name="reason" rows="3" required></textarea>
                    </div>
                    <input type="hidden" id="liaisonId" name="liaisonId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="submitReturn">Confirmer le Retour</button>
            </div>
        </div>
    </div>
</div>


@endsection
@extends('layouts.template')

@section('content')

<main class="main" id="main">
    <div class="container mt-3">
        @if($products->isEmpty())
            <div class="alert alert-info">Aucun produit à préparer pour le moment.</div>
        @else
            <div class="row">
                @foreach ($products as $product)
                    @foreach ($product->liaisonShopArticlesBill as $liaison)
                        @if ($liaison->bill  && !$liaison->is_prepared)
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card user-card">
                                    <div class="card-body">
                                        <div class="user-avatar m-2">
                                            <img src="{{ $product->image }}" alt="Product" class="rounded-circle">
                                        </div>
                                        <div class="user-info">
                                            <h5 class="user-name">{{ $product->title }} {{ optional($product->declinaisons->first())->libelle }}</h5>
                                            <p class="user-quantity">Quantité : {{ $liaison->quantity }}</p>
                                            <p class="user-location">Destinataire : {{ $liaison->addressee }}</p>
                                            <p class="user-status"><span class="status-dot active"></span> En préparation</p>
                                        </div>
                                        <div class="user-action m-3">
                                            <button type="button" class="btn btn-danger mx-2 nonConcerne" data-liaison-id="{{ $liaison->id_liaison }}" >Non concerné</button>
                                            <button type="button" class="btn btn-primary btn-prepared" data-toggle="modal" data-target="#confirmationModal" data-article-title="{{ $product->title }} {{ optional($product->declinaisons->first())->libelle }}" data-article-quantity="{{ $liaison->quantity }}" data-article-designation="{{ optional($product->declinaisons->first())->libelle }}" data-liaison-id="{{ $liaison->id_liaison }}">Préparé</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        @endif
    </div>
</main>

<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmationModalLabel">Confirmation de préparation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Voulez-vous confirmer la préparation de l'article suivant ?</p>
          <p><strong>Article :</strong> <span id="modalArticleTitle"></span></p>
          <p><strong>Quantité :</strong> <span id="modalArticleQuantity"></span></p>
        </div>
        <div class="modal-footer" >
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-primary" id="confirmPreparation">Confirmer</button>
        </div>
      </div>
    </div>
  </div>





  
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

</style>

@endsection

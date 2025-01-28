@extends('layouts.template')

@section('content')



<main class="main" id="main">

    <!-- si le client a la permission de voir preparation logistique -->
    @if (auth()->user()->roles->estAutoriserDeVoirPreparationLogistique)
   
    <div class="container mt-3">
        @if($products->isEmpty())
            <div class="alert alert-info">Aucun produit à préparer pour le moment.</div>
        @else
            <div class="row">
                @foreach ($products as $product)
                    @foreach ($product->liaisonShopArticlesBill as $liaison)
                        @if ($liaison->bill  && !$liaison->is_prepared && $liaison->bill->status == 100)
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card user-card">
                                    @if($liaison->productReturn) 
                                        <div class="card-header bg-warning">Retourné <a href="#" class="info" data-toggle="modal" data-target="#returnInfoModal" data-reason="{{ $liaison->productReturn->reason }}" data-returned-by="{{ optional($liaison->productReturn->user)->lastname . ' ' . optional($liaison->productReturn->user)->name }}" data-returned-at="{{ $liaison->productReturn->returned_at->format('d/m/Y H:i:s') }}">Info</a>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <div class="user-avatar m-2">
                                            <img src="{{ $product->image }}" alt="Product" class="rounded-circle">
                                        </div>
                                        <div class="user-info">
                                            <h5 class="user-name">{{ $product->title }} {{ optional($liaison->declinaison_link)->libelle ? '[' . optional($liaison->declinaison_link)->libelle . ']' : '' }}</h5>
                                            <p class="user-quantity">Quantité : {{ $liaison->quantity }}</p>
                                            <p class="user-location">Destinataire : {{ $liaison->addressee }} <button type="button" class="btn btn-view-liaisons view-liaisons mx-2" data-user-id="{{ $liaison->id_user }}" data-toggle="modal" data-target="#liaisonsModal">
                                                <i class="fa-solid fa-info"></i>
                                            </button>
                                            </p>
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
         <!-- this button doesn't work  <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button> -->
          <button type="button" class="btn btn-primary" id="confirmPreparation">Confirmer</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="liaisonsModal" tabindex="-1" role="dialog" aria-labelledby="liaisonsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="liaisonsModalLabel">Détails des liaisons</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Les détails des liaisons seront chargés ici -->
        </div>
      </div>
    </div>
  </div>


<!-- Return Info Modal -->
<div class="modal fade" id="returnInfoModal" tabindex="-1" role="dialog" aria-labelledby="returnInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnInfoModalLabel">Informations sur le retour</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Raison du retour :</strong> <span id="returnReason"></span></p>
                <p><strong>Retourné par :</strong> <span id="returnedBy"></span></p>
                <p><strong>Date du retour :</strong> <span id="returnedAt"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- si le user n'est pas autorisé redirection to another page  -->
@else

@endif

  
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
    position: relative;
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
.card-header.bg-warning {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #ffc107; /* Adjust color as needed */
    color:  #290d58;
    padding: 5px 15px; /* Smaller padding */
    font-size: 0.8em; /* Smaller font size */
    font-weight: bold;
    border-radius: 5px; /* Rounded corners for a modern look */
    box-shadow: 0px 2px 5px rgba(0,0,0,0.2);
    transform: rotate(0deg); /* Adjust or remove the rotation as needed */
    z-index: 2; /* Ensure it's above other content */
}

/* Remove the pseudo-element as it's no longer needed for a top corner tag */
.card-header.bg-warning:before {
    display: none;
}

a.info {
  vertical-align: bottom;
  position:relative; /* Anything but static */
  width: 1.5em;
  height: 1.5em;
  text-indent: -9999em;
  display: inline-block;
  color: white;
  font-weight:bold;
  font-size:1em;
  line-height:1em;
  background-color: #91b2d2;
  margin-left: .25em;
  -webkit-border-radius:.75em;
  -moz-border-radius:.75em;
  border-radius:.75em;
}
a.info:hover {
  background-color:#628cb6;
  cursor: hand; 
  cursor: pointer;
}
a.info:before {
  content:"?";
  position: absolute;
  top: .25em;
  left:0;
  text-indent: 0;
  display:block;
  width:1.5em;
  text-align:center;
}

.popover-title {
  font-weight:bold;
}


label a.info, 
label div.popover.fade.in { 
  opacity: 0;
  -webkit-transition: opacity 0.2s ease;
  -moz-transition: opacity 0.2s ease;
  transition: opacity 0.2s ease;
}
label:hover a.info, 
label:hover div.popover.fade.in { 
  opacity: 1; }
</style>

@endsection

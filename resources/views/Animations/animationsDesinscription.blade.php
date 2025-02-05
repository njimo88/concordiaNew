@extends('layouts.app')

@section('content')
<main id="main" class="main mt-5 mb-5">
        <div class="mt-3 text-center">
                <h2>Désinscription à l'animation - {{ $registration->animation->title }}</h2> 
        </div>

        @if(!$registration)
                <div class="mt-3 text-center">
                        <div class="alert alert-info text-center w-50 mx-auto" role="alert">
                                Cette inscription n'existe plus.
                        </div>
                </div>
        @else
        <div class="mt-3 text-center">
                <p>Vous êtes inscrit(e) à : <strong>{{ $registration->animation->title }}</strong></p>
                <p><strong>Date : </strong> {{ \Carbon\Carbon::parse($registration->animation->animation_starttime)->format('d/m/Y à H\hi') }}</p>
                <p><strong>Durée : </strong> {{ \Carbon\Carbon::parse($registration->animation->duration)->format('H\hi') }}</p>
                <p><strong>Prix : </strong> 
                        @if($registration->animation->price > 0)
                                {{ $registration->animation->price }}€ (à payer en espèces)
                        @else
                                Gratuit
                        @endif
                </p>

                <!-- Bouton qui ouvre le modal -->
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUnsubscribeModal">
                        Se désinscrire
                </button>
        </div>

        <!-- Modal de confirmation -->
        <div class="modal fade" id="confirmUnsubscribeModal" tabindex="-1" aria-labelledby="confirmUnsubscribeLabel" aria-hidden="true">
                <div class="modal-dialog">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <h5 class="modal-title" id="confirmUnsubscribeLabel">Confirmation de désinscription</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                        Êtes-vous sûr(e) de vouloir vous désinscrire de l'animation <strong>{{ $registration->animation->title }}</strong> ?
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <form action="{{ route('visiteurs.animation.desinscription.delete', ['id' => $registration->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Confirmer la désinscription</button>
                                        </form>
                                </div>
                        </div>
                </div>
        </div>
        @endif
</main>
@endsection

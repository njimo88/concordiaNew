<div class="modal fade" id="showAnimationModal{{ $animation->id }}" data-bs-focus="false" aria-labelledby="showAnimationModalLabel{{ $animation->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showAnimationModalLabel{{ $animation->id }}">Détails - {{ $animation->title }}</h5>
                <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Titre -->
                <div class="mb-3">
                    <h1 class="form-control-plaintext text-center">{{ $animation->title }}</h1>
                </div>

                <!-- Image -->
                @if($animation->image_path)
                    <div class="mb-3 text-center">
                        <img src="{{ $animation->image_path }}" alt="Image de l'animation" class="img-fluid rounded">
                    </div>
                @endif

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label"><strong>Description :</strong></label>
                    <div class="clearfix border border-2 p-3">{!! $animation->description !!}</div>
                </div>

                <!-- Date et heure -->
                <div class="mb-3">
                    <label class="form-label"><strong>Date :</strong></label>
                    <p class="form-control-plaintext">
                        Du <strong>{{ \Carbon\Carbon::parse($animation->animation_starttime)->format('d/m/Y') }}</strong> 
                        à <strong>{{ \Carbon\Carbon::parse($animation->animation_starttime)->format('H:i') }}</strong> 
                        pendant <strong>{{ \Carbon\Carbon::parse($animation->duration)->format('H\hi') }}</strong> 
                    </p>
                </div>


                <!-- Nombre maximum de participants -->
                <div class="mb-3">
                    <label class="form-label"><strong>Nombre de participants :</strong></label>
                    <p class="form-control-plaintext"><strong>{{ $animation->participants->count() }}/{{ $animation->max_participants }}<strong></p>
                </div>

                <!-- Catégorie -->
                <div class="mb-3">
                    <label class="form-label"><strong>Catégorie :</strong></label>
                    <p class="form-control-plaintext">
                        <span class="badge" style="background-color: {{ $animation->category->color }}; color: {{ $animation->category->text_color }}">
                            {{ $animation->category->name }}
                        </span>
                    </p>
                </div>

                <!-- Prix -->
                <div class="mb-3">
                    <label class="form-label"><strong>Prix de l'inscription :</strong></label>
                    <p class="form-control-plaintext"><strong>{{ $animation->price ? $animation->price . '€ (A payer en espèces sur place)' : 'Gratuit' }}</strong></p>
                </div>

                <!-- Professeur -->
                <div class="mb-3">
                    <label class="form-label"><strong>Professeur(e) :</strong></label>
                    <p class="form-control-plaintext"><strong>{{ $animation->teacher->name }} {{ $animation->teacher->lastname }}</strong></p>
                </div>

                <!-- Salle -->
                <div class="mb-3">
                    <label class="form-label"><strong>Lieu :</strong></label>
                    <a href="{{ $animation->room->map }}" target="_blank" class="form-control-plaintext text-decoration-underline text-primary"><strong>{{ $animation->room->name }}</strong></a>
                </div>

                <!-- Bouton de fermeture -->
                <div class="d-flex gap-3 justify-content-end">
                    <button type="button" class="btn btn-primary open-inscription-modal"
                    data-bs-toggle="modal" 
                    data-bs-target="#inscriptionAnimationModal"
                    data-animation-id="{{ $animation->id }}"
                    data-animation-title="{{ $animation->title }}">
                    S'inscrire
                    </button>
                    <button type="button" class="btn-secondary close-modal" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>

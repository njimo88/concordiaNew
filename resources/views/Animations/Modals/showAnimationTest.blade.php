<!-- Modal unique -->
<div class="modal fade" id="showAnimationModal" data-bs-focus="false" aria-labelledby="showAnimationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showAnimationModalLabel">Détails de l'animation</h5>
                <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Titre -->
                <div class="mb-3">
                    <h1 class="form-control-plaintext text-center" id="modal-title"></h1>
                </div>

                <!-- Image -->
                <div class="mb-3 text-center" id="modal-image-container"></div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label"><strong>Description :</strong></label>
                    <div class="clearfix border border-2 p-3" id="modal-description"></div>
                </div>

                <!-- Date et heure -->
                <div class="mb-3">
                    <label class="form-label"><strong>Date :</strong></label>
                    <p class="form-control-plaintext" id="modal-date"></p>
                </div>

                <!-- Nombre maximum de participants -->
                <div class="mb-3">
                    <label class="form-label"><strong>Nombre de participants :</strong></label>
                    <p class="form-control-plaintext" id="modal-participants"></p>
                </div>

                <!-- Catégorie -->
                <div class="mb-3">
                    <label class="form-label"><strong>Catégorie :</strong></label>
                    <p class="form-control-plaintext" id="modal-category"></p>
                </div>

                <!-- Prix -->
                <div class="mb-3">
                    <label class="form-label"><strong>Prix de l'inscription :</strong></label>
                    <p class="form-control-plaintext" id="modal-price"></p>
                </div>

                <!-- Professeur -->
                <div class="mb-3">
                    <label class="form-label"><strong>Professeur(e) :</strong></label>
                    <p class="form-control-plaintext" id="modal-teacher"></p>
                </div>

                <!-- Salle -->
                <div class="mb-3">
                    <label class="form-label"><strong>Lieu :</strong></label>
                    <a href="#" id="modal-room" class="form-control-plaintext text-decoration-underline text-primary" target="_blank"></a>
                </div>

                <!-- Bouton de fermeture -->
                <div class="d-flex gap-3 justify-content-end">
                    <button type="button" class="btn btn-primary open-inscription-modal" data-bs-toggle="modal" data-bs-target="#inscriptionAnimationModal" id="modal-inscription-button">
                        S'inscrire
                    </button>
                    <button type="button" class="btn-secondary close-modal" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal unique d'inscription -->
<div class="modal fade" id="inscriptionAnimationModal" data-bs-focus="false" aria-labelledby="inscriptionAnimationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inscriptionAnimationModalLabel">Inscription</h5>
                <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('visiteurs.animation.inscription') }}" id="inscriptionForm" method="POST">
                    @csrf
                    <input type="hidden" name="animation_id" id="animation_id">
                    
                    <div id="participants-container">
                        <div class="participant border p-3 mb-3 rounded" data-index="1">
                            <h6>Participant 1</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="nom-1">Nom</label>
                                    <input type="text" name="nom[]" id="nom-1" class="form-control" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="prenom-1">Prénom</label>
                                    <input type="text" name="prenom[]" id="prenom-1" class="form-control" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="email-1">Email (si enfant, celui du parent)</label>
                                    <input type="email" name="email[]" id="email-1" class="form-control" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="telephone-1">Numéro de téléphone (si enfant, celui du parent)</label>
                                    <input type="text" name="telephone[]" id="telephone-1" class="form-control" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="age-1">Âge</label>
                                    <input type="number" name="age[]" id="age-1" class="form-control age-input" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 parent-phone">
                                    <label class="form-label" for="contact_urgence-1">Contact urgence (téléphone)</label>
                                    <input type="text" name="contact_urgence[]" id="contact_urgence-1" class="form-control" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <button type="button" class="btn btn-success" id="addParticipant">Ajouter une autre personne</button>
                        <button type="button" class="btn btn-danger d-none" id="removeLastParticipant">Supprimer le dernier participant</button>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                        <button type="button" class="btn btn-secondary close-modal" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let participantIndex = 1;
    
    // Quand on ouvre le modal d'inscription
    document.querySelectorAll(".open-inscription-modal").forEach(button => {
        button.addEventListener("click", function () {
            let animationId = this.getAttribute("data-animation-id");
            let animationTitle = this.getAttribute("data-animation-title");

            // Mettre à jour les informations du modal
            document.getElementById("inscriptionAnimationModalLabel").textContent = "Inscription - " + animationTitle;
            document.getElementById("animation_id").value = animationId;
        });
    });

    // Ajout dynamique de participants
    document.getElementById("addParticipant").addEventListener("click", function () {
        participantIndex++;

        let newParticipant = document.createElement("div");
        newParticipant.classList.add("participant", "border", "p-3", "mb-3", "rounded");
        newParticipant.setAttribute("data-index", participantIndex);
        newParticipant.innerHTML = `
            <h6>Participant ${participantIndex}</h6>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label" for="nom-${participantIndex}">Nom</label>
                    <input type="text" name="nom[]" id="nom-${participantIndex}" class="form-control" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6"><label class="form-label" for="prenom-${participantIndex}">Prénom</label>
                    <input type="text" name="prenom[]" id="prenom-${participantIndex}" class="form-control" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6"><label class="form-label" for="email-${participantIndex}">Email (si enfant, celui du parent)</label>
                    <input type="email" name="email[]" id="email-${participantIndex}" class="form-control" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6"><label class="form-label" for="telephone-${participantIndex}">Numéro de téléphone (si enfant, celui du parent)</label>
                    <input type="text" name="telephone[]" id="telephone-${participantIndex}" class="form-control" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="age-${participantIndex}">Âge</label>
                    <input type="number" name="age[]" id="age-${participantIndex}" class="form-control age-input" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6 parent-phone">
                    <label class="form-label" for="contact_urgence-${participantIndex}">Contact urgence (téléphone)</label>
                    <input type="text" name="contact_urgence[]" id="contact_urgence-${participantIndex}" class="form-control" required>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        `;

        document.getElementById("participants-container").appendChild(newParticipant);

        checkParticipants();
    });

    // Supprimer le dernier participant
    document.getElementById("removeLastParticipant").addEventListener("click", function () {
        let participants = document.querySelectorAll(".participant");
        if (participants.length > 1) {
            participants[participants.length - 1].remove();
            participantIndex--;
        }
        checkParticipants();
    });

    function checkParticipants() {
        let participants = document.querySelectorAll(".participant");
        document.getElementById("removeLastParticipant").classList.toggle("d-none", participants.length <= 1);
    }

    checkParticipants();
});
</script>
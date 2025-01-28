<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID Certificat</th>
                    <th>Image du certificat</th>
                    <th>Date d'emission</th>
                    <th>Nom d'utilisateur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificatesToValidate as $certificate)
                    <tr>
                        <td class="text-center align-middle">{{ $certificate->id }}</td>
                        <td class="text-center align-middle">
                            <!-- L'image qui ouvre le modal -->
                            <img src="{{ asset($certificate->file_path) }}" style="cursor: pointer;" width="100" alt="Certificat" data-bs-toggle="modal" data-bs-target="#imageModal{{ $certificate->id }}">
                        </td>
                        <td class="text-center align-middle">
                            <input 
                                type="date" 
                                wire:model="emissionDates.{{ $certificate->id }}" 
                                class="form-control"
                            >
                        </td>
                        <td class="text-center align-middle">{{ $certificate->user->name }} {{ $certificate->user->lastname }} ({{ $certificate->user->user_id  }})</td>
                        <td class="text-center align-middle">
                            <div class="buttons-container">
                                {{-- <button data-bs-toggle="modal" data-bs-target="#updateExpiration{{ $certificate->id }}" class="btn btn-warning">
                                    Enregistrer et valider
                                </button> --}}
                                <button data-bs-toggle="modal" data-bs-target="#validateCertificate{{ $certificate->id }}" class="btn btn-success">
                                    Valider
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal pour l'image -->
                    <div class="modal fade" id="imageModal{{ $certificate->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $certificate->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="imageModalLabel{{ $certificate->id }}">Image du Certificat</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Affichage de l'image à 100% de la largeur et hauteur -->
                                    <img src="{{ asset($certificate->file_path) }}" alt="Certificat" class="rounded mx-auto d-block" style="width: 100%; height: 100%;">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de confirmation pour la mise à jour de l'expiration -->
                    {{-- <div class="modal fade" id="updateExpiration{{ $certificate->id }}" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationModalLabel">Confirmer la mise à jour</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Êtes-vous sûr de vouloir mettre à jour la date d'expiration et de passer en "valide" ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="button" wire:click="updateExpiration({{ $certificate->id }})" class="btn btn-primary" data-bs-dismiss="modal">Confirmer</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Modal de confirmation pour la validation du certificat -->
                    <div class="modal fade" id="validateCertificate{{ $certificate->id }}" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="validationModalLabel">Confirmer la validation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Êtes-vous sûr de vouloir valider ce certificat ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="button" wire:click="validateCertificate({{ $certificate->id }})" class="btn btn-primary" data-bs-dismiss="modal">Confirmer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucun certificat en attente de validation</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.table th {
    text-align: center !important;
}

main .table tr td {
    text-align: center !important;
    height: 100%;
}

.buttons-container {
    display: flex;
    flex-direction: column;
    gap: 7.5px;
    justify-content: space-between;
    align-items: center;
    height: 100%;
}

.buttons-container button {
    flex-grow: 1;
    align-self: center
}
</style>
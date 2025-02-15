@extends('layouts.template')

@section('content')
    <main id="main" class="main d-flex flex-column justify-content-center align-items-center gap-3"
        style="min-height: 100vh; background-color: #f8f9fa;">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('certifications_niveaux_backend', ['id' => $cours->id_shop_article]) }}" id="form-submit"
            method="POST" style="width: 100%; max-width: 700px;">
            @csrf

            <h2 class="text-center mb-4" id="title">Sélection des Attributs</h2>

            <!-- Sélecteurs Disciplines & Niveaux -->
            <div class="mb-3">
                <label for="disciplines" class="form-label fw-bold">Choix des disciplines</label>
                <select name="disciplines[]" id="disciplines" class="selectpicker form-control border" multiple
                    title='Disciplines'>
                    @foreach ($disciplines as $discipline)
                        <option value="{{ $discipline->id }}">{{ $discipline->name }}</option>
                    @endforeach
                </select>
                <div id="discipline-error" class="alert alert-danger text-center mt-2 w-100" style="display: none">Veuillez
                    sélectionner une discipline au minimum.</div>
            </div>

            <div class="mb-3">
                <label for="niveau" class="form-label fw-bold">Choix du niveau</label>
                <select name="niveau" id="niveau" class="selectpicker form-control border" title='Niveau'>
                    @foreach ($niveaux as $niveau)
                        <option value="{{ $niveau->id }}">{{ $niveau->name }}</option>
                    @endforeach
                </select>
                <div id="niveau-error" class="alert alert-danger text-center mt-2 w-100" style="display: none;">Veuillez
                    sélectionner un niveau.</div>
            </div>

            <!-- Sélection des Utilisateurs -->
            <h3 class="text-center mb-3">Sélection des Utilisateurs</h3>
            <button type="button" class="btn btn-outline-secondary mb-2 w-100" id="select-all">Tout
                sélectionner/déselectionner</button>

            <div class="d-flex flex-wrap gap-2">
                <div id="users-error" class="alert alert-danger text-center mt-2 w-100" style="display: none;">Veuillez
                    sélectionner au moins une personne (ajout ou suppression).</div>

                @foreach ($cours->users_cours as $user)
                    <label class="d-flex align-items-center justify-content-between border px-3 py-1 rounded w-100 gap-2"
                        style="background: white;">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" name="users[]" id="user-{{ $user->user_id }}"
                                value="{{ $user->user_id }}" class="form-check-input">
                            <span class="fw-bold fs-5">{{ $user->name }} {{ $user->lastname }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <input type="number" placeholder="Points" name="points[]" id="points-{{ $user->user_id }}"
                                class="form-control text-center" style="max-width: 100px;">
                            <input type="checkbox" value="{{ $user->user_id }}" name="delete[]"
                                id="delete-{{ $user->user_id }}" class="form-check-input checkbox-delete">
                        </div>
                    </label>
                @endforeach
            </div>

            <!-- Bouton de validation -->
            <button type="button" class="btn btn-primary w-100 mt-4" data-bs-toggle="modal"
                data-bs-target="#validationModal">Valider</button>

            <!-- Modal de Confirmation -->
            <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel"
                aria-hidden="true" data-bs-backdrop="static" style="overflow-y: auto !important;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="validationModalLabel">Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Êtes-vous sûr de vouloir enregistrer ces modifications ?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Oui</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        var jQuery = jQuery.noConflict(true);
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            // Scroll to top when modal is hidden
            $('#validationModal').on('hidden.bs.modal', function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 0);
            });

            // Bouton "Tout sélectionner/déselectionner"
            $('#select-all').on('click', function() {
                let checkboxes = $('input[name="users[]"]');
                let allChecked = checkboxes.length === checkboxes.filter(':checked').length;
                checkboxes.prop('checked', !allChecked);
            });

            // Validation du formulaire à l'envoi
            $('#form-submit').on('submit', function(event) {
                let disciplines = $('#disciplines');
                let niveaux = $('#niveau');
                let usersSelected = $('input[name="users[]"]:checked').length;
                let deleteSelected = $('input[name="delete[]"]:checked').length;

                let disciplineError = $('#discipline-error');
                let niveauError = $('#niveau-error');
                let usersError = $('#users-error');

                let hasError = false;

                // Vérification des disciplines
                if (disciplines.val().length == 0) {
                    event.preventDefault();
                    disciplineError.show();
                    hasError = true;
                } else {
                    disciplineError.hide();
                }

                // Vérification des niveaux
                if (!niveaux.val()) {
                    event.preventDefault();
                    niveauError.show();
                    hasError = true;
                } else {
                    niveauError.hide();
                }

                // Vérification de la sélection des utilisateurs
                if (usersSelected === 0 && deleteSelected === 0) {
                    event.preventDefault();
                    usersError.show();
                    hasError = true;
                } else {
                    usersError.hide();
                }

                // Ferme le modal si erreur
                if (hasError) {
                    $('#validationModal').modal('hide');

                    return;
                }

                // Désactive les champs "points" pour les utilisateurs non sélectionnés
                $('input[name="users[]"]').each(function() {
                    let userId = $(this).val();
                    let pointsInput = $(`#points-${userId}`);

                    pointsInput.prop('disabled', !$(this).prop('checked'));
                });
            });
        });
    </script>
@endsection
<style>
    input[type='checkbox'] {
        margin: 0px;
        margin-right: 10px;
        padding: 8px;
    }

    input[type='number'] {
        margin: 5px 0;
    }

    form .checkbox-delete:checked {
        accent-color: red;
        background-color: red;
        border-color: red;
    }
</style>

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

            <!-- Sélecteurs Disciplines, Niveaux & Date de passage -->
            <div class="d-flex flex-wrap" style="column-gap: 10px">
                <div class="mb-3 flex-grow-1 d-flex flex-column">
                    <select name="disciplines[]" id="disciplines" class="selectpicker form-control border" multiple
                        title='Disciplines'>
                        @foreach ($disciplines as $discipline)
                            <option value="{{ $discipline->id }}">{{ $discipline->name }}</option>
                        @endforeach
                    </select>
                    <div id="discipline-error" class="alert alert-danger text-center mt-2 w-100" style="display: none">
                        Veuillez
                        sélectionner une discipline au minimum.</div>
                </div>

                <div class="mb-3 flex-grow-1 d-flex flex-column">
                    <select name="niveau" id="niveau" class="selectpicker form-control border" title='Niveau'>
                        @foreach ($niveaux as $niveau)
                            <option value="{{ $niveau->id }}">{{ $niveau->name }}</option>
                        @endforeach
                    </select>
                    <div id="niveau-error" class="alert alert-danger text-center mt-2 w-100" style="display: none;">Veuillez
                        sélectionner un niveau.</div>
                </div>

                <div class="mb-3 flex-grow-1 d-flex flex-column">
                    <input type="date" name="date" id="date" class="form-control border date-input m-0">
                    <div id="date-error" class="alert alert-danger text-center mt-2 w-100" style="display: none;">
                        Veuillez sélectionner une date passée.
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3 justify-space-between">
                <button type="button" class="btn btn-outline-secondary flex-grow-1" id="select-all">
                    Tout sélect/déselect
                </button>

                <div class="flex-grow-1 d-none d-md-block"></div>

                <button type="button" class="btn btn-primary flex-grow-1" data-bs-toggle="modal"
                    data-bs-target="#validationModal">
                    Valider
                </button>
            </div>


            <div class="d-flex flex-wrap gap-2">
                <div id="users-error" class="alert alert-danger text-center mt-2 w-100" style="display: none;">Veuillez
                    sélectionner au moins une personne (ajout ou suppression) et vérifiez que les points > 0 ou vide.</div>

                @foreach ($cours->users_cours as $user)
                    <label class="d-flex align-items-center justify-content-between border px-3 py-1 rounded w-100 gap-2"
                        style="background: white;">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" name="users[]" id="user-{{ $user->user_id }}"
                                value="{{ $user->user_id }}" class="form-check-input">
                            <span class="fw-bold" style="margin-right: 10">{{ $user->name }}
                                {{ $user->lastname }}</span>
                            <i class="fa-solid fa-eye user-modal-infos" data-user-id="{{ $user->user_id }}"
                                style="cursor: pointer;"></i>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <input type="number" placeholder="Points" name="points[]" id="points-{{ $user->user_id }}"
                                class="form-control text-center" style="max-width: 100px;">
                            <label for="delete-{{ $user->user_id }}" class="trash-checkbox">
                                <input type="checkbox" value="{{ $user->user_id }}" name="delete[]"
                                    id="delete-{{ $user->user_id }}" class="d-none form-check-input checkbox-delete">
                                <i id="trash-user-{{ $user->user_id }}" class="fa-solid fa-trash"></i>
                            </label>
                        </div>
                    </label>
                @endforeach
            </div>

            <!-- Modal de Confirmation -->
            <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel"
                aria-hidden="true" data-bs-backdrop="static" style="overflow-y: auto !important;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="validationModalLabel">Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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

        <!-- Modal des certifications -->
        <div class="modal fade" id="certificationsModal" tabindex="-1" aria-labelledby="certificationsModalLabel"
            aria-hidden="true" data-bs-backdrop="static" style="overflow-y: auto !important;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="certificationsModalLabel">Certifications</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        var jQuery = jQuery.noConflict(true);
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            let today = new Date().toISOString().split('T')[0];
            $("#date").val(today);

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

            // Afficher modal avec certifications
            $('.user-modal-infos').on('click', function(event) {
                event.preventDefault();
                let userId = $(this).data("user-id");

                $.ajax({
                    url: '/club/getUserCertifications',
                    type: 'POST',
                    data: {
                        user_id: userId,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    success: function(response) {
                        $('#certificationsModal .modal-title').html(response.title)
                        $('#certificationsModal .modal-body').html(response.html);
                        $('#certificationsModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur AJAX :", error);
                        console.log(xhr.responseJSON)
                    }
                });
            });

            // Validation du formulaire à l'envoi
            $('#form-submit').on('submit', function(event) {
                let disciplines = $('#disciplines');
                let niveaux = $('#niveau');
                let date = $('#date');
                let usersSelected = $('input[name="users[]"]:checked').length;
                let deleteSelected = $('input[name="delete[]"]:checked').length;

                let disciplineError = $('#discipline-error');
                let niveauError = $('#niveau-error');
                let dateError = $('#date-error');
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

                if (!date.val() || date.val() > today) {
                    event.preventDefault();
                    dateError.show();
                    hasError = true;
                } else {
                    dateError.hide();
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

                // Vérifie que tous les points > 0
                $('input[name="users[]"]:checked').each(function() {
                    let userId = $(this).val();
                    let pointsInput = $(`#points-${userId}`);

                    let pointsValue = pointsInput.val();

                    if (!isNaN(pointsValue) && pointsValue < 0) {
                        event.preventDefault();
                        usersError.show();
                        $('#validationModal').modal('hide');
                        return false;
                    }
                });

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

    .trash-checkbox {
        cursor: pointer;
        font-size: 1.5rem;
        color: gray;
        transition: color 0.3s ease-in-out;
    }

    .trash-checkbox input:checked+i {
        color: red;
    }
</style>

@extends('layouts.app')

@section('content')
<main id="main" class="main">
        {{-- <div id="calendar-animations">
        </div> --}}

        <div class="mt-3 text-center">
                <h2>Liste des Animations à venir</h2> 
        </div>

        @if($animations->isEmpty())
                <div class="mt-3 text-center">
                        <div class="alert alert-info text-center w-50 mx-auto" role="alert">
                                Aucune animation disponible pour le moment.
                        </div>
                </div>
        @else
                <div id="table-container">
                        <!-- Affichage du message de succès -->
                        @if(session('success'))
                                <div class="alert alert-success" role="alert">
                                        {{ session('success') }}
                                </div>
                        @endif

                        @if(session('error'))
                                <div class="alert alert-danger" role="alert">
                                        {{ session('error') }}
                                </div>
                        @endif

                        @foreach ($animations as $animation)
                                @include("Animations.modals.showAnimation")
                                @include("Animations.modals.showAttendees")
                        @endforeach

                        <table id="table-animations" class="table nowrap"  style="width:100%">
                                <thead>
                                        <tr>
                                                <th>Date</th>
                                                <th>Titre</th>
                                                <th>Prix</th>
                                                <th>Participants</th>
                                                <th>Professeur(e)</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        @foreach ($animations as $animation)
                                        <tr style="background-color: {{ $animation->category->color }}; color: {{ $animation->category->text_color }}; border-color: transparent;" data-bs-toggle="modal" data-bs-target="#showAnimationModal{{ $animation->id }}" data-backdrop="static">
                                                <td>
                                                        Du {{ \Carbon\Carbon::parse($animation->animation_starttime)->format('d/m/Y à H\hi') }}
                                                        pendant {{ \Carbon\Carbon::parse($animation->duration)->format('H\hi') }}.
                                                </td>
                                                <td>{{ $animation->title }}</td>
                                                <td>
                                                        @if ($animation->price == 0)
                                                                Gratuit
                                                        @else
                                                                {{ $animation->price }}€
                                                        @endif
                                                </td>
                                                <td data-bs-toggle="modal" data-bs-target="#showAttendeesModal{{ $animation->id }}" data-backdrop="static">{{ $animation->participants->count() }}/{{ $animation->max_participants }}</td>
                                                <td>{{ $animation->teacher->name }} {{ $animation->teacher->lastname }}</td>
                                        </tr>
                                        @endforeach
                                </tbody>
                        </table>
                </div>
        @endif

        @include("Animations.modals.inscriptionAnimation")
</main>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">

<script>
$(document).ready(function () {
        var dataTable = $('#table-animations').DataTable({
        order: [[0, 'asc']], // Tri par défaut sur la première colonne
        columnDefs: [{ type: 'date-eu', targets: 0 }], // Correction de la colonne cible (0 = première colonne)
        lengthChange: false,
        info: false,
        paging: false,
        responsive: true,
        scrollX: 'true',
        scrollY: "500px",
        scrollCollapse: true,
        language: {
                search: "Rechercher :", 
                paginate: {
                        previous: "Précédent",
                        next: "Suivant"
                },
                emptyTable: "Aucune donnée disponible",
                zeroRecords: "Aucun résultat trouvé",
                infoFiltered: "(filtré sur _MAX_ entrées)",
                }
        });

        dataTable.columns.adjust().draw();

        $("#inscriptionForm").on('submit', function(event) {
                event.preventDefault();

                let form = $(this);

                let swalOptions = {
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Annuler"
                };

                swalOptions.title = "Confirmer l'inscription";
                swalOptions.text = "Voulez-vous vraimentvous inscrire ?";
                swalOptions.confirmButtonText = "Oui, inscrire !";

                // Afficher la fenêtre de confirmation
                Swal.fire(swalOptions).then((result) => {
                        if (result.isConfirmed) {
                                
                                var formData = new FormData(form[0]);

                                // Réinitialiser les erreurs précédentes
                                $('input, select, textarea').removeClass('is-invalid');
                                $('input, select, textarea').next('.invalid-feedback').text('');

                                // Soumettre le formulaire via AJAX
                                $.ajax({
                                        url: form.attr('action'),
                                        type: 'POST',
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        headers: {
                                                'X-Requested-With': 'XMLHttpRequest'
                                        },
                                        success: function(response) {
                                                if (response.success) {
                                                        window.location.replace(response.redirect_url);
                                                }
                                        },
                                        error: function(xhr) {
                                                console.log(xhr.responseJSON);
                                                if (xhr.responseJSON) {
                                                        if (xhr.responseJSON.redirect_url) {
                                                                window.location.replace(xhr.responseJSON.redirect_url);
                                                        }
                                                        $.each(xhr.responseJSON.errors, function(key, value) {
                                                        let parts = key.split('.'); // Sépare "nom.0"
                                                        if (parts.length === 2) {
                                                                let fieldName = parts[0]; // ex: "nom"
                                                                let index = parseInt(parts[1]) + 1; // Convertit en entier et ajoute +1
                                                                let formattedKey = `${fieldName}-${index}`; // ex: "nom-1"

                                                                let inputField = $('#' + formattedKey);
                                                                if (inputField.length > 0) {
                                                                inputField.addClass('is-invalid');
                                                                inputField.next('.invalid-feedback').text(value[0]);
                                                                }
                                                        }
                                                        });
                                                }
                                        }
                                });
                        }
                });
        });
});
</script>
<style>
main {
        min-height: 50%;
}

table.dataTable {
        border-spacing: 0px 15px;
        border-collapse: separate; 
        border-bottom: 0px;
        overflow: auto;
}

#table-container .dataTables_wrapper.no-footer .dataTables_scrollBody {
        border-bottom: 0px;
}

table.dataTable thead{
        background-color: transparent 
}

table.dataTable thead th{
        border-right: solid rgba(0, 0, 0, 0.3) 1px;
        border-bottom: solid rgba(0, 0, 0, 0.3) 1px;
}

table#table-animations.dataTable tbody tr {
        cursor: pointer;
        margin: 5px 0;
        border-style: hidden;
        box-shadow: 
        0px -2px 20px -4px rgba(100,100,100,0.7), /* En haut */
        0px 2px 20px -4px rgba(100,100,100,0.7) /* En bas */
}

main a {
        color: var(--bs-link-color);
}

main #table-container {
        padding: 15px;
        min-height: 50%;
        max-height: 85%;
}

.btn-primary {
        background-color: #007bff;
        border-color: #007bff;
}

.btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
}
</style>
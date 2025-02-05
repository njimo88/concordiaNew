@extends('layouts.template')

@section('content')

<main id="main" class="main">
    <!-- Affichage du message de succès -->
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Bouton "Créer Animation" en haut -->
    <div class="d-flex justify-content-between mb-4">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAnimationModal" data-backdrop="static">Créer Animation</button>
    </div>

    <!-- Boutons pour filtrer les animations -->
    <div class="d-flex justify-content-end mb-4 flex-wrap gap-3 mb-4">
        <button type="button" class="btn btn-primary btn-sm" id="showUpcomingBtn">Animations à venir</button>
        <button type="button" class="btn btn-secondary btn-sm" id="showPastBtn">Animations passées</button>
    </div>

    <!-- Liste des animations triées par animation_starttime -->
    <div id="animations-a-venir" class="content active calendar-container">
        {{-- <div id="calendarAVenir" class="calendar"></div> --}}
        @if ($animationsAVenir->isNotEmpty())
            @foreach ($animationsAVenir as $animation)
                @include("admin.animations.modals.editAnimation")
                @include("admin.animations.modals.showAttendees")
            @endforeach
            <div class="table-container">
                <table id="table-animations-a-venir" class="table nowrap"  style="width:100%">
                        <thead>
                                <tr>
                                        <th>Date</th>
                                        <th>Visibilité</th>
                                        <th>Titre</th>
                                        <th>Prix</th>
                                        <th>Participants</th>
                                        <th>Professeur(e)</th>
                                </tr>
                        </thead>
                        <tbody>
                            @foreach ($animationsAVenir as $animation)
                            <tr style="background-color: {{ $animation->category->color }}; color: {{ $animation->category->text_color }}; border-color: transparent;" data-bs-toggle="modal" data-bs-target="#editAnimationModal{{ $animation->id }}" data-backdrop="static">
                                    <td>
                                            Du {{ \Carbon\Carbon::parse($animation->animation_starttime)->format('d/m/Y à H\hi') }}
                                            pendant {{ \Carbon\Carbon::parse($animation->duration)->format('H\hi') }}.
                                    </td>
                                    <td>
                                        <span class="badge-visibility" style="background-color: {{ $animation->visibilite ? 'green' : 'red' }};"></span>
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
        @else
            <h1>Aucune animation à venir</h1>
        @endif
    </div>

    <!-- Liste des animations triées par animation_starttime -->
    <div id="animations-passees" class="content calendar-container">
        {{-- <div id="calendarPassees" class="calendar"></div> --}}
        @if ($animationsPassees->isNotEmpty())
            @foreach ($animationsPassees as $animation)
                @include("admin.animations.modals.editAnimation")
                @include("admin.animations.modals.showAttendees")
            @endforeach
            <div class="table-container">
                <table id="table-animations-passees" class="table nowrap"  style="width:100%">
                        <thead>
                                <tr>
                                        <th>Date</th>
                                        <th>Visibilité</th>
                                        <th>Titre</th>
                                        <th>Prix</th>
                                        <th>Participants</th>
                                        <th>Professeur(e)</th>
                                </tr>
                        </thead>
                        <tbody>
                            @foreach ($animationsPassees as $animation)
                            <tr style="background-color: {{ $animation->category->color }}; color: {{ $animation->category->text_color }}; border-color: transparent;" data-bs-toggle="modal" data-bs-target="#editAnimationModal{{ $animation->id }}" data-backdrop="static">
                                    <td>
                                            Du {{ \Carbon\Carbon::parse($animation->animation_starttime)->format('d/m/Y à H\hi') }}
                                            pendant {{ \Carbon\Carbon::parse($animation->duration)->format('H\hi') }}.
                                    </td>
                                    <td>
                                        <span class="badge-visibility" style="background-color: {{ $animation->visibilite ? 'green' : 'red' }};"></span>
                                    </td>
                                    <td>{{ $animation->title }}</td>
                                    <td>
                                            @if ($animation->price != 0)
                                                    Gratuit
                                            @else
                                                    {{ $animation->price }}€
                                            @endif
                                    </td>
                                    <td>{{ $animation->participants->count() }}/{{ $animation->max_participants }}</td>
                                    <td>{{ $animation->teacher->name }} {{ $animation->teacher->lastname }}</td>
                            </tr>
                            @endforeach
                    </tbody>
            </table>
        </div>
        @else
            <h1>Aucune animation passée</h1>
        @endif
    </div>
    @include("admin.animations.modals.creerAnimation")
</main>
@endsection

{{-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.ckeditor-to-transform').forEach(editor => {
            var editorTextArea = CKEDITOR.replace(editor.id, {
                versionCheck: false,
                filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
                filebrowserBrowseUrl: '/elfinder/ckeditor',
                filebrowserUploadMethod: 'form',
                language: 'fr',
                toolbar: [
                    { name: 'document', items: ['Source', 'Save', 'NewPage', 'Preview', 'Print', 'Templates'] },
                    { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                    { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', 'Scayt'] },
                    { name: 'forms', items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'] },
                    '/',
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'] },
                    { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'] },
                    '/',
                    { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                    { name: 'colors', items: ['TextColor', 'BGColor'] },
                    { name: 'tools', items: ['Maximize', 'ShowBlocks', 'About'] },
                ],
            });
        });

        
        // // Initialisation des calendriers
        // var calendarAVenir;
        // var calendarPassees;
        
        // function createCalendar(calendarEl, events) {
        //     return new FullCalendar.Calendar(calendarEl, {
        //         initialView: 'listYear',
        //         locale: 'fr', 
        //         noEventsText: "Aucune animation trouvée",
        //         height: 500,
        //         stickyFooterScrollbar: true,
        //         events: events,
        //         buttonText: {
        //             today: "Aujourd'hui",
        //         },
        //         windowResize: function() {
        //             this.refetchEvents();
        //         },
        //         eventClick: function(info) {
        //             $('#editAnimationModal' + info.event.id).modal('show');
        //         },
        //         eventContent: function(arg) {
        //             const event = arg.event;
        //             const customContent = `
        //                 <div class="event-content" style="display: flex; align-items: center;">
        //                     <div class="event-title" style="white-space: nowrap; margin-right: 10px;"><strong>Titre :</strong> ${event.title}</div>
        //                     <div class="event-details" style="display: flex; gap: 10px; flex-wrap: nowrap;">
        //                         ${event.extendedProps.price ? `<p style="margin: 0;"><strong>Prix :</strong> ${event.extendedProps.price}€</p>` : ''}
        //                         <p style="margin: 0;"><strong>Participants :</strong> ${event.extendedProps.max_participants}</p>
        //                         <p style="margin: 0;"><strong>Professeur(e) :</strong> ${event.extendedProps.teacher}</p>
        //                     </div>
        //                 </div>
        //             `;
        //             return { html: customContent };
        //         },
        //         eventDidMount: function(info) {
        //             info.el.style.backgroundColor = info.event.extendedProps.backgroundColor;
        //             info.el.style.color = info.event.extendedProps.textColor;
        //         }
        //     });
        // };

        function handleDataTable(id){
            return $('#' + id).DataTable({
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
        }

        var dataTableAVenir = handleDataTable("table-animations-a-venir")
        dataTableAVenir.columns.adjust().draw();
        
        var dataTablePassees = handleDataTable("table-animations-passees")
        dataTablePassees.columns.adjust().draw();

        const showUpcomingBtn = document.getElementById("showUpcomingBtn");
        const showPastBtn = document.getElementById("showPastBtn");
        const upcomingContent = document.querySelector("#animations-a-venir");
        const pastContent = document.querySelector("#animations-passees");
        
        // // Créez les événements pour chaque calendrier
        // const eventsAVenir = [
        //     @foreach ($animationsAVenir as $animation)
        //     {
        //         id: "{{ $animation->id }}",
        //         title: "{{ $animation->title }}",
        //         start: "{{ $animation->animation_starttime }}",
        //         end: "{{ $animation->animation_endtime }}",
        //         color: "{{ $animation->visibilite == 0 ? '#d61c1c' : '#08a80b' }}",
        //         price: "{{ $animation->price }}",
        //         max_participants: "LALAL/{{ $animation->max_participants }}", //TODO Mettre nombre de personnes inscrites
        //         teacher: "{{ $animation->teacher->name }} {{ $animation->teacher->lastname }}",
        //         extendedProps: {
        //             backgroundColor: "{{ $animation->category->color }}",
        //             textColor: "{{ $animation->category->text_color }}",
        //         },
        //     },
        //     @endforeach
        // ];

        // const eventsPassees = [
        //     @foreach ($animationsPassees as $animation)
        //     {
        //         id: "{{ $animation->id }}",
        //         title: "{{ $animation->title }}",
        //         start: "{{ $animation->animation_starttime }}",
        //         end: "{{ $animation->animation_endtime }}",
        //         color: "{{ $animation->visibilite == 0 ? '#d61c1c' : '#08a80b' }}",
        //         price: "{{ $animation->price }}",
        //         max_participants: "LALAL/{{ $animation->max_participants }}", //TODO Mettre nombre de personnes inscrites
        //         teacher: "{{ $animation->teacher->name }} {{ $animation->teacher->lastname }}",
        //         extendedProps: {
        //             backgroundColor: "{{ $animation->category->color }}",
        //             textColor: "{{ $animation->category->text_color }}",
        //         },
        //     },
        //     @endforeach
        // ];

        // // Créez les calendriers initialement
        // var calendarElAVenir = document.getElementById('calendarAVenir');
        // var calendarElPassees = document.getElementById('calendarPassees');

        // // Initialisez les calendriers
        // calendarAVenir = createCalendar(calendarElAVenir, eventsAVenir);
        // calendarPassees = createCalendar(calendarElPassees, eventsPassees);
        
        // // Render les calendriers
        // calendarAVenir.render();
        // calendarPassees.render();
        
        const toggleButtonColor = (button) => {
            if (button.classList.contains("btn-secondary")) {
                button.classList.remove("btn-secondary");
                button.classList.add("btn-primary");
            } else {
                button.classList.remove("btn-primary");
                button.classList.add("btn-secondary");
            }
        };

        resetOverflowCalendars();

        showUpcomingBtn.addEventListener("click", () => {
            upcomingContent.classList.add("active");
            pastContent.classList.remove("active");
            toggleButtonColor(showUpcomingBtn);
            toggleButtonColor(showPastBtn);

            // Réinitialisation du calendrier à venir
            // calendarAVenir.render();
            // resetOverflowCalendars();

            // Réajustement de la table
            dataTableAVenir.columns.adjust().draw();
        });

        showPastBtn.addEventListener("click", () => {
            pastContent.classList.add("active");
            upcomingContent.classList.remove("active");
            toggleButtonColor(showPastBtn);
            toggleButtonColor(showUpcomingBtn);

            // Réinitialisation du calendrier passé
            // calendarPassees.render();
            // resetOverflowCalendars();

            // Réajustement de la table
            dataTablePassees.columns.adjust().draw();
        });
    });

    function resetOverflowCalendars() {
        $(".fc-scroller.fc-scroller-liquid").css("overflow", "auto");
    }

    $(document).ready(function() {
    function handleFormSubmission(formClass, typeAction) {
        $(formClass).on('submit', function(event) {
            event.preventDefault();

            let form = $(this);

            let swalOptions = {
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Annuler"
            };

            if (typeAction === "create") {
                swalOptions.title = "Confirmer la création";
                swalOptions.text = "Voulez-vous vraiment créer cette animation ?";
                swalOptions.confirmButtonText = "Oui, créer !";
            } else if (typeAction === "edit") {
                swalOptions.title = "Confirmer la modification";
                swalOptions.text = "Voulez-vous vraiment modifier cette animation ?";
                swalOptions.confirmButtonText = "Oui, modifier !";
            } else {
                swalOptions.title = "Confirmer la suppression";
                swalOptions.text = "Voulez-vous vraiment supprimer cette animation ? CETTE ACTION EST IRREVERSIBLE";
                swalOptions.confirmButtonText = "Oui, supprimer !";
            }

            // Afficher la fenêtre de confirmation
            Swal.fire(swalOptions).then((result) => {
                if (result.isConfirmed) {
                    // Mettre à jour CKEditor si nécessaire
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }

                    var formData = new FormData(form[0]);

                    // console.log("Contenu du FormData :");
                    // console.log(formData);

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
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    $('#' + key).addClass('is-invalid');
                                    if (key.includes('description')) {
                                        $('#cke_' + key).next('.invalid-feedback').text(value[0]);
                                    } else {
                                        $('#' + key).next('.invalid-feedback').text(value[0]);
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });
    }

    // Appliquer la fonction à chaque type de formulaire
    handleFormSubmission('#createAnimationForm', 'create');
    handleFormSubmission('.editAnimationForm', 'edit');
    handleFormSubmission('.deleteAnimationForm', 'delete');
});
</script>
<style>
li {
    font-weight: bold;
}

table .badge-visibility {
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 50%; /* Rend la pastille ronde */
    border: 2px solid black; /* Bordure noire pour bien la voir */
}

table.table.dataTable {
        border-spacing: 0px 15px;
        border-collapse: separate; 
        border-bottom: 0px;
        overflow: auto;
}

.table-container .dataTables_wrapper.no-footer .dataTables_scrollBody {
        border-bottom: 0px;
}

table.table.dataTable thead{
        background-color: transparent 
}

table.table.dataTable thead th{
        border-right: solid rgba(0, 0, 0, 0.3) 1px;
        border-bottom: solid rgba(0, 0, 0, 0.3) 1px;
}

table.table.dataTable tbody tr {
        cursor: pointer;
        margin: 5px 0;
        border-style: hidden;
        box-shadow: 
        0px -2px 20px -4px rgba(100,100,100,0.7), /* En haut */
        0px 2px 20px -4px rgba(100,100,100,0.7) /* En bas */
}

div.invalid-feedback{
    display: block
}

.content {
    display: none;
}

.content.active {
    display: block;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

main .fc .fc-toolbar.fc-header-toolbar, main .fc .fc-toolbar-chunk {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

main .fc td {
    vertical-align: middle;
}

main .fc .fc-list-table{
    min-width: 400px;
}

.event-details {
    white-space: nowrap;
}

.fc-list-event {
    transition: all 0.2s ease-in-out;
}

.fc-list-event td {
    cursor: pointer;
}

.fc-list-event:hover td {
    padding-top: 15px;
    padding-bottom: 15px;
    background-color: inherit !important;
}
</style>
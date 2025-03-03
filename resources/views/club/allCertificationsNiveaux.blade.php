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

        <select id="disciplines-select" class="selectpicker form-control border" multiple title='Disciplines'>
            @foreach ($disciplines as $discipline)
                <option value="{{ $discipline->id }}">{{ $discipline->name }}</option>
            @endforeach
        </select>

        <select id="levels-select" class="selectpicker form-control border" multiple title='Niveaux'>
            @foreach ($levels as $level)
                <option value="{{ $level->id }}">{{ $level->name }}</option>
            @endforeach
        </select>

        <button class="btn btn-primary" id="exporter-pdf">Exporter en pdf</button>

        <table class="table" id="main-table">
            <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Niveaux</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="name">{{ $user->name }}</td>
                        <td class="lastname">{{ $user->lastname }}</td>
                        <td class="certifications-content" data-certifications='@json(
                            $user->certifications->flatten()->map(function ($certification) {
                                return [
                                    'discipline_id' => $certification->discipline->id,
                                    'level_id' => $certification->level->id,
                                ];
                            }))'>

                            @foreach ($user->certifications as $discipline)
                                @foreach ($discipline as $certification)
                                    @if ($loop->first)
                                        <span><strong>{{ $certification->discipline->name }}</strong></span>
                                    @endif
                                    <svg width="24" height="24" stroke="black" stroke-width="1" viewBox="0 0 24 24"
                                        fill="{{ $certification->level->color }}" xmlns="http://www.w3.org/2000/svg">
                                        <polygon points="12,2 15,10 24,10 17,15 19,24 12,19 5,24 7,15 0,10 9,10" />
                                    </svg>
                                    <span class="ms-2">
                                        {{ $certification->level->name }}
                                        @if ($certification->points)
                                            ({{ $certification->points }}pts)
                                        @endif
                                    </span>
                                    @if ($loop->last)
                                        <br>
                                    @endif
                                @endforeach
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <script src="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        var jQuery = jQuery.noConflict(true);
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            let table = $("#main-table").DataTable({
                responsive: true,
                paging: false,
            });

            $("#disciplines-select, #levels-select").on('change', function() {
                filterTable();
            });


            $("#exporter-pdf").on('click', function() {
                exporterPdf();
            });

            filterTable();

            function filterTable() {
                var selectedDisciplines = $("#disciplines-select").val() || [];
                var selectedLevels = $("#levels-select").val() || [];

                table.rows().every(function() {
                    var row = jQuery(this.node());
                    var data = row.find(".certifications-content").data(
                        "certifications");

                    var match = false;

                    if (data && data.length > 0) {
                        data.forEach(function(data) {
                            var disciplineId = data.discipline_id.toString();
                            var levelId = data.level_id.toString();

                            var disciplineMatch = selectedDisciplines.length === 0 ||
                                selectedDisciplines.includes(disciplineId);
                            var levelMatch = selectedLevels.length === 0 || selectedLevels.includes(
                                levelId);

                            if (disciplineMatch && levelMatch) {
                                match = true;
                            }
                        });
                    }

                    if (match) {
                        row.show();
                        row.addClass("export-pdf")
                    } else {
                        row.hide();
                        row.removeClass("export-pdf")
                    }
                });
            }


            function exporterPdf() {
                let dataForPdf = [];

                $('.export-pdf').each(function(index, row) {
                    let name = $(row).find('td.name').text();
                    let lastname = $(row).find('td.lastname').text();
                    let certificationsContent = $(row).find('.certifications-content').text();

                    let data = {
                        name: name,
                        lastname: lastname,
                        certificationsContent: certificationsContent,
                    }

                    dataForPdf.push(data)
                })

                $.ajax({
                    url: '/club/certifications-niveaux/export-pdf',
                    type: 'POST',
                    data: {
                        users: dataForPdf
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response) {
                        let blob = new Blob([response], {
                            type: 'application/pdf'
                        });
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "Diplomes.pdf";
                        link.click();
                        window.URL.revokeObjectURL(link.href);
                    },
                    error: function(xhr) {
                        console.error("Erreur lors de la génération du PDF", xhr);
                    }
                });
            }
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

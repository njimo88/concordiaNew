@extends('layouts.template')

@section('content')
    @php
        require_once app_path() . '/fonction.php';
        $saison_active = saison_active();
    @endphp

    <main id="main" class="main">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-4 d-flex justify-content-end mb-3">
                <label>Saison</label>
            </div>
            <div class="col-md-8 mb-3">
                <div class="col-6">
                    <form id="seasonForm">
                        @csrf
                        <select class="form-control" name="saison" id="saison">
                            @foreach ($saison_list as $data)
                                <option value="{{ $data->saison }}" {{ $data->saison == $saison_actu ? 'selected' : '' }}>
                                    {{ $data->saison }} - {{ $data->saison + 1 }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <hr>
        </div>

        <div class="d-grid gap-4">
            @foreach ($shop_article_first as $data)
                <div class="input-container">
                    <img class="px-2" style="height: 30px;" src="{{ asset('assets/images/logo-admin-list.png') }}"
                        alt="Logo" onclick="generatePDF({{ $data->id_shop_article }})">
                    <input readonly onclick="toggleElement('{{ $data->id_shop_article }}')" class="btn m-0 session_title"
                        style="font-weight: bold; text-align: left;"
                        value="{{ $data->title }} ({{ $data->totalBillsCount() }} / {{ $data->stock_ini }})">
                    <a href="{{ route('certifications_niveaux', ['id' => $data->id_shop_article]) }}" target="_blank"
                        class="btn btn-danger m-2" style="font-size: 14px"><i class="fas fa-graduation-cap"></i></a>
                </div>

                <div id="my-element-{{ $data->id_shop_article }}" style="display: none;">
                    <div id="content">
                        <div class="row">
                            <div class="col-4">
                                <form action="{{ route('enregistrer_appel', ['id' => $data->id_shop_article]) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Valider l'appel</button>
                            </div>
                            <div class="col-4">
                                <input type="date" class="form-control m-0" name="date_appel"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-4 d-flex justify-content-center">
                                <button type="button" class="btn btn-secondary">
                                    <a href="{{ route('historique_appel', $data->id_shop_article) }}">Historique des
                                        appels</a>
                                </button>
                            </div>
                        </div>
                        <br>

                        <table class="table table-hover" style="color:black">
                            <tbody>
                                @foreach ($data->users_cours as $dt)
                                    @foreach ($dt->bills as $bill)
                                        <tr
                                            style="background-color: {{ $dt->row_color == 'none' ? 'lime' : $dt->row_color }};">
                                            <td>
                                                <div class="form-check">
                                                    <input name="user_id[]" value="{{ $dt->user_id }}" hidden>
                                                    <input class="form-check-input" type="checkbox"
                                                        name="marque_presence[{{ $dt->user_id }}]" value="1"
                                                        id="myCheckbox">
                                                    <label class="form-check-label" for="flexCheckDefault"
                                                        style="color:black">
                                                        {{ $dt->name }} {{ $dt->lastname }}
                                                        @if (\Carbon\Carbon::parse($dt->birthdate)->isBirthday())
                                                            <i class="fa fa-birthday-cake"></i>
                                                        @endif
                                                    </label>

                                                </div>
                                            </td>
                                            @if (Auth::user()->role >= 90)
                                                <td>
                                                    <a target="_blank"
                                                        href="{{ route('facture.showBill', ['id' => $bill->id]) }}">
                                                        <i class="fas fa-file-invoice"
                                                            style="color: black; cursor: pointer; text-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5); font-size: 1.2rem;"></i>
                                                    </a>
                                                </td>
                                            @endif
                                            <td>
                                                <i class="fas fa-eye openmodal" style="color:blue; cursor: pointer;"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                    data-user-id="{{ $dt->user_id }}"></i>
                                            </td>
                                            <td>
                                                <a href="/admin/members/user/{{ $dt->user_id }}" target="_blank">
                                                    <i class="fas fa-id-card"
                                                        style="color:{{ $dt->medical_certificate_color }}; cursor: pointer; text-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5); font-size: 1.2rem;"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="col-6 col-md-2">
                                                    <a href="tel: {{ $dt->phone }}"
                                                        target="_blank">{{ $dt->phone }}</a>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $dt->birthdate }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal -->
        <div class="modal fade" id="display_info_user" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Informations</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="just_display">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        function toggleElement(id) {
            var element = document.getElementById('my-element-' + id);
            if (element.style.display === 'none') {
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        }

        document.getElementById('saison').addEventListener('change', function() {
            document.getElementById('seasonForm').submit();
        });

        function generatePDF(courseId) {
            window.open('/generate-pdf/' + courseId, '_blank');
        }
    </script>

    <style>
        .input-container {
            display: inline-flex;
            align-items: center;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            background-color: #e3e3e3;
        }

        .input-container input {
            border: none;
            outline: none;
            padding: 0.375rem;
            background-color: #e3e3e3;
        }
    </style>
@endsection

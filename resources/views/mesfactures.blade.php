@extends('layouts.app')

@section('content')
    <style>
        body {
            font-family: 'Roboto', sans-serif !important;
            background-color: #f1f1f1;
        }

        html,
        body,
        #app {
            min-height: 100%;
        }

        #content {
            min-height: 100%;
            /* assuming your footer height is 100px; adjust as required */
            margin-bottom: -100px;
        }

        #footer {
            height: 100px;
        }

        .container {
            background: linear-gradient(to right, #fafafa, #f5f5f5);
            /* subtle gradient */
            padding: 20px;
            margin: 20px auto;
            border-radius: 8px;
            /* rounded corners */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1), 0 2px 5px rgba(0, 0, 0, 0.05);
            /* soft shadow */
            transition: box-shadow 0.3s ease;
            /* smooth transition for the shadow */
        }

        .container:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1), 0 3px 7px rgba(0, 0, 0, 0.08);
            /* shadow deepens on hover */
        }

        :root {
            --clr-gray300: #ccc;
            --clr-gray150: #f5f5f5;
            --clr-link: #007bff;
            --clr-primary: #272e5c;
        }

        table {
            border-collapse: collapse;
            box-shadow: 0 5px 10px var(--clr-gray300);
            background-color: white;
            text-align: left;
            overflow: hidden;
        }

        thead {
            box-shadow: 0 5px 10px var(--clr-gray300);
        }

        th {
            padding: 1rem 2rem;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
            font-size: 0.7rem;
            font-weight: 900;
        }

        td {
            padding: 1rem 2rem;
        }

        a {
            text-decoration: none;
            color: var(--clr-link);
        }

        .amount {
            text-align: right;
        }

        tr:nth-child(even) {
            background-color: var(--clr-gray150);
        }

        @media only screen and (max-width: 600px) {
            .table-row {
                font-size: 11px;
            }
        }

        /* Toggle Button Style */
        .button {
            background-color: #d2d2d2;
            width: 50px;
            height: 25px;
            border-radius: 50px;
            cursor: pointer;
            position: relative;
            transition: 0.2s;
        }

        .button::before {
            position: absolute;
            content: '';
            background-color: #fff;
            width: 22px;
            height: 22px;
            border-radius: 50px;
            margin: 1.5px;
            transition: 0.2s;
        }

        input:checked+.button {
            background-color: var(--clr-primary);
        }

        input:checked+.button::before {
            transform: translateX(25px);
        }

        input {
            display: none;
        }

        .toggle-label {
            display: inline-block;
            margin-left: 10px;
            vertical-align: middle;
        }

        table,
        th,
        td {
            text-align: center;
        }

        body,
        h1,
        h2,
        h3,
        p {
            color: #272e5c;
        }

        h2 {
            color: #c20012;
        }

        a {
            color: #c20012;
            text-decoration: none;
        }
    </style>
    <section style="background-image: url('{{ asset('/assets/images/jbb.jpg') }}');"
        class=" bg-light position-relative bg-cover top-banner-page">
        <img loading="lazy" src="{{ asset('/assets/images/gymm.jpg') }}" alt="Blog" title="Blog" class="d-none">
        <div class="dark-overlay"></div>
        <div class="position-absolute bottom-0 start-50 translate-middle-x container-xl z-100">
            <div class="d-flex flex-column justify-content-end">
                <h1 class="h2 fw-black text-white">Mes factures</h1>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="toggle-section">

            <!-- Titre -->
            <div class="mb-3">
                <h2>Gestion des factures</h2>
            </div>

            <!-- Ligne des éléments de bascule -->
            <div class="d-flex align-items-center">

                <div>
                    Factures totales : <span>{{ count($bills) }}</span>
                </div>

                <div class="toggle-container mx-3 d-flex align-items-center">
                    <input type="checkbox" id="factureCheckbox" checked>
                    <label for="factureCheckbox" class="button me-2"></label>
                    <span class="toggle-label me-2">Facture</span>
                </div>

                <!-- Toggle for Devis -->
                <div class="toggle-container me-3 d-flex align-items-center">
                    <input type="checkbox" id="devisCheckbox" checked>
                    <label for="devisCheckbox" class="button me-2"></label>
                    <span class="toggle-label me-2">Devis</span>
                </div>

                @if ($additionalChargesCount > 0)
                    <div class="alert alert-warning" role="alert">
                        Votre famille a {{ $additionalChargesCount }} charge(s) supplémentaire(s) en attente.
                    </div>
                @endif

            </div>
        </div>


        <div class="table-box m-4">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Moyen de paiement</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($bills as $bill)
                        <tr data-bill-type="{{ $bill->type }}">
                            <td style="white-space: nowrap;">
                                <div class="d-flex align-items-center">
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="{{ route('user.showBill', ['id' => $bill->id]) }}" target="_blank"
                                        data-bs-toggle="tooltip" title="Afficher Facture" class="me-2">
                                        {{ intval($bill->id) }}
                                    </a>
                                    @if ($bill->additionalCharges->isNotEmpty())
                                        <span class="badge bg-warning text-dark">Notice</span>
                                    @endif
                                </div>
                            </td>

                            <td style="font-weight:bold;">{{ $bill->user->lastname ?? '' }} {{ $bill->user->name ?? '' }}
                            </td>
                            <td>
                                <img style="height: 30px" src="{{ $bill->image }}" alt="">
                                <span class="d-none">{{ $bill->paymentMethod->payment_method ?? 'Carte bleue' }}</span>
                            </td>
                            <td>{{ date('d/m/Y à H:i', strtotime($bill->date_bill)) }}</td>
                            <td class="amount" style="font-weight:bold;">
                                {{ number_format($bill->payment_total_amount, 2, ',', ' ') }}€
                            </td>
                            <td>
                                <img src="{{ $bill->image_status }}" alt="Status Image">
                                <span class="d-none">{{ $bill->status->status ?? 'Paiement accepté' }}</span>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6" style="text-align:center;">Aucune facture à afficher.</td>
                    </tr>
                </tbody>
            </table>
            <div id="noDataMessage"
                style="display: none; color:black;  margin: auto;
            text-align: center;padding: 10px;">Aucune
                donnée disponible</div>
        </div>
    </div>
    </div>
@endsection

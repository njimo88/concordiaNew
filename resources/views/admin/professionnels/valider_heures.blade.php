@extends('layouts.template')

@section('content')
@php
    $moisTraduits = [
    'January' => 'Janvier',
    'February' => 'Février',
    'March' => 'Mars',
    'April' => 'Avril',
    'May' => 'Mai',
    'June' => 'Juin',
    'July' => 'Juillet',
    'August' => 'Août',
    'September' => 'Septembre',
    'October' => 'Octobre',
    'November' => 'Novembre',
    'December' => 'Décembre',
];

@endphp
<main class="main" id="main">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <div class="container mt-5">
        <h1 class="mb-4">Validation des heures</h1>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Mois</th>
                    <th scope="col">Année</th>
                    <th scope="col">Voir la déclaration</th>
                </tr>
            </thead>
            <tbody>
                @forelse($declaration as $item)
                    <tr>
                        <td>{{ $item->lastname }} {{ $item->name }}</td>
                        <td>{{ $moisTraduits[strftime('%B', mktime(0, 0, 0, $item->mois  %12, 10))] }}</td>

                        <td>{{ $item->annee }}</td>
                        <td>
                            <a href="{{ route('voir_declaration', ['declaration_id' => $item->id]) }}" class="btn btn-primary">Voir la déclaration</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Aucune déclaration disponible</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>

@endsection
<!-- Fin de la section content -->
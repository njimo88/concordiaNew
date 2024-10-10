@extends('layouts.template')

@section('content')
<style>
    /* Ajoutez votre CSS personnalisé ici pour correspondre à l'image que vous avez envoyée */
    .btn-primary {
      background-color: #4e73df; /* Exemple de couleur */
      border: none;
    }
    
    .modal-content {
      border-radius: 0.5rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
      background-color: #f8f9fc;
      border-bottom: none;
    }
    
    .modal-title {
      color: #5a5c69;
    }
    
    .btn-close {
      color: #d1d3e2;
    }
    
    .modal-footer {
      border-top: none;
    }
    
    /* Plus de styles personnalisés si nécessaire */
  </style>
  
 <main id="main" class="main">

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        
    @endif

    <div class="row">
        <!-- Titre et breadcrumb à gauche -->
        <div class="pagetitle col-md-6">
            <h1>Liste des factures</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Paiement</li>
                    <li class="breadcrumb-item active">Réduction</li>
                </ol>
            </nav>
        </div>
    
        <!-- Bouton à droite -->
        <div class="col-md-6 d-flex justify-content-md-end align-items-center p-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReductionModal">
                Ajouter une réduction <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>
      
    <div class="modal fade" id="addReductionModal" tabindex="-1" aria-labelledby="addReductionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReductionModalLabel">Nouvelle Réduction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route ('reductionsadd') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Champs de formulaire -->
                        <div class="form-group mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="value" class="form-label">Valeur (en €)</label>
                            <input type="number" class="form-control" id="value" name="value" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="percentage" class="form-label">Pourcentage</label>
                            <input type="number" class="form-control" id="percentage" name="percentage" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="startvalidity" class="form-label">Date de début de validité</label>
                            <input type="date" class="form-control" id="startvalidity" name="startvalidity" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="endvalidity" class="form-label">Date de fin de validité</label>
                            <input type="date" class="form-control" id="endvalidity" name="endvalidity" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="usable" class="form-label">Nombre d'utilisations limité</label>
                            <input type="number" class="form-control" id="usable" name="usable" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="automatic" class="form-label">Automatique</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="automatic" name="automatic">
                                <label class="form-check-label" for="automatic">
                                    Oui
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="state" class="form-label">Activation</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="state" name="state">
                                <label class="form-check-label" for="state">
                                    Oui
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer Réduction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    

    <table id="reductions" class="table nowrap dataTable no-footer dtr-inline" style="width: 100%; background-color: rgb(253, 239, 255); border-width: 2px; border-style: solid; border-color: grey;" aria-describedby="reductions_info">
        <thead>
            <tr>
                <th data-priority="5" class="sorting sorting_asc" tabindex="0" aria-controls="reductions" rowspan="1" colspan="1" style="width: 72px;" aria-sort="ascending" aria-label="ID : activate to sort column descending">ID </th>
                <th data-priority="1" class="sorting" tabindex="0" aria-controls="reductions" rowspan="1" colspan="1" style="width: 295px;" aria-label="Code: activate to sort column ascending">Code</th>
                <th data-priority="6" class="sorting" tabindex="0" aria-controls="reductions" rowspan="1" colspan="1" style="width: 628px;" aria-label="Description: activate to sort column ascending">Description</th>
                <th data-priority="3" class="sorting" tabindex="0" aria-controls="reductions" rowspan="1" colspan="1" style="width: 130px;" aria-label="Valeur: activate to sort column ascending">Valeur</th>
                <th data-priority="2" class="sorting" tabindex="0" aria-controls="reductions" rowspan="1" colspan="1" style="width: 72px;" aria-label="%: activate to sort column ascending">%</th>
                <th data-priority="4" class="sorting_disabled" rowspan="1" colspan="1" style="width: 167px;" aria-label=""></th>
            </tr>
        </thead>
        <tbody>
            @foreach($shopReductions as $shopReduction)
                <tr>
                    <td class="sorting_1 dtr-control">{{ $shopReduction->id_shop_reduction }}</td>
                    <td>{{ $shopReduction->code }}</td>
                    <td>{{ $shopReduction->description }}</td>
                    <td>{{ $shopReduction->value }} €</td>
                    <td>{{ $shopReduction->percentage }} %</td>
                    <td>
                        <a href="{{ route('edit.reduction', ['id' => $shopReduction->id_shop_reduction]) }}"><i class="fa fa-pencil fa-xl" style="color:orange"></i></a>

                        <a href="#" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer cette réduction ?')){document.getElementById('delete-form-{{ $shopReduction->id_shop_reduction }}').submit();}"><i class="fa fa-xmark fa-2xl" style="color:red"></i></a>
                        <form id="delete-form-{{ $shopReduction->id_shop_reduction }}" action="{{ route('paiement.reduction.delete', $shopReduction->id_shop_reduction) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
  
      
    
 </main>
@endsection
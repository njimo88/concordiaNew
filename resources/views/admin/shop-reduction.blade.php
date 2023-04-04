@extends('layouts.template')

@section('content')
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
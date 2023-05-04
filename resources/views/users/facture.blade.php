@extends('layouts.app')

@section('content')


<div class="py-5" style="min-height:100vh; background-image: url('{{asset("/assets/images/background.png")}}'); color:#fff;">
    <div class="container border border-dark" style=" background : #fff">
        <div class="row d-flex justify-content-center">
            <div class="col-11 d-flex justify-content-between   text-dark p-4">
                <div>
                    <h1>Liste des factures/Devis</h1>
                </div>
                @if($showPaymentButton)
                <div>
                    <a href="#" class="btn btn-primary">Payer facture</a>
                </div>
                @endif
            </div>
            
                <div class="col-11">  
                        <div class="row">
                            <div class="col-12 main-datatable">
                                <div class="card_body">
                                    <div class="row d-flex">
                                        <!-- Button trigger modal -->
            
                                        <!-- Modal -->
                                        <div class="modal fade " id="addMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content ">
                                                
                                            </div>
                                            </div>
                                        </div>
                                        @if (session('success'))
                                                <div class="alert alert-success col-11">
                                                    {{ session('success') }}
                                                </div>
                                        @endif
                                    </div>
                                    <form class="row mb-3">
                                        <div class="form-check col-1">
                                            <input class="form-check-input" type="checkbox" value="" id="factureCheckbox" checked>
                                            <label class="form-check-label" for="factureCheckbox">
                                                Facture
                                            </label>
                                        </div>
                                        <div class="form-check col-2">
                                            <input class="form-check-input" type="checkbox" value="" id="devisCheckbox" checked>
                                            <label class="form-check-label" for="devisCheckbox">
                                                Devis
                                            </label>
                                        </div>
                                    </form>
                                    <div class="overflow-x">
                                        <table style=" border: 1px solid ;background : #fff;" id="myTablefacture"  class="table cust-datatable dataTable no-footer">
                                            <thead>
                                                <th style="min-width:50px;"> <a>ID Facture</a></th>
                                                <th style="min-width:150px;"><a>Nom</a></th>
                                                <th style="min-width:150px;"><a >Moyen de paiement</a></th>
                                                <th id="date" style="min-width:100px;"><a >Date</a></th>
                                                <th style="min-width:100px;"><a >Total</a></th>
                                                <th style="min-width:150px;"><a >Statut</a></th>
                                                <th style="min-width:150px">Actions</th>
                                            </thead>                            
                                            <tbody>
                                                @foreach ($bill as $bills )
                                            
                                                    <tr data-bill-type="{{ $bills->type }}" style="background-color: {{ $bills->row_color}}"> 
                                                        <td>
                                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Afficher Facture">
                                                                <a type="button" target="_blank" class=" user-link a text-black "  href="{{ route('user.showBill', ['id' => $bills->id]) }}">{{ intval($bills->id) }}</i></a>
                                                            </span>
                                                        </td>
                                                        <td style="font-weight : bold;">{{ $user->lastname}}</td> 
                                                        <td><img style="height: 30px" src="{{ $bills->image}}" alt="">
                                                            <span style="display: none;">{{ $bills->payment_method}}</span>
                                                        </td>
                                                        
                                                        <td><?php echo date("d/m/Y à H:i", strtotime($bills->date_bill)); ?></td>
            
            
                                                        <td style="font-weight: bold; font-family:Arial, Helvetica, sans-serif">{{ number_format($bills->payment_total_amount, 2, ',', ' ') }}<i class="fa-solid fa-euro-sign"></i>
                                                        </td>
                                                        <td>
                                                            <img src="{{ $bills->image_status }}" alt="Caution acceptée">
                                                            <span style="display: none;">{{ $bills->status}}</span>
                                                        </td>
                                                        <td>   
                                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="DELETE">
                                                                <a data-toggle="modal" data-target="#deleteUserFacture{{ $bills->id }}" href="" type="button" class="btn  rounded-circle "><i style="color: red" class="fa-solid fa-trash"></i></a>
                                                            </span> 
                                                            @include('users.modals.deleteFacture')
                                                            @if ($bills->type == 'devis')
                                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Ajout Panier">
                                                                <a data-toggle="modal" data-target="" href="" type="button" class=""><i class="fa-solid fa-euro-sign"></i></a>
                                                            </span>
                                                            @endif
                                                        </td>
                                                    </tr>        
                                                @endforeach  
                                                
                                            </tbody>
                                        </table>
                                        
                                        <div id="noDataMessage" style="display: none; color:black;  margin: auto;
                                        text-align: center;padding: 10px;">Aucune donnée disponible</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
        </div>
        
        
    </div>
</div>

   



@endsection

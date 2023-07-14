@extends('layouts.template')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

.btn {
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
  font-size: 0.8rem; /* smaller font size */
  letter-spacing: 1px;
  display: inline-block;
  padding: 8px 16px; /* smaller padding */
  color: #fff;
  border: none;
  position: relative;
  cursor: pointer;
  overflow: hidden;
  transition: .3s ease background-color;
  margin-right: 10px;

}

.btn i {
  transition: .3s ease all;
}

.btn:hover i {
  transform: rotate(360deg);
}

.btn-red {
  background-color: #ff0000;
}

.btn-red:hover {
  background-color: #cc0000;
}

.btn-grey {
  background-color: #888;
}

.btn-grey:hover {
  background-color: #666;
}

.btn-green {
  background-color: #008000;
}

.btn-green:hover {
  background-color: #006600;
}


.btn:last-child {
  margin-right: 0;
}

</style>
<main id="main" class="main">
    @if(session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('stockUpdated'))
    <div class="alert alert-success">
        {{ session('stockUpdated') }}
    </div>
@endif
  
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Créer une facture</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="bill-form">
            <div class="form-group">
                <label for="user-search">Chercher un utilisateur</label>
                <input type="text" class="form-control" id="user-search-input">
                <select class="form-control user-select" id="user-select"></select>
            </div>
        
            <div class="form-group">
                <label for="payment-method">Méthode de paiement</label>
                <select class="form-control" id="payment-method">
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->payment_method }}</option>
                    @endforeach
                </select>
            </div>
        
            <input type="hidden"  id="user-id" name="user_id">
            <input type="hidden"  id="family-id" name="family_id">
        
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="save-button" class="btn btn-primary">Sauvegarder</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>



  <div class="pagetitle row justify-content-between align-items-center">
    <div class="col-md-8 row">
        <div class="col-md-4">
            <a href="{{ route('paiement.facture') }}"><h1>Liste des factures</h1></a>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Paiement</li>
                    <li class="breadcrumb-item active">Facture</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-8 d-flex justify-content-start pt-3">
            <button id="updateStock" class="btn btn-primary">
                <i class="fas fa-sync"></i> Mettre à jour le stock
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                <i class="fas fa-file-invoice"></i> Créer une facture
            </button>
        </div>
    </div>
    <div class="col-md-4 d-flex justify-content-between">
        <a href="{{ route('paiement.facture') }}" id="bills" class="btn btn-success">
            <i class="fas fa-file-invoice"></i> Factures
        </a>

        <button id="oldBills" class="btn btn-secondary">
            <i class="fas fa-history"></i>  factures
        </button>
        <button id="filterStatus" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> Corbeille
        </button>
        
    </div>
</div>

    
    <section class="section">    
        <div class="row">
            <div class="col-12 main-datatable">
                <div class="card_body">
                    <div class="row d-flex">
                        <!-- Button trigger modal -->

                          <!-- Modal -->
                          <!-- ---- modal OLD facture ---- -->
    <div style="--bs-modal-width: 1000px !important;" class="modal fade " id="oldBillsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content p-3">
              <!--Body-->
                <section class="section">
                    <div class="row">
                        <div class="col-12 main-datatable" style="padding-right: calc(var(--bs-gutter-x) * .0) ; padding-left: calc(var(--bs-gutter-x) * .0);">
                            <div class="card_body">
                                <div class="row d-flex">
                                    <!-- Button trigger modal -->
                                    <div class="col-12 add_flex justify-content-center mt-4">
                                        <div class="text-center pt-3 pb-2">
                                            <img style="width: 100px" src="{{ asset('assets\images\family.png') }}"
                                                alt="Check" width="60">
                                            <h2 class="my-4">Anciennes Factures</h2>
                                            </div>
                                    </div>
                                    <div  class="row modal-body overflow-x" id="oldBillsContainer">
                                        <!-- content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
           </div>
      </div>
    </div>
<!-- ---- modal famille facture ---- -->
<div style="--bs-modal-width: 55vw !important; height: 80vh !important; overflow-y: auto;" class="modal fade " id="factureFamille" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
          <!--Body-->
            <section class="section">
                <div class="row">
                    <div class="col-12 main-datatable" style="padding-right: calc(var(--bs-gutter-x) * .0) ; padding-left: calc(var(--bs-gutter-x) * .0);">
                        <div class="card_body">
                            <div class="row d-flex">
                                <!-- Button trigger modal -->
                                <div class="col-12 add_flex justify-content-center mt-4">
                                    <div class="text-center pt-3 pb-2">
                                        <img style="width: 100px" src="{{ asset('assets\images\family.png') }}"
                                            alt="Check" width="60">
                                        <h2 class="my-4">Factures Famille</h2>
                                        </div>
                                </div>
                                <div  class="row modal-body overflow-x" id="familyBillsContainer">
                                    <!-- content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
       </div>
  </div>
</div>
                         
                    </div>
                    <div class="overflow-x">
                        <table style="width:100%;" id="myTable"  class="table cust-datatable dataTable no-footer">
                            <thead>
                                <th style="min-width:50px;"> <a>ID</a></th>
                                <th style="min-width:150px;"><a>NOM Prénom</a></th>
                                <th style="min-width:150px;"><a >Type</a></th>
                                <th id="date" style="min-width:100px;"><a >Date</a></th>
                                <th style="min-width:100px;"><a >Total</a></th>
                                <th style="min-width:150px;"><a >Statut</a></th>
                                <th style="min-width:150px">Actions</th>
                            </thead>                            
                            <tbody>
                                @foreach ($bill as $bills )
                               
                                    <tr  style="background-color: {{ $bills->row_color}}"> 
                                        <td>
                                            @if (auth()->user()->roles->supprimer_edit_facture)
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Afficher Facture">
                                                <a type="button" target="_blank" class=" user-link a text-black "  href="{{ route('facture.showBill', ['id' => $bills->id]) }}">{{ intval($bills->id) }}</i></a>
                                              </span>
                                            @else
                                                {{ intval($bills->id) }}
                                            @endif
                                        </td>
                                        <td style="font-weight : bold;">{{ $bills->name}} {{ $bills->lastname}}</td> 
                                        <td><img style="height: 30px" src="{{ $bills->icon}}" alt="">
                                            <span style="display: none;">{{ $bills->payment_method}}</span>
                                        </td>
                                          
                                        <td><?php echo date("d/m/Y à H:i", strtotime($bills->date_bill)); ?></td>

                                        <td data-user-id="{{ $bills->user_id }}"  class="bill a" style="font-weight: bold; font-family:Arial, Helvetica, sans-serif">{{ number_format($bills->payment_total_amount, 2, ',', ' ') }} <i class="fa-solid fa-euro-sign"></i></td>
                                        
                                        <td>
                                            <img src="{{ $bills->image_status }}" alt="Caution acceptée">
                                            <span style="display: none;">{{ $bills->bill_status}}</span>
                                        </td>
                                        <td> 
                                            <!-- Bouton de modification -->
                                            @if (auth()->user()->roles->supprimer_edit_facture)
                                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Modifier">
                                                    <a  target="_blank" href="{{ route('facture.showBill', ['id' => $bills->id]) }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                </span>
                                            @endif
                                        
                                            
                                        
                                            <!-- Bouton des factures famille -->
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Factures famille">
                                                <button data-family-id="{{ $bills->family_id }}" type="button" class="btn btn-sm btn-primary familybill">
                                                    <i  class="fas fa-house"></i>
                                                </button>
                                            </span> 

                                            <!-- Bouton de suppression -->
                                            @if (auth()->user()->roles->supprimer_edit_facture)  
                                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Supprimer">
                                                    <button href="#" type="button" class="btn btn-sm btn-danger delete-bill"
                                                        onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')) {document.getElementById('delete-form-{{ $bills->id }}').submit();}">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                </span> 
                                                <form id="delete-form-{{ $bills->id }}" action="{{ route('bill.destroy', $bills->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </td>
                                        
                                        
                                    </tr>        
                                @endforeach  
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>




@endsection

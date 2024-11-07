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
            <h1>Liste des factures</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Paiement</li>
                    <li class="breadcrumb-item active">Facture</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-8 d-flex justify-content-start pt-3">
            <button id="updateStock" class="form-control d-none d-sm-block btn btn-primary">
                <i class="fas fa-sync"></i> Mettre à jour le stock
            </button>
            <button style="font-size: 11px" id="updateStock" class=" mb-1 form-control form-control-sm d-block d-sm-none btn btn-primary">
                <i class="fas fa-sync"></i> Mettre à jour le stock
            </button>
            <button type="button" class="form-control d-none d-sm-block btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                <i class="fas fa-file-invoice"></i> Créer une facture
            </button>
            <button style="font-size: 11px" type="button" class="mb-1 form-control form-control-sm d-block d-sm-none btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                <i class="fas fa-file-invoice"></i> Créer une facture
            </button>
        </div>
    </div>
    <div class="col-md-4 d-flex justify-content-between">
        <a href="{{ route('paiement.facture') }}" id="bills" class="form-control d-none d-sm-block btn btn-success">
            <i class="fas fa-file-invoice"></i> Factures
        </a>
        <a href="{{ route('paiement.facture') }}" style="font-size: 11px" id="bills" class="form-control form-control-sm d-block d-sm-none  btn btn-success">
            <i class="fas fa-file-invoice"></i> Factures
        </a>

        <button id="oldBills" class="form-control d-none d-sm-block btn btn-secondary">
            <i class="fas fa-history"></i>  factures
        </button>
        <button style="font-size: 11px" id="oldBills2" class="form-control form-control-sm d-block d-sm-none btn btn-secondary">
            <i class="fas fa-history"></i>  factures
        </button>
        <button id="filterStatus" class="form-control d-none d-sm-block btn btn-danger">
            <i class="fas fa-trash-alt"></i> Corbeille
        </button>
        <button style="font-size: 11px" id="filterStatus2" class="form-control form-control-sm d-block d-sm-none btn btn-danger">
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
                        
                        <div class="input-group mb-3">
                            <input type="text" id="customSearch" class="form-control" placeholder="Recherche..." aria-label="Recherche" aria-describedby="search-addon" autocomplete="off">
                            <button id="submitSearch" class="btn btn-primary mt-3" type="button">Rechercher</button>
                        </div>
                        
                        <table style="width:100%;" id="myTable"  class="table cust-datatable dataTable no-footer">
                            <thead>
                                <th style="min-width:40px;"> <a>ID</a></th>
                                <th style="min-width:120px;"><a>NOM Prénom</a></th>
                                <th style="min-width:70px;"><a >Total</a></th>
                                <th id="date" style="min-width:90px;"><a >Date</a></th>
                                <th style="min-width:80px;"><a >Statut</a></th>
                                <th style="min-width:70px;"><a >Type</a></th>
                                <th style="min-width:150px">Actions</th>
                            </thead> 
                            <tbody>
                            </tbody>                           
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>




@endsection

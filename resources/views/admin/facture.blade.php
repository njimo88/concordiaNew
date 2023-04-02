@extends('layouts.template')

@section('content')


<main id="main" class="main">
    @if(session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

   



    <div class="pagetitle">
       <h1>Liste des factures</h1>
       <nav>
          <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="index.html">Home</a></li>
             <li class="breadcrumb-item">Paiement</li>
             <li class="breadcrumb-item active">Facture</li>
          </ol>
       </nav>
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
                               
                                    <tr  style="background-color: {{ $bills->row_color}}"> 
                                        <td>
                                            @if (auth()->user()->roles->supprimer_edit_facture)
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Afficher Facture">
                                                <a type="button" class=" user-link a text-black "  href="{{ route('facture.showBill', ['id' => $bills->id]) }}">{{ intval($bills->id) }}</i></a>
                                              </span>
                                            @else
                                                {{ intval($bills->id) }}
                                            @endif
                                        </td>
                                        <td style="font-weight : bold;">{{ $bills->name}} {{ $bills->lastname}}</td> 
                                        <td><img style="height: 30px" src="{{ $bills->image}}" alt="">
                                            <span style="display: none;">{{ $bills->payment_method}}</span>
                                        </td>
                                          
                                        <td><?php echo date("d/m/Y à H:i", strtotime($bills->date_bill)); ?></td>

                                        <td data-user-id="{{ $bills->user_id }}"  class="bill a" style="font-weight: bold; font-family:Arial, Helvetica, sans-serif">{{ number_format($bills->payment_total_amount, 2, ',', ' ') }} <i class="fa-solid fa-euro-sign"></i></td>
                                        
                                        <td>
                                            <img src="{{ $bills->image_status }}" alt="Caution acceptée">
                                            <span style="display: none;">{{ $bills->bill_status}}</span>
                                        </td>
                                        <td> 
                                            @if (auth()->user()->roles->supprimer_edit_facture)  
                                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Supprimer">
                                                    <button href="#" type="button" class=" delete-bill "
                                                    onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')) {document.getElementById('delete-form').submit();}">
                                                        <i  class="fa-solid fa-trash"></i>
                                                    </button>
                                                </span> 
                                                <form id="delete-form" action="{{ route('bill.destroy', $bills->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif


                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Factures famile">
                                                <button  data-family-id="{{ $bills->family_id }}"  type="button" class="familybill"><i  class="fas fa-house"></i></button>
                                            </span> 
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

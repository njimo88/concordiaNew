
<main id="main" class="main">

    <!-- ---- modal Oldbils ---- -->
    <div  class="modal fade " id="oldBillsModal" tabindex="-1" role="dialog" aria-labelledby="oldBillsModalLabel" aria-hidden="true">
        <div style="max-width: 50vw;" class="modal-dialog modal-notify modal-info" role="document">
            <!--Content-->
            <div class="modal-content text-center">
              <!--Body-->
                <section class="section">
                    <div class="row">
                        <div class="col-12 main-datatable" style="padding-right: calc(var(--bs-gutter-x) * .0) ; padding-left: calc(var(--bs-gutter-x) * .0);">
                            <div class="card_body">
                                <div class="row d-flex">
                                    <!-- Button trigger modal -->
                                    <div class="col-12 add_flex justify-content-center mt-4">
                                        <div class="text-center pt-3 pb-2">
                                            <img style="width: 100px" src="{{ asset('assets\images\facturer.png') }}"
                                                alt="Check" width="60">
                                            <h2 class="my-4">Anciennes Factures</h2>
                                            </div>
                                    </div>
                                    <div  class="modal-body overflow-x" id="oldBillsContainer">
                                        <!-- content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
           </div>
        <!--/.Content-->
      </div>
    </div>



    <!-- ---- modal famille facture ---- -->
    <div  class="modal fade " id="factureFamille" tabindex="-1" role="dialog" aria-labelledby="oldBillsModalLabel" aria-hidden="true">
        <div style="max-width: 50vw;" class="modal-dialog modal-notify modal-info" role="document">
            <!--Content-->
            <div class="modal-content text-center">
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
                                    <div  class="modal-body overflow-x" id="familyBillsContainer">
                                        <!-- content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
           </div>
        <!--/.Content-->
      </div>
    </div>



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
                    <div class="overflow-x">
                        <table style="width:100%;" id="myTable"  class="table cust-datatable dataTable no-footer">
                            <thead>
                                <th style="min-width:50px;"> <a >ID Facture</a></th>
                                <th style="min-width:150px;"><a >Nom</a></th>
                                <th style="min-width:150px;"><a >Moyen de paiement</a></th>
                                <th id="date" style="min-width:100px;"><a >Date</a></th>
                                <th style="min-width:100px;"><a >Total</a></th>
                                <th style="min-width:150px;"><a >Statut</a></th>
                                <th style="min-width:150px">Actions</th>
                            </thead>                            
                            <tbody>
                                @foreach ($bill as $bill )
                               
                                    <tr  style="background-color: {{ $bill->row_color}}"> 
                                        <td>
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Afficher Facture">
                                                <a type="button" class=" user-link a text-black "  href="{{ route('facture.showBill', ['id' => $bill->id]) }}">{{ intval($bill->id) }}</i></a>
                                              </span>
                                        </td>
                                        <td style="font-weight : bold;">{{ $bill->name}} {{ $bill->lastname}}</td> 
                                        <td><img style="height: 30px" src="{{ $bill->image}}" alt="">
                                            <span style="display: none;">{{ $bill->payment_method}}</span>
                                        </td>
                                          
                                        <td><?php echo date("Y/m/d à H:i", strtotime($bill->date_bill)); ?></td>


                                        <td style="font-weight: bold; font-family:Arial, Helvetica, sans-serif">
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Anciennes factures">
                                                <a  data-user-id="{{ $bill->user_id }}"  type="button" class="bill user-link a text-black "  href="#">{{ $bill->payment_total_amount }}<i class="fa-solid fa-euro-sign"></i></a>
                                            </span>
                                            
                                        </td>
                                        <td>
                                            <img src="{{ $bill->image_status }}" alt="Caution acceptée">
                                            <span style="display: none;">{{ $bill->bill_status}}</span>
                                        </td>
                                        <td>   
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="DELETE">
                                                <a data-toggle="modal" data-target="#deleteFacture{{ $bill->id }}" href="" type="button" class="btn  rounded-circle border"><i style="color: red" class="fa-solid fa-trash"></i></a>
                                            </span> 
                                            @include('admin.modals.deleteFacture')
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Factures famile">
                                                <a  data-family-id="{{ $bill->family_id }}"  type="button" class="familybill btn  rounded-circle border"><i style="color: #47cead" class="fas fa-house"></i></a>
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
  
   


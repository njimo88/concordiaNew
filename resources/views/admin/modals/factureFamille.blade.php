
<style>
    .dataTables_wrapper .dataTables_filter {
float: left !important;
text-align: left !important;
}
</style>
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
                                        <h2 class="my-4">Factures Famille</h2>
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
<table style="max-width:1000px" id="myTableadminmember" class="border cust-datatable dataTable no-footer table">
    <thead>
        <tr>
            <th style="min-width:50px;">ID Facture</th>
            <th style="min-width:150px;">Nom </th>
            <th style="min-width:150px;">Moyen de paiement</th>
            <th style="min-width:100px;">Date</th>
            <th style="min-width:100px;">Total</th>
            <th style="min-width:150px;">Statut</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bill as $bills )
       
            <tr>
                
                <td>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Afficher Facture">
                        <a type="button" class=" user-link a text-black "  href="{{ route('facture.showBill',$bills->id) }}">{{ $bills->id  }}</i></a>
                    </span>
                </td>
                <td style="font-weight : bold;">{{ $bills->lastname}} {{ $bills->name}}</td>                                 
                <td><img style="height: 30px" src="{{ $bills->image}}" alt="">
                    <span style="display: none;">{{ $bills->payment_method}}</span>
                </td>
                  
                <td><?php echo date("d/m/Y à H:i", strtotime($bills->date_bill)); ?></td>

                <td style="font-weight: bold; font-family:Arial, Helvetica, sans-serif">
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Anciennes factures">
                        <a  data-user-id="{{ $bills->user_id }}"  type="button" class="bill user-link a text-black "  href="#">{{ number_format($bills->payment_total_amount, 2, ',', ' ') }} €</a>
                    </span>
                    
                </td>
                <td>
                    <img src="{{ $bills->image_status }}" alt="Caution acceptée">
                    <span style="display: none;">{{ $bills->status}}</span>
                </td>
            </tr>        
        @endforeach  
        
    </tbody>
</table>
<div id="noDataMessage" style="display: none; color:black;  margin: auto;
    text-align: center;padding: 10px;">Aucune donnée disponible</div>
</div>


<style>
    .dataTables_wrapper .dataTables_filter {
float: left !important;
text-align: left !important;
}
</style>

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
<script>
    $('#myTableadminmember').on('click', '.bill', function(){
  

  $('#oldBillsModal').modal('show');

  // Get the bill ID from the clicked element
  var user_id = $(this).data('user-id');
console.log(user_id);
   // Make an AJAX request to retrieve the old bills
   $.ajax({
      
   url: '/admin/paiement/facture/get-old-bills/' + user_id,
   success: function(data) {
       console.log(data);
      $('#oldBillsContainer').html(data);
   }
   });
 });
</script>
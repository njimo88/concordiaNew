
<style>
    .dataTables_wrapper .dataTables_filter {
float: left !important;
text-align: left !important;
}
</style>

<table class="table table-bordered  table-striped table-hover" id="myTableadminmember" style="max-width:1000px; table-layout: fixed;">
    <thead>
        <tr>
            <th style="min-width:50px;">ID Facture</th>
            <th style="min-width:150px;">Nom</th>
            <th style="min-width:150px;">Moyen de paiement</th>
            <th style="min-width:100px;">Date</th>
            <th style="min-width:100px;">Total</th>
            <th style="min-width:150px;">Statut</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bill as $bills )
            <tr >
                <td>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Afficher Facture">
                        <a type="button" class=" user-link a text-black" href="{{ route('facture.showBill',$bills->id) }}">{{ $bills->id }}</a>
                    </span>
                </td>
                <td style="font-weight : bold; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{ $bills->lastname}} {{ $bills->name}}</td>                                 
                <td><img style="height: 30px" src="{{ $bills->image }}" alt="">
                    <span style="display: none;">{{ $bills->payment_method }}</span>
                </td>
                <td><?php echo date("d/m/Y à H:i", strtotime($bills->date_bill)); ?></td>
                <td data-user-id="{{ $bills->user_id }}" class="bill a" style="font-weight: bold; font-family:Arial, Helvetica, sans-serif">{{ number_format($bills->payment_total_amount, 2, ',', ' ') }} <i class="fa-solid fa-euro-sign"></i></td>
                <td>
                    <img src="{{ $bills->image_status }}" alt="Caution acceptée">
                    <span style="display: none;">{{ $bills->status }}</span>
                </td>
            </tr>
            @foreach($bills->liaisons as $liaison)
                <tr>
                    <td colspan="6">
                        <table class="table table-bordered table-striped table-hover table-sm" style="table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Designation</th>
                                    <th style="width: 15%;">Quantity</th>
                                    <th style="width: 20%;">Price</th>
                                    <th style="width: 25%;">Pour</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $liaison->designation }}</td>
                                    <td>{{ $liaison->quantity }}</td>
                                    <td>{{ number_format($liaison->ttc, 2, ',', ' ') }} <i class="fa-solid fa-euro-sign"></i></td>
                                    <td>{{ $liaison->liaison_user_lastname }} {{ $liaison->liaison_user_name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
            <tr style="background-color: #f5f5f5">
                <td colspan="6" style="border-bottom: 3px solid #333;"></td>
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
<style>
    .invoice {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f9f9f9; 
    }

    .invoice-header {
        font-weight: bold;
        background-color: #f2f2f2; 
        
    }

    .invoice-items {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr; 
        gap: 5px;
        margin-left: 20px;
    }

    .invoice-item {
        display: contents; 
    }

    .invoice-item:nth-child(odd) {
        background-color: #f1f1f1; 
    }

    .invoice-item:nth-child(even) {
        background-color: #ffffff; 
    }
</style>

@foreach ($bill as $bills)
    <div class="invoice">
        <div style="background-color: {{ $bills->row_color == 'none' ? '#00ff00' : $bills->row_color }} !important;" class="invoice-header p-2">Facture n°: <a target="_blank" href="{{ route('facture.showBill',$bills->id) }}">{{ $bills->id }}</a> </div>
        <div class="invoice-items">
            @foreach($bills->liaisons as $liaison)
                <div class="invoice-item">
                    <span>{{ $liaison->designation }}</span>
                    <span>{{ $liaison->quantity }} x {{ number_format($liaison->ttc, 2, ',', ' ') }} <i class="fa-solid fa-euro-sign"></i></span>
                    <span>{{ $liaison->liaison_user_lastname }} {{ $liaison->liaison_user_name }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endforeach


                   


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
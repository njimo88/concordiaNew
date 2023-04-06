<table  class="table cust-datatable dataTable no-footer">
  @if (session('success'))
                                <div class="alert alert-success col-11">
                                    {{ session('success') }}
                                </div>
                          @endif
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
      @foreach ($oldBills as $oldBills )
     
          <tr>
              
              <td>
                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" title="Afficher Facture">
                  <a type="button" class=" user-link a text-black "  href="{{ route('facture.showBill',$oldBills->id) }}">{{ $oldBills->id }}</i></a>
               </span>
              </td>
              <td style="font-weight : bold;">{{ $oldBills->lastname}} {{ $oldBills->name}}</td>                                 
              <td><img style="height: 30px" src="{{ $oldBills->image}}" alt="">
                <span style="display: none;">{{ $oldBills->payment_method}}</span>
            </td>
                
              <td>{{ date('d/m/Y', strtotime($oldBills->date_bill)) }}</td>

              <td style="font-weight: bold; font-family:Arial, Helvetica, sans-serif">
                <a  data-user-id="{{ $oldBills->user_id }}"  type="button" class="bill user-link a text-black "  href="#">{{ number_format($oldBills->payment_total_amount, 2) }}<i class="fa-solid fa-euro-sign">  </i></a>
              </td>
              <td>
                <img src="{{ $oldBills->image_status }}" alt="Caution acceptée">
                <span style="display: none;">{{ $oldBills->bill_status}}</span>
            </td>     
          </tr>        
      @endforeach  
      
  </tbody>
</table>
                           
   


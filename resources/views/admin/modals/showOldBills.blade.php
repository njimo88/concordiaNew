<table style="width:100%;" class="table cust-datatable dataTable no-footer">
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
                  <a type="button" class=" user-link a text-black "  href="#">{{ $oldBills->id }}</i></a>
               </span>
              </td>
              <td style="font-weight : bold;">{{ $oldBills->lastname}} {{ $oldBills->name}}</td>                                 
              <td>
                  @if ($oldBills->payment_method === 'Chèques')
                    <img style="width:50px; height:50px;" src="{{ asset('assets/images/cheque.png') }}" alt="{{ $oldBills->payment_method }}">
                  @elseif ($oldBills->payment_method === 'Carte bancaire')
                    <img style="width:50px; height:50px;" src="{{ asset('assets/images/carte-bancaire.png') }}" alt="{{ $oldBills->payment_method }}">
                  @elseif ($oldBills->payment_method === 'Espèces')
                    <img style="width:50px; height:50px;" src="{{ asset('assets/images/especes.png') }}" alt="{{ $oldBills->payment_method }}">
                  @elseif ($oldBills->payment_method === 'Virements')
                    <img style="width:50px; height:50px;" src="{{ asset('assets/images/virement.png') }}" alt="{{ $oldBills->payment_method }}">
                  @elseif ($oldBills->payment_method === 'Bons')
                    <img style="width:50px; height:50px;" src="{{ asset('assets/images/bons.png') }}" alt="{{ $oldBills->payment_method }}">
                  @elseif ($oldBills->payment_method === 'Prélèvements')
                      <img style="width:50px; height:50px;" src="{{ asset('assets/images/prelevement.png') }}" alt="{{ $oldBills->payment_method }}">
                  @else
                      {{ $oldBills->payment_method }}
                  @endif
              </td>
                
              <td>{{ date('d/m/Y', strtotime($oldBills->date_bill)) }}</td>

              <td style="font-weight: bold; font-family:Arial, Helvetica, sans-serif">
                {{ number_format($oldBills->payment_total_amount, 2) }}<i class="fa-solid fa-euro-sign">     
              </td>
              <td>
                @switch($oldBills->status)
                    @case("Caution acceptée")
                        <img src="chemin/vers/image1.jpg" alt="Caution acceptée">
                        @break
                    @case("Commande suspendue")
                        <img src="chemin/vers/image2.jpg" alt="Commande suspendue">
                        @break
                    @case("Paiement accepté")
                        <img style="width:40px; height:40px;" src="{{ asset('assets/images/accepter.png') }}" alt="Paiement accepté">
                        @break
                    @case("Paiement partiel")
                        <img src="chemin/vers/image4.jpg" alt="Paiement partiel">
                        @break
                    @default
                        <span class="mode mode_on">{{ $oldBills->status}}
                @endswitch
              </td>      
          </tr>        
      @endforeach  
      
  </tbody>
</table>
                           
   


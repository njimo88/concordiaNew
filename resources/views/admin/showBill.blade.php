@extends('layouts.app')

@section('content')
<main class="main" id="main"  style="background-image: url('{{asset("/assets/images/background.png")}}'); min-height:100vh;">

<!-- Modal -->
<div class="modal fade" id="additionalChargeModal" tabindex="-1" aria-labelledby="additionalChargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="additionalChargeModalLabel">Ajout de frais supplémentaires</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('additional-charge.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="bill_id">ID Facture:</label>
            <input type="text" name="bill_id" id="bill_id" required class="form-control" value="{{ $bill->id }}" readonly>
          </div>

          <div class="form-group">
            <label for="amount">Montant:</label>
            <input type="text" name="amount" id="amount" required class="form-control">
          </div>

          <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div style="background-color: white;" class="container  justify-content-center">
    <div class="row ">
        @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
        @endif
        @if (session('error'))
            <div style="    display: -webkit-inline-box;" class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="row "> 

          <div class="col-md-3 col-sm-12 text-center"> <!-- Facture Column -->
              <h3 class="my-4 ml-0" style="color: black">Facture n°{{ $bill->id }}</h3>
          </div>
      
          @if ($bill->status >= 70)
          <div class="col-md-9 col-sm-12 text-md-right text-center"> <!-- Buttons Column -->
              <div class="row justify-content-end">
                  
                  <div class="col-md-3 col-sm-3 d-flex justify-content-end">
                      <form method="post" action="{{ route('generatePDFfacture', $bill->id) }}" class="mx-md-0 mx-auto">
                          @csrf
                          <button type="submit" class="custom-btn btn btn-primary">
                              Fac <img src="{{ asset('assets/images/pdf-icon.png') }}" alt="PDF Icon" class="ml-2 icon-size">
                          </button>
                      </form>
                  </div>
      
                  <!-- Réduction Fiscale Button -->
                  <div class="col-md-2 col-sm-4 d-flex justify-content-star">
                      <form method="post" action="{{ route('generatePDFreduction_Fiscale', $bill->id) }}" class="mx-md-0 mx-auto">
                          @csrf
                          <button type="submit" class="custom-btn btn btn-primary">
                              Red Fisc <img src="{{ asset('assets/images/pdf-icon.png') }}" alt="PDF Icon" class="ml-2 icon-size">
                          </button>
                      </form>
                  </div>
      
              </div>
          </div>
          @endif
      
      </div>
      <hr>
      
           <style>
            .custom-btn {
    font-size: 14px;     /* Reduce the font size */
    padding: 8px 15px;   /* Adjust button padding */
    border-radius: 5px;  /* Add a bit of border-radius */
    width: 100%;         /* Full width to fill the parent container */
    margin: 20px 0;      /* Top and bottom margin */
}

.icon-size {
    max-width: 16px;
    vertical-align: middle; /* Align icon with the text */
}

           </style>
        
        @if (auth()->user()->roles->changer_status_facture && Route::currentRouteName() === 'facture.showBill')
        
          <form action="{{ route('facture.updateStatus', $bill->id) }}" method="post">
              @csrf
              @method('PUT')
          @else
              <form>
          @endif
              <div style="background-color: @if ( $bill->row_color == 'none' ) #00ff00 @else {{ $bill->row_color }} @endif" class="mb-3 row d-flex justify-content-between">
                  <div class="col-md-5 p-4 col-12">
                    @if(!auth()->user()->roles->changer_status_facture || Route::currentRouteName() !== 'facture.showBill')
                    <select disabled class="border col-md-12 form-select @error('role') is-invalid @enderror" name="status" id="status" autocomplete="status" autofocus role="listbox">
                      @foreach($status as $s)
                          @if($s->id == $bill->status)
                              <option value="{{ $s->id }}" selected role="option">{{ $s->status }}</option>
                          @endif
                      @endforeach
                  </select>
                  
                    @else
                      <select  class="border col-md-12 form-select @error('role') is-invalid @enderror" name="status" id="status" autocomplete="status" autofocus role="listbox" >
                          @foreach($status as $status)
                              <option value="{{ $status->id }}" {{ $bill->status == $status->id ? 'selected' : '' }} role="option">{{ $status->status }}</option>
                          @endforeach
                      </select>
                    @endif
                  </div> 
                  @if (auth()->user()->roles->changer_status_facture && Route::currentRouteName() === 'facture.showBill')
                  <div class="col-md-2 p-4 col-10 d-flex justify-content-center ">
                      <button type="submit" class="btn btn-dark">Enregistrer</button>
                  </div>
                  @endif
              </div>
          </form>

          
          <hr>
        </div>
    <div class="row border border-dark m-1 mb-4 rounded">
      <div class="col-md-4 col-12 p-3">
        <h4>Informations Membre</h4>
        <span style="font-weight:bold">{{ $bill->lastname }} {{ $bill->name }} (ID n°{{ $bill->user_id }}) </span> <br>
        <a style="color:rgb(4, 0, 255)" class="a" href="mailto:{{ $bill->email }}">{{ $bill->email }} <br>
        <a style="color:rgb(4, 0, 255)" class="a" href="tel:{{ $bill->phone }}">{{ $bill->phone }}</a><br>
        {{ \Carbon\Carbon::parse($bill->birthdate)->age }} ans
        <h5 class="mt-2">Adresse de facturation</h5>
        {{ $bill->lastname }} {{ $bill->name }}
            <br>
            {{ $bill->address }} <br> {{ $bill->zip }} {{ $bill->city }} <br>{{ $bill->country }}
      </div>
      <div class="col-md-4 col-12 p-3">
        <h4>Détail de la commande</h4>
        <span style="font-weight:bold">Numéro de commande</span> : {{ $bill->id }} <br>
        <?php
          setlocale(LC_ALL, 'fr_FR.UTF-8');
          
          // Define an array of English to French translations for month and day names
          $englishToFrench = [
              'January' => 'janvier',
              'February' => 'février',
              'March' => 'mars',
              'April' => 'avril',
              'May' => 'mai',
              'June' => 'juin',
              'July' => 'juillet',
              'August' => 'août',
              'September' => 'septembre',
              'October' => 'octobre',
              'November' => 'novembre',
              'December' => 'décembre',
              'Monday' => 'Lundi',
              'Tuesday' => 'Mardi',
              'Wednesday' => 'Mercredi',
              'Thursday' => 'Jeudi',
              'Friday' => 'Vendredi',
              'Saturday' => 'Samedi',
              'Sunday' => 'Dimanche',
          ];
          
          // Use the strtr function to replace the English month and day names with their French equivalents
          $formattedDate = strtr(\Carbon\Carbon::parse($bill->date_bill)->isoFormat('dddd D MMMM YYYY à HH:mm:ss'), $englishToFrench);
          
          // Output the formatted date
          echo '<span style="font-weight:bold">Date</span> '.$formattedDate;
          ?>
        <br>
        <span style="font-weight:bold">Référence commande </span>: {{ $bill->ref }} <br>
    
    <span style="font-weight:bold">Mode de paiement </span>:
    <select name="payment_method" id="payment_method">
        @foreach($paymentMethods as $method)
            <option value="{{ $method->id }}" {{ $bill->payment_method == $method->id ? 'selected' : '' }}>
                {{ $method->payment_method }}
            </option>
        @endforeach
    </select>
    <br>
    
    Paiement en {{ $bill->number }} fois <br>
    
    <span style="font-weight:bold">Frais </span>: {{ number_format($bill->total_charges, 2, ',', ' ') }} €<br>
    
    <div class="form-group">
      <label for="payment_total_amount"><strong style="color: black">Coût TTC:</strong></label>
      <input style="width: 30%" type="text" id="payment_total_amount" class="form-control" name="payment_total_amount" value="{{ number_format($bill->payment_total_amount, 2, ',', ' ') }} €">
    </div>
    <br>
</div>
      <div class="col-md-4 col-12 p-3">
        <h4>Détail paiement</h4>
        <fieldset class="large-8 left">
          <?php
          setlocale(LC_ALL, 'fr_FR.UTF-8');
          date_default_timezone_set('Europe/Paris');

          $englishToFrench = [
              'January' => 'janvier',
              'February' => 'février',
              'March' => 'mars',
              'April' => 'avril',
              'May' => 'mai',
              'June' => 'juin',
              'July' => 'juillet',
              'August' => 'août',
              'September' => 'septembre',
              'October' => 'octobre',
              'November' => 'novembre',
              'December' => 'décembre',
          ];

          $billDate = \Carbon\Carbon::parse($bill->date_bill);
          $datetime = new DateTime($billDate->format('Y-m-d'));
          $formattedMonthYear = $datetime->format('F Y');
          $i = 1;

          foreach ($nb_paiment as $paiment) {
    $formattedMonthYear = $englishToFrench[$datetime->format('F')] . ' ' . $datetime->format('Y');
    echo '<b>Paiement ' . $i . '</b>: &nbsp;' . number_format($paiment, 2, ',', ' ') . ' €&nbsp;&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp; <b>Echéance : &nbsp;</b>' . $formattedMonthYear . '<br>';
              
    $datetime->add(new DateInterval('P1M')); // add one month to the date
    $i++;
}

          ?>
      </fieldset>
      <br><br>
        <span style="font-weight:bold">
          Reste à payer :
           @if ($bill->status == 100)
            <span class="text-success">Facture payée</span>
          @else
            {{ number_format($bill->payment_total_amount-$bill->amount_paid, 2, ',', ' ') }} €</span>
            @endif 
        <br><br><br>
        @if (auth()->user()->roles->paiement_immediat && Route::currentRouteName() === 'facture.showBill')
          <a href="{{ route("paiement_immediat",$bill->id ) }}" class="my-custom-btn btn btn-primary my-4 p-2">Paiement Immédiat <img  style="width: 30px" src="{{ asset('assets/images/fds.png') }}" alt=""></a>
        @endif
        <!-- Button trigger modal -->
      <button type="button" class="btn-danger" data-bs-toggle="modal" data-bs-target="#additionalChargeModal">
        paiement CB <img  style="width: 30px" src="{{ asset('assets/images/fds.png') }}" alt="">
      </button>
      </div>
      
    </div>

    <div class="row border border-dark mx-2">
      <div class="col-12 d-flex justify-content-center border-bottom -border-dark bg-secondary">
        <h3 class="my-2">Détail Facture</h3> <hr>
      </div>
    </div>
    @if (count($shop) > 0)
    <style>
      @media (max-width: 768px) { /* Apply styles for screens smaller than 768px */
        table.table {
          font-size: 0.8em; /* Reduces font size */
        }
      }
    </style>
    
    <style>
      
.td img {
  height: 70px
}      .td {
          display: flex;
          gap: 33px;
          align-items: center;
      }

      .td select{
          width: 80%
      }
  
      @media (max-width: 767px) {
         .btn-primary{
        margin: 15px auto;
      }
          .td {
              flex-direction: column;
              gap: 10px;
          }


          tr{
            display: grid;
            justify-items: center;
          }        
  
          .td select, .td button {
             width: 100%;
          }
           .td img {
            height: 150px !important  ;
          }

          .td select {
              width: 200% !important;
              font-size: 14px;  
              align-self: center;
          }
  
          .td button {
              font-size: 14px;  
              padding: 4px 8px; 
          }
          
      }

  </style>

<table class="table">
    <thead>
        <tr>
            <th><a>Désignation</a></th>
            <th class="d-none d-md-table-cell"><a>Quantité</a></th>
            <th class="d-none d-md-table-cell"><a>Prix</a></th>
            <th class="d-none d-md-table-cell"><a>Sous-total</a></th>
            <th><a></a></th>
            <th><a></a></th>
        </tr>
    </thead>
    <tbody>
      @foreach ($shop as $shop)
    @if ($shop->article_id == -1)
        <tr class="responsive-table-row">
            <td colspan="2" >&nbsp;&nbsp;&nbsp;&nbsp; Réduction(s)</td>
            <td></td>
            <td>{{ number_format($shop->sub_total, 2, ',', ' ') }} €</td>
            <td></td>
            <td></td>
        </tr>
    @else
        <tr class="responsive-table-row">
            <td style="width: 45%;">
              <div class="td">
                  <img style="height: 70px" src="{{ $shop->image }}" alt="">
                  <input type="hidden" name="user_id" value="{{ $bill->user_id }}">
                  
                  @php
                      $isCurrentSeason = $shop->saison == $saisonActive;
                  @endphp

                  @if(!$isCurrentSeason)
                      <input type="text" class="form-control mt-3" value="{{ $shop->designation }}" disabled>
                  @else
                      @if(!auth()->user()->roles->changer_designation_facture)
                          <select disabled name="designation" class="border form-select mt-3 designation-select" id="status" autocomplete="status" autofocus role="listbox" data-liaison-id="{{ $shop->id_liaison }}">
                              @foreach($designation as $title)
                                  @if($title == $shop->designation)
                                      <option value="{{ $title }}" role="option" selected>{{ $title }}@if(!empty($shop->declinaison_libelle)) [{{ $shop->declinaison_libelle }}] @endif</option>
                                  @endif
                              @endforeach
                          </select>
                      @else
                          <select name="designation" class="border form-select mt-3 designation-select" id="status" autocomplete="status" autofocus role="listbox" data-liaison-id="{{ $shop->id_liaison }}">
                              @foreach($designation as $article)
                                  <option value="{{ $article->id_shop_article }}" data-declinaison-id="{{ optional($article->declinaisons->first())->id }}" role="option" @if($article->id_shop_article == $shop->id_shop_article) selected @endif>{{ $article->title }}@if(!empty($article->declinaisons->first()->libelle)) [{{ $article->declinaisons->first()->libelle }}] @endif</option>
                              @endforeach
                          </select>
                      @endif

                      @if(auth()->user()->roles->changer_designation_facture)
                          <input type="hidden" id="declinaison_id" name="declinaison_id" value="{{ optional($article->declinaisons->first())->id }}">
                          <button type="button" class="btn btn-sm btn-warning mt-3 change-designation-button" data-liaison-id="{{ $shop->id_liaison }}">Changer</button>
                      @endif
                  @endif
              </div>
          </td>

            <td class="d-none d-md-table-cell">{{ $shop->quantity }}</td>
            <td class="d-none d-md-table-cell">{{ number_format($shop->ttc, 2, ',', ' ') }} €</td>
            <td class="d-none d-md-table-cell">{{ number_format($shop->sub_total, 2, ',', ' ') }} €</td>
            <td>
                <select name="addressee" class="border form-select mt-3 family-select" onchange="confirmChange(this)" data-liaison-id="{{ $shop->id_liaison }}">
                    <option value="{{ $shop->id_user }}" selected>{{ $shop->addressee }}</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger delete-des my-4 cc" data-id="{{ $shop->id_liaison }}">Supprimer</button>
            </td>
        </tr>
    @endif
@endforeach

  </tbody>
  
</table>

  
  
  @else
    <div class="row ">
        <div class="col-12 d-flex justify-content-center ">
            <h4 class="mt-4 ">Aucune information disponible.</h4>
        </div>
    </div>
@endif
@if (auth()->user()->roles->changer_designation_facture && Route::currentRouteName() === 'facture.showBill')
<div class="d-flex justify-content-end m-3">
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AjouteProduit">
    <i class="fa-solid fa-plus"></i> &nbsp; Ajouter un produit
</div>
@endif
<div class="modal fade" id="AjouteProduit" tabindex="-1" aria-labelledby="AjouteProduitLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="AjouteProduitLabel">Sélectionner un produit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="product-select" class="form-label">Produits</label>
            <select class="form-select" id="product-select">
              <!-- Les options seront remplis avec du code Javascript -->
            </select>
          </div>
          <div class="mb-3">
            <label for="product-quantity" class="form-label">Quantité</label>
            <input type="number" class="form-control" id="product-quantity" min="1" value="1">
          </div>
          
          <div class="mb-3">
            <label for="family-select" class="form-label">Membre de la famille</label>
            <select class="form-select" id="family-select">
            </select>
        </div>
        
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="recalculate-bill">
            <label class="form-check-label" for="recalculate-bill">
              Recalculer la facture
            </label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary">Sauvegarder</button>
      </div>
    </div>
  </div>
</div>
   
  
  <div class="row border border-dark my-5 mx-2">
    <div class="col-12 d-flex justify-content-center border-bottom -border-dark bg-secondary">
      <h3 class="my-2">Historique des modification :</h3> <hr>
    </div>


    <div class="col-lg-12 mt-3">
      @foreach($messages as $message)
        <?php
        // Configure la locale en français
        setlocale(LC_ALL, 'fr_FR.UTF-8');
      
        // Tableau de traduction des mois et jours de la semaine
        $englishToFrench = [
            'January' => 'janvier',
            'February' => 'février',
            'March' => 'mars',
            'April' => 'avril',
            'May' => 'mai',
            'June' => 'juin',
            'July' => 'juillet',
            'August' => 'août',
            'September' => 'septembre',
            'October' => 'octobre',
            'November' => 'novembre',
            'December' => 'décembre',
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche',
        ];
      
        $formattedDate = \Carbon\Carbon::parse($message->date)->isoFormat('dddd D MMMM YYYY à HH:mm:ss ');
      
        $formattedDate = strtr($formattedDate, $englishToFrench);
        ?>
        <div class="row" id="message-{{ $message->id_shop_message }}">
          @if ($message->state == 'Public' || Route::currentRouteName() === 'facture.showBill')
            <div>
              @if ($message->state == 'Privé')
                <u><b><span style="color:red;">(Privé)</span></b> {{ $message->lastname }} {{ $message->name }} <time datetime="{{ $message->date }}">{{ $formattedDate }}</time></u><br>
              @else
                <u>{{ $message->lastname }} {{ $message->name }} <time datetime="{{ $message->date }}">{{ $formattedDate }}</time></u><br>
              @endif
        
              @if ($message->somme_payé <= 0 && $message->somme_payé != null)
                <b>Somme payée : </b><span style="font-weight : bold" class="text-danger">{{ $message->somme_payé*-1 }} €</span><br>
              @elseif ($message->somme_payé > 0)
                <b>Somme remboursée : </b><span style="font-weight : bold" class="text-success">{{ $message->somme_payé }} €</span><br>
              @endif
            </div>
        <div class="col-11 my-2">
          {!! html_entity_decode(nl2br(e($message->message))) !!}
        </div>
        
    <div class="col-1 my-2 d-flex justify-content-end">
      <button style="display: flex ; align-self:flex-end"class="btn  btn-outline-danger delete-button" data-id="{{ $message->id_shop_message  }}">
        <i class="fas fa-times"></i>
      </button>
    </div>
          
          
          <hr>
        @endif
        </div>
      @endforeach
    </div>
    
    @if (Route::currentRouteName() === 'facture.showBill')


  <div class="col-lg-12">
    <form action="{{ route('addShopMessage', ['id' => $bill->id]) }}" method="POST">
      @csrf
      <div class="content">
        <select class="form-control" name="comment_visibility">
          <option value="Privé">Privé</option>
          <option value="Public">Public</option>
        </select>
        <br>
        <textarea class="form-control" style="height: 200px" name="comment_content" placeholder="Contenu du commentaire"></textarea>
        <input type="number" class="form-control" name="somme_payé" placeholder="Somme payée">
        <input type="hidden" name="id_admin" value="{{ auth()->user()->user_id }}">
        <input type="submit" class="btn btn-primary" value="Envoyer">
      </div>
    </form>
  </div>
  
  @endif
  
</div>


<div style="height: 25px"></div>
</main>
<script>
$(document).ready(function() {
    $('.change-designation-button').click(function() {
        var liaisonId = $(this).data('liaison-id');
        var selectedValue = $(this).closest('tr').find('.designation-select').val();
        var declinaisonId = $(this).closest('tr').find('.designation-select option:selected').data('declinaison-id');
        var user_id = {{ $bill->user_id }};
        $.ajax({
            url: '/admin/paiement/facture/updateDes/' + liaisonId,
            method: 'PUT',
            data: {
                designation: selectedValue,
                declinaison_id: declinaisonId,
                user_id: user_id,
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(error) {
                console.error(error);
            }
        });
    });
});


document.getElementById("payment_method").addEventListener("change", function() {
    updateBillDetails({
        payment_method: this.value,
        bill_id: {{ $bill->id }}
    });
});

// Handle Coût TTC input change
document.getElementById("payment_total_amount").addEventListener("blur", function() {
    updateBillDetails({
        payment_total_amount: this.value.replace('€', '').trim(),
        bill_id: {{ $bill->id }}
    });
});

function updateBillDetails(data) {
    fetch('/update-bill-details', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Détails mis à jour avec succès!');
        } else {
            alert('Erreur lors de la mise à jour des détails.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour des détails.');
    });
}

 $(document).ready(function() {
  $('.delete-button').click(function() {
    console.log('delete');
    var messageId = $(this).data('id');
    if (confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
      $.ajax({
        url: '/admin/supprimer-message/' + messageId,
        method: 'DELETE',
        data: {
          "_token": "{{ csrf_token() }}"
        },
        success: function(response) {
            alert('Le message a été supprimé avec succès.');
            $('#message-' + messageId).hide(); 
                location.reload();
        }
      });
    }
  });
});

var select = $('#product-select');

$.ajax({
  url: '/products/current-season',
  type: 'GET',
  dataType: 'json',
  success: function(products) {
      products.forEach(function(product) {
          var option = $('<option></option>').val(product.id_shop_article).text(product.title);
          select.append(option);
      });
  },
  error: function(error) {
      console.log('Error:', error);
  }
});
var bill = @json($bill);

$(document).ready(function() {
  var familySelect = $('#family-select');
  var familyId = bill.family_id;

  $.ajax({
      url: '/family-members/' + familyId,
      type: 'GET',
      dataType: 'json',
      success: function(familyMembers) {
          familyMembers.forEach(function(member) {
              var option = $('<option></option>').val(member.user_id).text(member.lastname + ' ' + member.name);
              familySelect.append(option);
          });
      },
      error: function(error) {
          console.log('Error:', error);
      }
  });

  var productSelect = $('#product-select');

  $.ajax({
      url: '/products/current-season',
      type: 'GET',
      dataType: 'json',
      success: function(products) {
          products.forEach(function(product) {
              var option = $('<option></option>').val(product.id_shop_article).text(product.title);
              productSelect.append(option);
          });
      },
      error: function(error) {
          console.log('Error:', error);
      }
  });

  $(document).ready(function() {
    var familySelect = $('.family-select');
    var familyId = bill.family_id;

    $.ajax({
        url: '/family-members/' + familyId,
        type: 'GET',
        dataType: 'json',
        success: function(familyMembers) {
            familyMembers.forEach(function(member) {
                var option = $('<option></option>').val(member.user_id).text(member.lastname + ' ' + member.name);
                familySelect.append(option);
            });
        },
        error: function(error) {
            console.log('Error:', error);
        }
    });

});

  var saveButton = document.querySelector('#AjouteProduit .btn-primary');
  var form = document.querySelector('#AjouteProduit form');
  saveButton.addEventListener('click', function(event) {
      event.preventDefault();

      var bill_id = bill.id;
      var id_shop_article = document.querySelector('#product-select').value;
      var quantity = document.querySelector('#product-quantity').value;
      var recalculate = document.querySelector('#recalculate-bill').checked;
      var familyMemberId = document.querySelector('#family-select').value; 

      var data = {
          bill_id: bill_id,
          id_shop_article: id_shop_article,
          quantity: quantity,
          recalculate: recalculate,
          family_member_id: familyMemberId // Envoyer l'id du membre de la famille avec les données du formulaire
      };

      fetch('/save-selection', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(data)
      })
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok');
          }
          return response.json();
      })
      .then(data => {
          alert(data.message);
          location.reload();
      })
      .catch(error => {
          alert('Error: ' + error);
      });
  });
});


function confirmChange(select) {
        var newAddressee = select.value;
        var confirmation = confirm("Êtes-vous sûr de vouloir changer le propriétaire de l'article ?");

        if (confirmation) {
            // Envoyez une requête AJAX pour mettre à jour l'adresse de livraison
            var liaisonId = select.getAttribute('data-liaison-id');
            var url = '/update-addressee/' + liaisonId;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({ addressee: newAddressee })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => console.error(error));
        } else {
            select.value = select.getAttribute('data-previous-value');
        }
    }

    $(document).ready(function() {
    $('.delete-des').click(function() {
        var liaisonId = $(this).data('id');
        if (confirm('Êtes-vous sûr de vouloir supprimer cette désignation ?')) {
            $.ajax({
                url: '/delete-designation/' + liaisonId,
                method: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert('La désignation a été supprimée avec succès.');
                    $('#liaison-' + liaisonId).remove();
          location.reload();


                }
            });
        }
    });

});

</script>
  
@endsection


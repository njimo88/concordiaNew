@extends('layouts.app')

@section('content')
<style>
.left-section {
    background-color: #f0f0f0;
}

.right-section {
    background-color: #ffffff;
}

.item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #ddd;
    padding: 10px 0;
}

.item .price {
    color: #333;
    font-weight: bold;
}

.total {
    margin-top: 20px;
    font-size: 1.5em;
    text-align: end;
}

.payment-section {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.payment-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}






body, html {
    background-color: #ebf5ff;
}

.container {
    font-family: 'Arial', sans-serif;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    background: #fff;
    margin-top: 5rem !important;
}

.content-wrapper {
    display: flex;
    justify-content: space-between;
    gap: 40px;
}

.summary-section {
    flex: 2;
    background: #63c3d1;
    padding: 20px;
    border-radius: 10px;
    color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.summary-title, .payment-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 30px;
}

.item-name, .item-price {
    font-size: 16px;
    color: white;
}
.payment-card {
    flex: 1 0 calc(50% - 10px); 
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
    background: #fff;
    height: 120px; /* Reduced height of the card */
    position: relative; /* To position the checkmark absolutely inside */
}

.payment-icon img {
    max-height: 60px; /* Reduced the max-height for the image */
    width: auto;
    margin: auto; /* Added to center the image */
    display: block; /* To make margin: auto work for centering the image */
}

.checkmark {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 24px;
    height: 24px;
    background-color: #4CAF50;
    display: none;
}

.payment-card:hover {
    transform: scale(1.05); /* Slightly scale up */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); /* Add a bit of shadow */
    background-color: #f7f7f7; /* Lighten the card's background */
    transition: all 0.3s ease; /* Smooth transition for all changes */
}

@media (max-width: 850px) {
    .container {
        margin: 10px !important;
        width: 95%;
    }
    .item {
        flex-direction: column; /* This will stack the children vertically */
        align-items: flex-start; /* Aligns items to the left */
        gap: 5px; /* Adds some space between the stacked items */
    }
    .content-wrapper {
        flex-direction: column; 
        gap: 20px;
    }

    .left-section, .right-section {
        padding: 15px; 
    }
}

</style>

<div class="container" style="margin-bottom: 6rem !important;">
    <div class="row">
        <div class="col-md-8 left-section p-5">
            <h3 class="summary-title">Récapitulatif</h3>
            <h4 class="p-3">Adresse de facturation : </h4>
            <span class="d-flex p-3 pt-0">{{ $adresse->address }} <br> {{ $adresse->zip }} {{ $adresse->city }} <br>{{ $adresse->country }}</span>
            @foreach ($paniers as $article)
            <div class="item">
                <span style="font-weight:bold" class="text-dark">{{ $article->lastname }} {{ $article->name }}</span>
                <span>{{ $article->title }}</span>
                @if ($article->reduction != null)
                <span class="text-danger">({{ $article->reduction }})</span>
                @endif
                <span class="price">{{ $article->qte }} * {{ number_format($article->totalprice, 2, ',', ' ') }} €</span>
            </div>
            @endforeach
            <div class="total mt-5">
                <strong>Total :</strong>
                <span>{{ number_format($total, 2, ',', ' ') }} €</span>
            </div>
        </div>
        <div class="col-md-4 right-section p-5">
            <h3 class="payment-title">Moyens de paiement :</h3>
            <div class="payment-grid">
                @foreach ($Mpaiement as $Mpaiement)
                @if ($Mpaiement->payment_method != 'Mixte' && ($Mpaiement->payment_method != 'Virement' || $total >= 800))
                <div  data-toggle="modal" data-target="#{{ $Mpaiement->payment_method }}" class="payment-card">
                    <form action="#">
                        <label for="{{ $Mpaiement->payment_method }}">
                            <div class="payment-icon">
                                <img src="{{ $Mpaiement->icon }}" alt="{{ $Mpaiement->payment_method }}">
                            </div>
                            <h6 class="payment-subtitle">{{ $Mpaiement->payment_method }}</h6>
                            <div class="checkmark"></div>
                        </label>
                    </form>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
 <!-- Modal -->
 <div class="modal fade" id="Espèces" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content p-2">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Paiement en Espèces</h5>
                <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
               {!! $Espece->text !!}
                <a href="{{ route('fichepaiement', ['id' => 3, 'nombre_cheques' => 1]) }}" class="btn btn-primary mr-2">Valider ma commande</a>
                <button type="button" class="btn-rouge " data-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Carte Bancaire" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content p-2">
          <div class="modal-header bg-primary text-white">
              <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Paiement en Carte Bancaire</h5>
              <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </a>
          </div>
          <div class="modal-body">
              {!! $cb->text !!}
              <select class="form-control selectpicker mb-3" id="nombre_virment" data-style="" data-width="fit"></select> 
              <br>
              <a href="#" class="btn btn-primary mr-2 " id="valider_virment">Valider ma commande</a>
              <button type="button" class="btn-rouge " data-dismiss="modal">Annuler</button>
              <script>
                  document.getElementById('valider_virment').addEventListener('click', function(event) {
                      event.preventDefault();
                      var nombre_virment = document.getElementById('nombre_virment').value;
                      var url = '{{ route('payment_form', ['nombre_virment' => ':nombre_virment', 'total' => $total ]) }}';
                      url = url.replace(':nombre_virment', nombre_virment);

                      window.location.href = url;
                  });
              </script>
          </div>
      </div>
  </div>
</div>
<script>
  var total = {{ $total }}; // Mettre ici la valeur du total
  var nombre_virment = document.getElementById('nombre_virment');

  var maxPayments;
  if (total < 50) {
      maxPayments = 1;
  } else if (total < 100) {
      maxPayments = 2;
  } else if (total < 150) {
      maxPayments = 3;
  } else if (total < 200) {
      maxPayments = 4;
  } else {
      maxPayments = 5;
  }

  for (var i = 1; i <= maxPayments; i++) {
      var option = document.createElement('option');
      option.value = i;
      option.text = i;
      nombre_virment.add(option);
  }

  // Lorsque l'utilisateur clique sur le bouton "Valider ma commande"
  document.getElementById('valider_virment').addEventListener('click', function(event) {
      event.preventDefault(); // Empêcher le comportement par défaut du lien
      var nombre_virment_value = nombre_virment.value; // Récupérer la valeur sélectionnée
      var url = '{{ route('payment_form', ['nombre_virment' => ':nombre_virment', 'total' => $total ]) }}';
      url = url.replace(':nombre_virment', nombre_virment_value); // Remplacer la valeur de la variable dans l'URL
      window.location.href = url; // Rediriger vers la page payment_form avec le nombre de virements sélectionné
  });
</script>


<div class="modal fade" id="Bons" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content p-2">
          <div class="modal-header bg-primary text-white">
              <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Paiement par Bons</h5>
              <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
          </div>
          <div class="modal-body">
            {!! $Bons->text !!}
              
              <a href="{{ route('detail_paiement', ['id' => 5, 'nombre_cheques' => 1]) }}" class="btn btn-primary mr-2">Valider ma commande</a>
              <button type="button" class="btn-rouge " data-dismiss="modal">Annuler</button>
          </div>
      </div>
  </div>
</div>





    
<div class="modal fade" id="Chèques" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content p-2">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Paiement par Chèques</h5>
            <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </a>
        </div>
        <div class="modal-body">
            {!! $Cheques->text !!}
            <p class="mt-4">Veuillez sélectionner le nombre de paiements que vous souhaitez effectuer :</p>
            <div class="form-group mb-3">
                <select class="form-control selectpicker" id="nombre_cheques" data-style="btn-dark" data-width="auto"></select>
            </div>
            <a href="#" class="btn btn-primary mr-2" id="valider_commande">Valider ma commande</a>
            <button type="button" class="btn-rouge " data-dismiss="modal">Annuler</button>
        </div>
    </div>
</div>
</div> 


<script>
var total = {{ $total }}; // Mettre ici la valeur du total
var nombre_cheques = document.getElementById('nombre_cheques');

var maxPayments;
if (total < 50) {
    maxPayments = 1;
} else if (total < 100) {
    maxPayments = 2;
} else if (total < 150) {
    maxPayments = 3;
} else if (total < 200) {
    maxPayments = 4;
} else {
    maxPayments = 5;
}

for (var i = 1; i <= maxPayments; i++) {
    var option = document.createElement('option');
    option.value = i;
    option.text = i;
    nombre_cheques.add(option);
}

// Lorsque l'utilisateur clique sur le bouton "Valider ma commande"
document.getElementById('valider_commande').addEventListener('click', function(event) {
    event.preventDefault(); // Empêcher le comportement par défaut du lien
    var nombre_cheques_value = nombre_cheques.value; // Récupérer la valeur sélectionnée
    var url = '{{ route('detail_paiement', ['id' => 4, 'nombre_cheques' => ':nombre_cheques']) }}';
    url = url.replace(':nombre_cheques', nombre_cheques_value); // Remplacer la valeur de la variable dans l'URL
    window.location.href = url; // Rediriger vers la page detail_paiement avec le nombre de chèques sélectionné
});
</script>

@endsection

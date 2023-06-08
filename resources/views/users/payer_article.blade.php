@extends('layouts.app')

@section('content')
<main id="main" class="main" style="padding : 20px 0; background-image: url('{{asset("/assets/images/background.png")}}');">
    <div style="background-color:white;"  class="container rounded" >
      @if (session('success'))
            <div class="alert alert-success col-12 m-3">
                {{ session('success') }}
            </div>
      @endif
      @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
  <div class="row">
    <div class="widget-title col-12 d-flex justify-content-between align-items-center">
      <h5 class="text-dark font-weight-bold" style="font-weight:bold">Récapitulatif :&nbsp;</h5>
      <span class="total-price">Total : {{ number_format($total, 2, ',', ' ') }} € TTC</span>
    </div>
    <div class="row d-flex justify-content-between">
      <div class="col-md-7 mx-3 border border-dark" style="background-color:#edeeef;">
        <h4 class="p-3">Produits Achetés :</h4>
        @foreach ($paniers as $article)
        <div class="row d-flex justify-content-between align-items-center mb-2">
          <div class="col-12 col-sm-3 d-flex justify-content-start">
            <span style="font-weight:bold" class="text-dark">{{ $article->lastname }} {{ $article->name }}</span>
          </div>
          <div class="col-12 col-sm-5 d-flex justify-content-start">
            <span>{{ $article->title }}
            @if ($article->reduction != null)
            <span class="text-danger">({{ $article->reduction }})</span>
            @endif
            </span>
          </div>
          <div class="col-12 col-sm-4 d-flex justify-content-center">
            <span>{{ number_format($article->totalprice, 2, ',', ' ') }} € TTC</span>
          </div>
        </div>
        <hr>
        @endforeach
        <div class="d-flex justify-content-end">
          <span class="text-success p-3" style="font-weight:bold;text-align: right;">Total : {{ number_format($total, 2, ',', ' ') }} € TTC</span>
        </div>
      </div>
      <div class="col-md-4 mx-3 border border-dark my-auto" style="background-color:#edeeef;">
        <h4 class="p-3">Adresse de facturation : </h4>
        <span class="d-flex p-3 pt-0">{{ $adresse->address }} <br> {{ $adresse->zip }} {{ $adresse->city }} <br>{{ $adresse->country }}</span>
      </div>
    </div>
  </div>
  
        <hr>
        <div class="row d-flex justify-content-center ">
            <h5 style="font-weight:bold"  class="text-dark font-weight-bold p-3">Moyens de paiement :</h5>

            @foreach ($Mpaiement as $Mpaiement)
                @if ($Mpaiement->payment_method == 'Mixte'  )
                 @elseif ($Mpaiement->payment_method == 'Virement' && $total < 800)
                @else
                    <div class="col-md-5  row mx-2 d-flex justify-content-center mb-5">
                        <div style="background-color:#edeeef;" class="col-7 d-flex justify-content-center m-2 p-1 border">
                            <img style="width : 30px;height:30px;" src="{{ $Mpaiement->icon}}" alt=""><h5 class="mx-3">{{ $Mpaiement->payment_method}}</h5>
                        </div>
                        <div style="    min-height: 204px;
                        " class="col-11 d-flex justify-content-center m-2 align-items-center">
                          @if ($Mpaiement->payment_method == 'Carte Bancaire')
                          <a type="button" href="{{ route('payment_form', ['user_id' => $article->user_id, 'total' => $total]) }}"><img class="imghover" style="max-width : 200px" src="{{ $Mpaiement->image}}" alt=""></a>
                        @else
                            <a type="button"  data-toggle="modal" data-target="#{{ $Mpaiement->payment_method }}" href="#"><img class="imghover" style="max-width : 200px" src="{{ $Mpaiement->image}}" alt=""></a>
                        @endif
                        </div>
                    </div>
                @endif
            @endforeach

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
                          <a href="{{ route('detail_paiement', ['id' => 3, 'nombre_cheques' => 1]) }}" class="btn btn-primary mr-2">Valider ma commande</a>
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                      </div>
                  </div>
              </div>
          </div>

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
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
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
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
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
      
      


                <div class="modal fade" id="Virement" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content p-2">
                          <div class="modal-header bg-primary text-white">
                              <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Virement Bancaire</h5>
                              <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </a>
                          </div>
                          <div class="modal-body">
                            {!! $Virement->text !!}
                              
                              <a href="#" class="btn btn-primary mr-2" id="valider_virment">Valider ma commande</a>
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                              <script>
                                  document.getElementById('valider_virment').addEventListener('click', function(event) {
                                      event.preventDefault();
                                      var nombre_virment = document.getElementById('nombre_virment').value;
                                      var url = '{{ route('detail_paiement', ['id' => 6, 'nombre_cheques' => ':nombre_virment']) }}';
                                      url = url.replace(':nombre_virment', nombre_virment);
                                      window.location.href = url;
                                  });
                              </script>
                          </div>
                      </div>
                  </div>
              </div>
              
              
              

            </div>
              
              
        </div>
</main>

@endsection



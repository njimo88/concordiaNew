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
            <span style="font-weight:bold" class="text-dark">{{ $article->lastname }}{{ $article->name }}</span>
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
            @if ($Mpaiement->payment_method == 'Carte Bancaire')
            @else
                <div  class="col-md-5  row mx-2 d-flex justify-content-center mb-5">
                    <div style="background-color:#edeeef;" class="col-7 d-flex justify-content-center m-2 p-1 border">
                        <img style="width : 30px" src="{{ $Mpaiement->image}}" alt=""><h5 class="mx-3">{{ $Mpaiement->payment_method}}</h5>
                    </div>
                    <div class="col-11 d-flex justify-content-center m-2">
                      @if ($Mpaiement->payment_method == 'Carte Bancaire')
                <a type="button" href="/payment_form"><img class="imghover" style="max-width : 200px" src="{{ $Mpaiement->image}}" alt=""></a>
            @else
            <a type="button"  data-toggle="modal" data-target="#{{ $Mpaiement->payment_method }}" href="#"><img class="imghover" style="max-width : 200px" src="{{ $Mpaiement->image}}" alt=""></a>

            @endif
                    </div>

                </div>
                @endif
            @endforeach
            <!-- Modal -->
            <div class="modal fade" id="Espèces" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content p-2">
                    <div class="modal-header">
                      <h5 style="font-weight:bold" class="modal-title" id="exampleModalLabel">Payement par espèces</h5>
                      <a type="button"  data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </a>
                    </div>
                    <div class="modal-body">
                        <a href="{{ route('detail_paiement', ['id' => 3, 'nombre_cheques' => 1]) }}" class="btn btn-primary">Valider ma commande</a>
                    </div>
                    
                  </div>
                </div>
              </div>

              
              <div class="modal fade" id="Chèques" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content p-2">
                    <div class="modal-header">
                      <h5 style="font-weight:bold" class="modal-title" id="exampleModalLabel">Paiement par chèque</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <p>Frais : 1 € / chèque</p>
                        <p>En cas de paiement en plusieurs fois :</p>
                        <ul>
                            <li>20% immédiat</li>
                            <li>80% fractionnable</li>
                        </ul>
                        <div class="form-group mb-4">
                            <label for="nombre_cheques">Nombre de chèques:</label>
                            <select class="form-control selectpicker" id="nombre_cheques" data-style="btn-danger" data-width="auto">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <a href="#" class="btn btn-primary" id="valider_commande">Valider ma commande</a>
                        <script>
                            // Lorsque l'utilisateur clique sur le bouton "Valider ma commande"
                            document.getElementById('valider_commande').addEventListener('click', function(event) {
                                event.preventDefault(); // Empêcher le comportement par défaut du lien
                                var nombre_cheques = document.getElementById('nombre_cheques').value; // Récupérer la valeur sélectionnée
                                var url = '{{ route('detail_paiement', ['id' => 4, 'nombre_cheques' => ':nombre_cheques']) }}';
                                url = url.replace(':nombre_cheques', nombre_cheques); // Remplacer la valeur de la variable dans l'URL
                                window.location.href = url; // Rediriger vers la page detail_paiement avec le nombre de chèques sélectionné
                            });
                        </script>
                    </div>
                  </div>
                  </div>
                </div>


                <div class="modal fade" id="Virement" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content p-2">
                        <div class="modal-header">
                          <h5 style="font-weight:bold" class="modal-title" id="exampleModalLabel">Paiement par prélèvement</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <p>Frais : 0 € / prélèvement</p>
                          <p>En cas de paiement en plusieurs fois :</p>
                          <ul>
                            <li>20% immédiat</li>
                            <li>80% fractionnable</li>
                          </ul>
                          <div class="form-group mb-4">
                            <label for="nombre_virment">Nombre de virements:</label>
                            <select class="form-control selectpicker" id="nombre_virment" data-style="btn-danger" data-width="auto">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <a href="#" class="btn btn-primary" id="valider_virment">Valider ma commande</a>
                        <script>
                            // Lorsque l'utilisateur clique sur le bouton "Valider ma commande"
                            document.getElementById('valider_virment').addEventListener('click', function(event) {
                                event.preventDefault(); // Empêcher le comportement par défaut du lien
                                var nombre_virment = document.getElementById('nombre_virment').value; // Récupérer la valeur sélectionnée
                                var url = '{{ route('detail_paiement', ['id' => 6, 'nombre_cheques' => ':nombre_virment']) }}';
                                url = url.replace(':nombre_virment', nombre_virment); // Remplacer la valeur de la variable dans l'URL
                                window.location.href = url; // Rediriger vers la page detail_paiement avec le nombre de chèques sélectionné
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



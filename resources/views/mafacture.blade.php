@extends('layouts.app')

@section('content')
<style>
/* Base styles */
body {
    font-family: 'Roboto', sans-serif !important;
}



h4, h5 {
    font-weight: 500 !important;
    margin-bottom: 20px !important;
    border-bottom: 2px solid #272e5c !important;
    display: inline-block !important;
    color: #272e5c !important;
}

a {
    color: #272e5c !important;
    text-decoration: none !important;
    transition: all 0.3s !important;
}

a:hover {
    text-decoration: underline !important;
}

.my-custom-btn {
    background-color: #666675 !important;
    border-color: #272e5c !important;
    color: white !important;
    padding: 10px 20px !important;
    border-radius: 5px !important;
    transition: all 0.3s !important;
    display: inline-block !important;
    text-align: center !important;
}

.my-custom-btn:hover {
    background-color: #1a2140 !important;
    border-color: #1a2140 !important;
    color: white !important;
}

.text-success {
    color: #4CAF50 !important;
}

/* Styling for fields */
fieldset {
    border: 1px solid #272e5c !important;
    border-radius: 5px !important;
    padding: 20px !important;
    margin-bottom: 20px !important;
}
.shadow{
    border: 1px solid #e0e0e0 !important;  
    border-radius: 8px !important;  
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important; 
}

</style>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
    <section class="container-fluid ps-4 py-2" style="background-color: @if ( $bill->row_color == 'none' ) #00ff00 @else {{ $bill->row_color }} @endif">
        <div class="container-xxl">
            <div class="row justify-content-between align-items-center">
                <div class="col-12 col-xl-5">
                    <p class="fw-bold text-white mb-0 link-white">
                        <i class="far fa-lg fa-newspaper me-2 me-md-3 text-white"></i>
                        <a href="#" rel="category tag">Facture n°{{ $bill->id }}</a>&nbsp;&nbsp;/ &nbsp;&nbsp;{{ $bill->bill_status }}
                    </p>
                </div>
                
                @if ($bill->status >= 70)
                <div class="col-auto">
                    <form method="post" action="{{ route('generatePDFfacture', $bill->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="my-custom-btn btn btn-primary my-2">Facture <img src="{{ asset("assets\images\pdf-icon.png") }}" alt=""></button>
                    </form>
                    <form method="post" action="{{ route('generatePDFreduction_Fiscale', $bill->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="my-custom-btn btn btn-primary my-2">Réduction Fiscale  <img src="{{ asset("assets\images\pdf-icon.png") }}" alt=""></button>
                    </form>
                </div>
                @endif
                
            </div>
        </div>
    </section>
    
    <section class="container-fluid  py-4" style="background-color: #f2f2f2 ">
        <div class="container-xxl bg-white p-4 shadow">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="content py-3">
                        <div class="row">
    
                            <!-- Information Membre -->
                            <div class="col-md-4 col-12 p-0 cardd">
                                <h5 class="cardd-header  ">Informations Membre</h5>
                                <div class="cardd-body">
                                    <p><strong>{{ $bill->lastname }} {{ $bill->name }} (ID n°{{ $bill->user_id }})</strong></p>
                                    <p><i class="fa fa-envelope"></i> <a href="mailto:{{ $bill->email }}">{{ $bill->email }}</a></p>
                                    <p><i class="fa fa-phone"></i> <a href="tel:{{ $bill->phone }}">{{ $bill->phone }}</a></p>
                                    <p>{{ \Carbon\Carbon::parse($bill->birthdate)->age }} ans</p>
                                    <h5>Adresse de facturation</h5>
                                    <p>
                                        {{ $bill->lastname }} {{ $bill->name }} <br>
                                        {{ $bill->address }} <br>
                                        {{ $bill->zip }} {{ $bill->city }} <br>
                                        {{ $bill->country }}
                                    </p>
                                </div>
                            </div>
    
                            <!-- Détail de la commande -->
                            <div class="col-md-4 col-12 p-0 cardd">
                                <h5 class="cardd-header  ">Détail de la commande</h5>
                                <div class="cardd-body">
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
        <span style="font-weight:bold">Mode de paiement </span>: {{ $bill->method }} <br>
        Paiement en {{ $bill->number }} fois <br>
        <span style="font-weight:bold">Frais </span>: {{ number_format($bill->total_charges, 2, ',', ' ') }} €<br>
        <span style="font-weight:bold">Coût TTC </span>: {{ number_format($bill->payment_total_amount, 2, ',', ' ') }} €<br>
      </div>
    </div>
      <!-- Détail paiement -->
      <div class="col-md-4 col-12 p-0 d">
        <h5 class="cardd-header ">Détail paiement</h5>
        <div class="cardd-body">
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

          $datetime = new DateTime(now()->format('Y-m-d'));
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
      </div>
    </div>
    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container-fluid  text-white p-3" style="background-color: #292543">
        <div class="container-xxl">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10 d-flex align-items-center">
                    <i class="fa-solid fa-circle-info me-3"></i>
                    <p class="fw-bold mb-0">
                        Détail Facture
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="container-fluid  py-4" style="background-color: #f2f2f2 ">
        <div class="container-xxl bg-white p-4 shadow">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="content py-3">
                        @if (count($shop) > 0)
                            <table id="" style="width: 98%; margin:0px auto !important" class="table">
                            <thead>
                                <th><a>Désignation</a></th>
                                <th><a>Quantité</a></th>
                                <th><a>Prix</a></th>
                                <th><a>Sous-total</a></th>
                                <th><a></a></th>
                                <th><a></a></th> <!-- New column for delete button -->
                            </thead>
                            <tbody>
                                @foreach ($shop as $shop)
                                <tr>
                                    
                                    <form>
                                        <td style="width: 600px;">
                                            <div class="row">
                                                <div class="col-md-2 col-12">
                                                    <img style="height: 70px" src="{{ $shop->image }}" alt="">
                                                </div>
                                                    <div class="col-md-6 col-12">
                                                <a href="{{ route('singleProduct', ['id' => $shop->id_shop_article]) }}" style="text-decoration: none;" target="_blank">
                                                        <input type="hidden" name="user_id" value="{{ $bill->user_id }}">
                                                        <select  name="designation" class="border form-select mt-3 @error('role') is-invalid @enderror"
                                                                name="status" id="status" autocomplete="status" autofocus role="listbox">
                                                            <option value="{{ $shop->designation }}" role="option" selected>{{ $shop->designation }}</option>
                                                        </select>
                                                </a>

                                                    </div>
                                                
                                            </div>
                                        </td>
                        
                                    </form>
                        
                                    <td>{{ $shop->quantity }} </td>
                                    <td>{{ number_format($shop->ttc, 2, ',', ' ') }} €</td>
                                    <td>{{ number_format($shop->sub_total, 2, ',', ' ') }} €</td>
                                    <td>{{ $shop->addressee }}</td>
                                </tr>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container-fluid  ps-4 py-4" style="background-color: #403a69">
        <div class="container-xxl">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <p class="fw-bold text-white mb-0 link-white">
                        <i class="fa-solid fa-clock-rotate-left me-2 me-md-3 text-white" ></i>
                        <a href="#" rel="category tag">Historique des modification</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="container-fluid  py-4" style="background-color: #f2f2f2 ">
        <div class="container-xxl bg-white p-4 shadow">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="content py-3">
                        <div class="col-lg-12 mt-3">
                            @if ($messages->count() > 0)
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
                                    <div>
                                        <u>{{ $message->lastname }} {{ $message->name }} <time datetime="{{ $message->date }}">{{ $formattedDate }}</time></u><br>
                                        
                                        @if ($message->somme_payé <= 0 && $message->somme_payé != null)
                                            <b>Somme payée : </b><span style="font-weight : bold" class="text-danger">{{ $message->somme_payé*-1 }} €</span><br>
                                        @elseif ($message->somme_payé > 0)
                                            <b>Somme remboursée : </b><span style="font-weight : bold" class="text-success">{{ $message->somme_payé }} €</span><br>
                                        @endif
                                    </div>

                                    <div class="col-11 my-2">
                                        {!! html_entity_decode(nl2br(e($message->message))) !!}
                                    </div>

                                    <hr>
                                </div>
                            @endforeach
                        @else
                            <div class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    <h4 class="mt-4">Aucun message disponible.</h4>
                                </div>
                            </div>
                        @endif

                          </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('shop')
@endsection
@extends('layouts.app')

@section('content')
<main class="main" id="main"  style="background-image: url('{{asset("/assets/images/background.png")}}')">


<div style="background-color: white;" class="container  justify-content-center">
    <div class="row">
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
        <div class="col-3">
            <h3 style="color: black" class="my-4  ml-0">Facture n°{{ $bill->id }}</h3>
        </div>
        <div class="row col-9 d-flex justify-content-end" >
          <div class="col-3 d-flex justify-content-end" >
            <form method="post" action="{{ route('generatePDFfacture', $bill->id) }}">
              @csrf
              <button type="submit" class="my-custom-btn btn btn-primary my-4">Facture <img src="{{ asset("assets\images\pdf-icon.png") }}" alt=""></button>
            </form>
          </div>
          @if ($bill->status == 100)
          <div class="col-3 d-flex justify-content-end" >
            <form method="post" action="{{ route('generatePDFreduction_Fiscale', $bill->id) }}">
              @csrf
              <button type="submit" class="my-custom-btn btn btn-primary my-4">Réduction Fiscale  <img src="{{ asset("assets\images\pdf-icon.png") }}" alt=""></button>
            </form>
          </div>
          @endif          
          <div class="col-2 d-flex justify-content-end" >
            <a href="{{ url()->previous() }}" class="my-custom-btn btn btn-primary my-4">Retour  <img src="{{ asset("assets\images\a-gauche.png") }}" alt=""></a>
          </div>
      </div>
        
        <hr>
        
        @if (auth()->user()->roles->changer_status_facture && Route::currentRouteName() === 'facture.showBill')
        
          <form action="{{ route('facture.updateStatus', $bill->id) }}" method="post">
              @csrf
              @method('PUT')
          @else
              <form>
          @endif
              <div style="background-color: @if ( $bill->row_color == 'none' ) #00ff00 @else {{ $bill->row_color }} @endif" class="mb-3 row d-flex justify-content-between">
                  <div class="col-md-5 p-4 col-12">
                      <select  class="border col-md-12 form-select @error('role') is-invalid @enderror" name="status" id="status" autocomplete="status" autofocus role="listbox" @if(!auth()->user()->roles->changer_status_facture || Route::currentRouteName() !== 'facture.showBill'
                        ) disabled @endif>
                          @foreach($status as $status)
                              <option value="{{ $status->id }}" {{ $bill->status == $status->id ? 'selected' : '' }} role="option">{{ $status->status }}</option>
                          @endforeach
                      </select>
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
        <span style="font-weight:bold">{{ $bill->lastname }} {{ $bill->name }} (n°{{ $bill->user_id }}) </span> <br>
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
        <span style="font-weight:bold">Mode de paiement </span>: {{ $bill->method }} <br>
        Paiement en {{ $bill->number }} fois <br>
        <span style="font-weight:bold">Frais </span>: {{ number_format($bill->total_charges, 2, ',', ' ') }} €<br>
        <span style="font-weight:bold">Coût TTC </span>: {{ number_format($bill->payment_total_amount, 2, ',', ' ') }} €<br>
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

          $datetime = new DateTime(now()->format('Y-m-d'));
          $formattedMonthYear = $datetime->format('F Y');
          $i = 1;

          foreach ($nb_paiment as $paiment) {
              echo '<b>Paiement ' . $i . '</b>: &nbsp;' . number_format($paiment, 2, ',', ' ') . ' €&nbsp;&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;&nbsp; <b>Echance : &nbsp;</b>' . $formattedMonthYear . '<br>';
              
              $datetime->add(new DateInterval('P1M')); // add one month to the date
              $formattedMonthYear = $englishToFrench[$datetime->format('F')] . ' ' . $datetime->format('Y');
              $i++;
          }
          ?>
      </fieldset>
      <br><br>
        <span style="font-weight:bold">Reste à payer : {{ number_format($bill->payment_total_amount-$bill->amount_paid, 2, ',', ' ') }} €</span> 
        <br><br><br>
        @if (auth()->user()->roles->paiement_immediat && Route::currentRouteName() === 'facture.showBill')
          <a href="{{ route("paiement_immediat",$bill->id ) }}" class="my-custom-btn btn btn-primary my-4 p-2">Paiement Immédiat <img  style="width: 30px" src="{{ asset('assets/images/fds.png') }}" alt=""></a>
        @endif
      </div>
    </div>

    <div class="row border border-dark mx-2">
      <div class="col-12 d-flex justify-content-center border-bottom -border-dark bg-secondary">
        <h3 class="my-2">Détail Facture</h3> <hr>
      </div>
    </div>

    <table id="" style="width: 98%; margin:0px auto !important"  class="table ">
      <thead>
          <th > <a>Désignation</a></th>
          <th ><a>Quantité</a></th>
          <th ><a >Prix</a></th>
          <th><a >Sous-total</a></th>
          <th><a ></a></th>
      </thead>                            
      <tbody>
          @foreach ($shop as $shop)
          <tr>
            @if (auth()->user()->roles->changer_designation_facture && Route::currentRouteName() === 'facture.showBill')
            <form action="{{ route('facture.updateDes', $shop->id_liaison) }}" method="post">
                @csrf
                @method('PUT')
            @else
                <form>
            @endif
                <td style="width: 800px;">
                    <div class="row ">
                        <div class="col-md-2 col-12">
                            <img style="height: 70px" src="{{ $shop->image }}"  alt="">
                        </div>
                        <div class="col-md-6 col-12">
                            <select name="designation"  class="border form-select mt-3 @error('role') is-invalid @enderror" name="status" id="status" autocomplete="status" autofocus role="listbox" @if(!auth()->user()->roles->changer_designation_facture || Route::currentRouteName() !== 'facture.showBill') disabled @endif>
                                <option value="{{ $shop->designation }}" role="option" selected>{{ $shop->designation }}</option>
                                @foreach($designation as $title)
                                    <option value="{{ $title }}" role="option">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if (auth()->user()->roles->changer_designation_facture && Route::currentRouteName() === 'facture.showBill')
                        <div class="col-md-2 col-12">
                            <button type="submit" class="btn btn-sm btn-warning mt-3">Changer</button>
                        </div>
                        @endif
                    </div>
                </td>
            </form>

              <td>{{ $shop->quantity }} </td>
              <td>{{ number_format($shop->ttc, 2, ',', ' ') }} €</td>
              <td>{{ number_format($shop->sub_total, 2, ',', ' ') }} €</td>
              <td>{{ $shop->addressee   }} </td>
            </tr>
          @endforeach
      
                   
           
          
      </tbody>
  </table>
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
      @if ($message->state == 'Privé')
          <u><b><span style="color:red;">(Privé)</span></b> {{ $message->lastname }} {{ $message->name }} <time datetime="{{ $message->date }}">{{ $formattedDate }}</time></u><br>
      @else
          <u>{{ $message->lastname }} {{ $message->name }} <time datetime="{{ $message->date }}">{{ $formattedDate }}</time></u><br>
      @endif
      @if ($message->somme_payé <= 0 && $message->somme_payé != null)
      <b >Somme payée : </b><span style="font-weight : bold" class="text-danger">{{ $message->somme_payé }} €</span><br>
      @elseif ($message->somme_payé > 0)
        <b >Somme remboursée : </b><span style="font-weight : bold" class="text-success">{{ $message->somme_payé }} €</span><br>
        @endif
      {{ $message->message }}
     
        
      <hr>
  @endforeach
</div>


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
  

  
</div>
<div style="height: 25px"></div>
</main>
@endsection


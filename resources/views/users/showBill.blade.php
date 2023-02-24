@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row">
        <div class="col-6">
            <h3 style="color: black" class="my-4  ml-0">Facture n°{{ $bill->id }}</h3>
        </div>
        <div class="col-6" >
            <a href="{{ route('users.FactureUser') }}" class="my-custom-btn btn btn-primary my-4">Retour à la liste des factures</a>
        </div>
        
    </div>
    
    <table class="table table-bordered table1">
      <thead>
        <tr>
          <th colspan="2">Informations Membre</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Nom</td>
          <td>{{ $bill->lastname }} {{ $bill->name }} (n°{{ $bill->user_id }})</td>
        </tr>
        <tr>
          <td>Email</td>
          <td><a class="a" href="mailto:{{ $bill->email }}">{{ $bill->email }}</a></td>
        </tr>
        <tr>
          <td>Téléphone</td>
          <td><a class="a" href="tel:{{ $bill->phone }}">{{ $bill->phone }}</a></td>
        </tr>
        <tr>
          <td>Age</td>
          <td>{{ \Carbon\Carbon::parse($bill->birthdate)->age }} ans</td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th colspan="2">Adresse de facturation</th>
        </tr>
      </thead>
      <tbody>
        <tr>
         <td>
            {{ $bill->lastname }} {{ $bill->name }}
            <br>
            {{ $bill->address }} <br>{{ $bill->city }} <br>{{ $bill->country }}
        </td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th colspan="2">Détail de la commande</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Numéro de commande</td>
          <td>{{ $bill->id }}</td>
        </tr>
        <tr>
          <td>Date</td>
          <?php
            setlocale(LC_ALL, 'fr_FR.UTF-8');
           ?>

            <td>{{ Carbon\Carbon::parse($bill->date_bill)->formatLocalized('%A %d %B %Y à %H:%M:%S') }}</td>

        </tr>
        <tr>
          <td>Dernière mise à jour</td>
          <td>????????</td>
        </tr>
        <tr>
          <td>Référence commande</td>
          <td>{{ $bill->ref }}</td>
        </tr>
        <tr>
          <td>Mode de paiement</td>
          <td>{{ $bill->payment_method }}</td>
        </tr>
        
          <th>Paiement en 1 fois</th>
        
        <tr>
            <td>Frais</td>
            <td>{{ $bill->total_charges }} €</td>
          </tr>
        <tr>
          <td>Coût TTC</td>
          <td>{{ number_format($bill->payment_total_amount, 2) }}€</td>
        </tr>
      </tbody>
    </table>
    
</div>
@endsection


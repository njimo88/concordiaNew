@extends('layouts.app')

@section('content')
@php
use Carbon\Carbon;
@endphp
<main style="min-height:100vh; background-image: url('{{asset("/assets/images/background.png")}}" class="main " id="main">

<div class="container">
<div  class="row ">
    <div style="    border: solid !important;
    border-width: 1px !important;
    border-color: grey !important;
    margin-bottom: 10px !important;
    box-shadow: 3px 3px 3px #5c5c5c !important;
    " class="card shadow mb-4 mt-4 p-0">
                        <div style="background-color: #A9BCF5 !important" class="card-header py-2  d-flex justify-content-center">
                          <div class="col-9 d-flex align-items-center justify-content-center">
                            <?php
setlocale(LC_TIME, 'fr_FR', 'fra');

// Tableau pour les jours de la semaine
$jours = array(
    'Monday'    => 'Lundi',
    'Tuesday'   => 'Mardi',
    'Wednesday' => 'Mercredi',
    'Thursday'  => 'Jeudi',
    'Friday'    => 'Vendredi',
    'Saturday'  => 'Samedi',
    'Sunday'    => 'Dimanche'
);

// Tableau pour les mois
$mois = array(
    'January'   => 'Janvier',
    'February'  => 'Février',
    'March'     => 'Mars',
    'April'     => 'Avril',
    'May'       => 'Mai',
    'June'      => 'Juin',
    'July'      => 'Juillet',
    'August'    => 'Août',
    'September' => 'Septembre',
    'October'   => 'Octobre',
    'November'  => 'Novembre',
    'December'  => 'Décembre'
);

$date = new DateTime(); // obtenir la date d'aujourd'hui

$jour = $date->format('l'); // obtenir le jour de la semaine
$mois_num = $date->format('n'); // obtenir le mois
$jour_num = $date->format('j'); // obtenir le jour du mois
$annee = $date->format('Y'); // obtenir l'année

// Convertir le jour de la semaine et le mois en français
$jour_fr = $jours[$jour];
$mois_fr = $mois[date('F', mktime(0, 0, 0, $mois_num, 10))];

?>

<h6 class="m-0 font-weight-bold text-primary">
    Joyeux anniversaire ! - <?php echo "$jour_fr $jour_num $mois_fr $annee"; ?>
</h6>

            </div></div>
            <div style="align-items: center !important; background-color : #FFFFFF !important" class="card-body post-content">
                @php
                    $date = new DateTime();
                    $dateString = $date->format('Y-m-d');
                    $filename = $dateString . "-birthday.jpg";

                @endphp
                <img src="{{ asset('assets/images/' . $filename ) }}" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="100">
            </div>
            <div style="align-items: start !important; background-color : #FFFFFF !important" class="card-body post-content">
                <p class="mt-2">Vous pouvez envoyer un petit message à nos membres qui fêtent leurs anniversaires aujourd’hui en cliquant sur leurs noms... À vous de jouer :</p>
                <ul>
                    @foreach ($usersbirth as $user) 
                      @php
                        $age = Carbon::parse($user->birthdate)->diffInYears(Carbon::now());
                      @endphp
                     <li>Écrivez à : {{ $user->name }} {{ $user->lastname  }} qui vient d'avoir ses <b>{{ $age }} ans</b> </li> 
                    @endforeach
                </ul>
                
                
                <br>  <p>La Gym Concordia vous souhaite un joyeux anniversaire!</p>      
            </div>
           
        </div>
    </div>
</div>
</div>

</main>
@endsection
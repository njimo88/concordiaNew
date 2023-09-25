<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
   
</head>
<body>
    @php

	require_once(app_path().'/ProfessionelFonction.php');
	$date = "2023-04-22";
	$timestamp = strtotime($date);
	$couleur = ColorFont($timestamp);

$HeuresTheoriques = array(
    $pro->lundi,
    $pro->mardi,
    $pro->mercredi,
    $pro->jeudi,
    $pro->vendredi,
    $pro->samedi,
    $pro->dimanche
);

$mois 	= 	$pro->LastDeclarationMonth;
$annee 	= 	$pro->LastDeclarationYear;

$VolumeMensueldu = round($pro->VolumeHebdo * 52 / 12, 2);
	if (($pro->LastDeclarationMonth+1)%12 >= 8) {
		$NbMoisPeriode = ($pro->LastDeclarationMonth+1)%12 - 8;
	} else {
		$NbMoisPeriode = ($pro->LastDeclarationMonth+1)%12 + 4;
	}
	$TotalMensueldu = $VolumeMensueldu * $NbMoisPeriode;

	function daysInMonth($month, $year) {
    return date("t", mktime(0, 0, 0, $month+1, 1, $year));
}

// Returns the sum of all weekday values in a given month
function sumWeekdayValues($month, $year, $pro) {
    $daysInMonth = daysInMonth($month, $year);
    $totalSum = 0;

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $weekday = date('l', mktime(0, 0, 0, $month+1, $day, $year));

        switch ($weekday) {
            case 'Monday':
                $totalSum += $pro->Lundi;
                break;
            case 'Tuesday':
                $totalSum += $pro->Mardi;
                break;
            case 'Wednesday':
                $totalSum += $pro->Mercredi;
                break;
            case 'Thursday':
                $totalSum += $pro->Jeudi;
                break;
            case 'Friday':
                $totalSum += $pro->Vendredi;
                break;
            case 'Saturday':
                $totalSum += $pro->Samedi;
                break;
            case 'Sunday':
                $totalSum += $pro->Dimanche;
                break;
        }
    }

    return $totalSum;
}

$monthNames = [
    1 => 'Janvier',
    2 => 'Février',
    3 => 'Mars',
    4 => 'Avril',
    5 => 'Mai',
    6 => 'Juin',
    7 => 'Juillet',
    8 => 'Août',
    9 => 'Septembre',
    10 => 'Octobre',
    11 => 'Novembre',
    12 => 'Décembre',
    ];

    $dayNames = [
        'Monday' => 'Lundi',
        'Tuesday' => 'Mardi',
        'Wednesday' => 'Mercredi',
        'Thursday' => 'Jeudi',
        'Friday' => 'Vendredi',
        'Saturday' => 'Samedi',
        'Sunday' => 'Dimanche'
    ];

$TotalHeures = sumWeekdayValues($mois, $annee, $pro);
$TotalCongespris = 0;
$TotalConges = $pro->SoldeConges + 2.5;
$TotalHeuresMaladieprises = 0;
$TotalMaladiepris = 0 ;
	
@endphp
<style>
    .btn-custom {
        margin: 0 10px;
    }
    .validation-text {
        font-weight: bold;
        color: #333;
        font-size: 20px
    }
</style>
<style>
    
    table {
        width: 100%;
    }

    .date-col {
        width: 33%;
    }
    .theo-col, .real-col {
        width: 8%;
    }
    .conges-col, .maladie-col {
        width: 3%;
    }
    .remarque-col {
        width: 50%;
    }
       
    

    body {
        font-family: Arial, Helvetica, sans-serif !important;
    }

    .table td, .table th, .small-text {
        font-size: 12px; 
        margin: 0%;
    }
   
    /* Supprime l'espacement autour des cellules de tableau */
    .table tr, .table td, .table th {
        padding: 0;
        border-spacing: 0;
        border-collapse: collapse;
        line-height: 0.2; /* réduit l'espace entre les lignes */
    }

    /* Réduire l'espace autour des éléments <p> et <b> */
    .compact p, .compact b {
        margin: 0;
    }
    table {
        margin-left: 0; /* Aligner la table à gauche */
    }
</style>


<b><h2>{{ $pro->firstname }} {{ $pro->lastname }} - Mois de {{ $monthNames[$mois] }} {{ $annee }}</h2></b>
<style>
    .frcontent {
        font-size: 12px; /* Réduire la taille de la police */
        line-height: 0.2 !important; /* Réduire l'espace entre les lignes */
    }
</style>

<div class="frcontent">
    <p>Mois de <b>{{ $monthNames[$mois] }} {{ $annee }}</b> - {{ isset($declaration) ? $declaration->heures_realisees : $TotalHeures }} Heures réalisées</p>
    <p>Jours de Congés pris {{ isset($declaration) ? $declaration->jours_conges : $TotalCongespris }} jours (soit {{ isset($declaration) ? $TotalConges-$declaration->jours_conges : $TotalConges }} jours restant)</p>
    <p>Jours de Maladie pris {{ isset($declaration) ? $declaration->jours_maladie : $TotalMaladiepris }} jours (soit {{ isset($declaration) ? $TotalHeuresMaladieprises : $TotalHeuresMaladieprises }} heures)</p>
</div>

<hr>
    <p style="line-height: 0.2 !important; margin-top : 20px !important" class="small-text ">Saison {{ $pro->Saison }}-{{ $pro->Saison+1 }} : Du 1er Août {{ $pro->Saison }} au 31 Juillet {{ $pro->Saison+1 }}</p>
    <p  class="small-text ">Cumul période jusqu'au 1er {{ $monthNames[$mois] }} {{ $annee }} (inclus) : {{ $pro->OldHeuresRealisees }} heures réalisées / {{ $TotalMensueldu }} heures dues</p>
    <hr>
   
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="col-2">Date</th>
                <th class="col-2">H Théo</th>
                <th class="col-2">H Réal</th>
                <th class="col-1">Congés</th>
                <th class="col-1">Maladie</th>
                <th class="">Remarque</th>
            </tr>
        </thead>
        <tbody>
            @php
            $daysInMonth = date('t', mktime(0, 0, 0, $mois+1, 1, $annee));
            @endphp
            @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
            $date = mktime(0, 0, 0, $mois, $day, $annee);
            $weekday = date('l', $date);
            $formattedDate = $dayNames[$weekday] . ' ' . $day . ' ' . $monthNames[$mois] . ' ' . $annee;
            $color = ColorFont($date);
            $weekdayValue = $dayNames[$weekday];
            $weekdayValue = $pro->$weekdayValue;
    
            // Utiliser details_admin si disponible, sinon utiliser details
            $detail_data = $declaration->details_admin ? json_decode($declaration->details_admin, true) : $declaration->details;
            $details = isset($declaration) && array_key_exists($day - 1, $detail_data) ? $detail_data[$day - 1] : null;
            @endphp
            <tr  style="background-color: {{ $color }};">
                <td style="margin: 0% !important;padding : 0% !important;" class="date-col">{{ $formattedDate }}</td>
                <td style="margin: 0% !important;padding : 0% !important;" class="theo-col">{{ $weekdayValue }}</td>
                <td style="margin: 0% !important;padding : 0% !important;" class="real-col">{{ $details ? $details['heures'] : $weekdayValue }}</td>
                <td style="margin: 0% !important;padding : 0% !important;" class="conges-col"><input style="margin: 0% !important;padding : 0% !important;" type="checkbox" {!! $details && $details['conges'] ? 'checked' : '' !!} disabled></td>
                <td style="margin: 0% !important;padding : 0% !important;" class="maladie-col"><input style="margin: 0% !important;padding : 0% !important;" type="checkbox" {!! $details && $details['maladie'] ? 'checked' : '' !!} disabled></td>
                <td style="margin: 0% !important;padding : 0% !important;" class="remarque-col">{{ $details ? $details['remarque'] : '' }}</td>
            </tr>
            @endfor
        </tbody>
    </table>
    <script>
        function bloqueHeuresConges(pNum, pValue) {
        
        if (document.getElementById('Conges[' + pNum + ']').checked == true) {
            document.getElementById('Heures[' + pNum + ']').value = pValue;
            if (document.getElementById('Maladie[' + pNum + ']').checked == true) {
                document.getElementById('Maladie[' + pNum + ']').checked = false;
                document.getElementById('JoursMaladiePris').value = document.getElementById('JoursMaladiePris').value - 1;
                document.getElementById('TotalHeuresMaladiePrises').value = document.getElementById('TotalHeuresMaladiePrises').value - document.getElementById('Heures[' + pNum + ']').value;
            };
            document.getElementById('JoursCongesRestant').value = document.getElementById('JoursCongesRestant').value - 1;
            document.getElementById('JoursCongesPris').value = document.getElementById('JoursCongesPris').value - (-1);
            document.getElementById('Heures[' + pNum + ']').setAttribute('readonly', 'true');
            document.getElementById('Heures[' + pNum + ']').setAttribute('style', 'text-align:center; background-color:#77B5FE');
            calculTotal(pNum);
        };
        if (document.getElementById('Conges[' + pNum + ']').checked == false) {
            document.getElementById('Heures[' + pNum + ']').value = pValue;
            document.getElementById('JoursCongesRestant').value = document.getElementById('JoursCongesRestant').value - (-1);
            document.getElementById('JoursCongesPris').value = document.getElementById('JoursCongesPris').value - 1;
            document.getElementById('Heures[' + pNum + ']').removeAttribute('readonly');
            document.getElementById('Heures[' + pNum + ']').setAttribute('style', 'text-align:center; background-color:#FFFFFF;');
            calculTotal(pNum);
        };
    }
    
    
        function bloqueHeuresMaladie(pNum, pValue) {
    
            if (document.getElementById('Maladie[' + pNum + ']').checked == true) {
                if (document.getElementById('Conges[' + pNum + ']').checked == true) {
                    document.getElementById('Conges[' + pNum + ']').checked = false;
                    document.getElementById('JoursCongesRestant').value = document.getElementById('JoursCongesRestant').value - (-1);
                    document.getElementById('JoursCongesPris').value = document.getElementById('JoursCongesPris').value - 1;
                };
                document.getElementById('JoursMaladiePris').value = document.getElementById('JoursMaladiePris').value - (-1);
                document.getElementById('Heures[' + pNum + ']').value = pValue;
                document.getElementById('TotalHeuresMaladiePrises').value = document.getElementById('TotalHeuresMaladiePrises').value - (-document.getElementById('Heures[' + pNum + ']').value);
                document.getElementById('Heures[' + pNum + ']').setAttribute('readonly', 'true');
                document.getElementById('Heures[' + pNum + ']').setAttribute('style', 'text-align:center; background-color:#FD6C9E;');
                calculTotal(pNum);
            };
            if (document.getElementById('Maladie[' + pNum + ']').checked == false) {
                document.getElementById('Heures[' + pNum + ']').value = pValue;
                document.getElementById('TotalHeuresMaladiePrises').value = document.getElementById('TotalHeuresMaladiePrises').value - document.getElementById('Heures[' + pNum + ']').value;
                document.getElementById('JoursMaladiePris').value = document.getElementById('JoursMaladiePris').value - 1;
                document.getElementById('Heures[' + pNum + ']').removeAttribute('readonly');
                document.getElementById('Heures[' + pNum + ']').setAttribute('style', 'text-align:center; background-color:#FFFFFF;');
                calculTotal(pNum);
            };
        }

    </script>
</body>
</html>

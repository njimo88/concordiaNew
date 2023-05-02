@extends('layouts.template')

@section('content')
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
    0 => 'Janvier',
    1 => 'Février',
    2 => 'Mars',
    3 => 'Avril',
    4 => 'Mai',
    5 => 'Juin',
    6 => 'Juillet',
    7 => 'Août',
    8 => 'Septembre',
    9 => 'Octobre',
    10 => 'Novembre',
    11 => 'Décembre',
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
<main class="main" id="main">
<form action="" method="post" id="declaration-form">
	@csrf
	<input type="hidden" name="mois" value="{{ $mois }}">
	<input type="hidden" name="annee" value="{{ $annee }}">
	<input type="hidden" name="user_id" value="{{ $pro->id_user }}">
    <input type="hidden" name="declaration_id" value="{{ $declaration->id }}">

	<div align="center">
		<table border="5px" width="80%">
			<tbody align="left">
				<tr>
					<td>
						<table align="center">
							<tbody>
								<div class="container">
                                    <div class="row">
                                        <div class="col-12 text-center my-3">
                                            <span class="validation-text">Validez-vous cette déclaration d'heures pour {{ $monthNames[$mois] }} {{ $annee }} ?</span>
                                        </div>
                                        <div class="col-12 text-center">
                                            <button class="btn btn-success btn-custom" id="valider-btn" type="button">Valider</button>
                                            <button class="btn btn-danger btn-custom" id="refuser-btn" type="button">Refuser</button>
                                            <button class="btn btn-info btn-custom" id="enregistrer-btn" type="button">Enregistrer</button>
                                        </div>
                                    </div>
                                </div>
							</tbody>
						</table>
						<hr>

						<b><h1>{{ $pro->firstname }} {{ $pro->lastname }} - Mois de {{ $monthNames[$mois] }} {{ $annee }}</h1>
						<h3>Saison {{ $pro->Saison }}-{{ $pro->Saison+1 }} : Du 1er Août {{ $pro->Saison }} au 31 Juillet {{ $pro->Saison+1 }}</h3></b>
						<p>Cumul période jusqu'au 1er {{ $monthNames[$mois] }} {{ $annee }} (inclus) : {{ $pro->OldHeuresRealisees }} heures réalisées / {{ $TotalMensueldu }} heures dues</p>
						<hr>
						<table border="0">
							<tbody>
								<tr>
									<td>
										<p>Mois de <b>{{ $monthNames[$mois] }} {{ $annee }}</b> - </td>
									<td>
										<input type="text" style="text-align:center; width: 50px;" id="HeuresTotal" value="{{ isset($declaration) ? $declaration->heures_realisees : $TotalHeures }}" name="HeuresTotal" size="5" readonly>
									</td>
									<td>
										<p> Heures réalisées</p>
									</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>
										<p> Jours de Congés pris</td>
									<td>
										<input type="text" style="text-align:center;" id="JoursCongesPris" name="JoursCongesPris" value="{{ isset($declaration) ? $declaration->jours_conges : $TotalCongespris }}" size="2" readonly>
									</td>
									<td>
										<p> jours (soit </p>
									</td>
									<td>
										<input type="text" style="text-align:center;" id="JoursCongesRestant" name="JoursCongesRestant" value="{{ isset($declaration) ? $TotalConges-$declaration->jours_conges : $TotalConges }}" size="2" readonly>
									</td>
									<td>
										<p> jours restant)</p>
									</td>
								</tr>
								<tr>
									<td>
										<p> Jours de Maladie pris</td>
									<td>
										<input type="text" style="text-align:center;" id="JoursMaladiePris" name="JoursMaladiePris" value="{{ isset($declaration) ? $declaration->jours_maladie : $TotalMaladiepris }}" size="2" readonly>
									</td>
									<td>
										<p> jours (soit </p>
									</td>
									<td>
										<input type="text" style="text-align:center;" id="TotalHeuresMaladiePrises" name="TotalHeuresMaladiePrises" value="{{ isset($declaration) ? $TotalHeuresMaladieprises : $TotalHeuresMaladieprises }}" size="2" readonly>
									</td>
									<td>
										<p> heures)</p>
									</td>
								</tr>
							</tbody>
									</table>
									<hr>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th class="col-2">Date</th>
												<th class="col-1">H Théo.</th>
												<th class="col-1">H Réal.</th>
												<th class="col-1">Congés</th>
												<th class="col-1">Maladie</th>
												<th class="col-6">Remarque</th>
											</tr>
										</thead>
										<tbody>
                                            @php
                                                $daysInMonth = date('t', mktime(0, 0, 0, $mois+1, 1, $annee));
                                            @endphp
                                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                                @php
                                                    $date = mktime(0, 0, 0, $mois+1, $day, $annee);
                                                    $weekday = date('l', $date);
                                                    $formattedDate = $dayNames[$weekday] . ' ' . $day . ' ' . $monthNames[$mois] . ' ' . $annee;
                                                    $color = ColorFont($date);
                                                    $weekdayValue = $dayNames[$weekday];
                                                    $weekdayValue = $pro->$weekdayValue;
                                        
                                                    // Utiliser details_admin si disponible, sinon utiliser details
                                                    $detail_data = $declaration->details_admin ? json_decode($declaration->details_admin, true) : $declaration->details;
                                                    $details = isset($declaration) ? $detail_data[$day - 1] : null;
                                                @endphp
                                                <tr style="background-color: {{ $color }};">
                                                    <td class="col-2">{{ $formattedDate }}</td>
                                                    <td style="text-align: center !important" class="col-1 align-middle text-center">{{ $weekdayValue }}</td>
                                                    <td class="col-1">
                                                        <input type="text" class="form-control text-center bg-light" size="3" id="Heures[{{ $day }}]" name="Heures[{{ $day }}]" onkeyup="calculTotal({{ $day }})"
                                                        value="{{ $details ? $details['heures'] : $weekdayValue }}" />
                                                    </td>
                                                    <td style="text-align: center !important" class="col-1 align-middle text-center">
                                                        <input type="checkbox" label="Conges" name="Conges[{{ $day }}]" id="Conges[{{ $day }}]" onchange="bloqueHeuresConges({{ $day }}, {{ $weekdayValue }})" class="form-check-input"
                                                        @if ($details && $details['conges']) checked @endif />
                                                    </td>
                                                    <td  style="text-align: center !important" class="col-1 align-middle text-center">
                                                        <input type="checkbox" label="Maladie" name="Maladie[{{ $day }}]" id="Maladie[{{ $day }}]" onchange="bloqueHeuresMaladie({{ $day }}, {{ $weekdayValue }})" class="form-check-input"
                                                        @if ($details && $details['maladie']) checked @endif />
                                                    </td>
                                                    <td class="col-6" style="width: 60%;">
                                                        <input type="text" class="form-control" style="background-color: ROW_BACKGROUND_COLOR; width: 100%;" name="Remarque[{{ $day }}]"
                                                        value="{{ $details ? $details['remarque'] : '' }}" />
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
		</main>	
        <script language="javascript">
            function calculTotal(pNum) {
                var nbLignes = document.getElementById("tab").rows.length;
                document.getElementById('HeuresTotal').value = 0;
                for (i = 1; i < nbLignes; i++) {
                    var ChaineReplace = document.getElementById('Heures[' + i + ']').value;
                    document.getElementById('HeuresTotal').value = parseFloat(document.getElementById('HeuresTotal').value) + parseFloat(ChaineReplace.replace(',', '.'));
                };
            }
        
            function modifyTotal(pNum) {
                var nbLignes = document.getElementById("tab").rows.length;
        
                var int = 0;
                document.getElementById('HeuresTotal').value = 0;
                for (i = 1; i < nbLignes; i++) {
                    var ChaineReplace = document.getElementById('Heures[' + i + ']').value;
                    int = int + parseFloat(ChaineReplace.replace(',', '.'));
                    //document.getElementById('totalheuredynamique').innerHTML = parseFloat(document.getElementById('HeuresTotal').value) + parseFloat(ChaineReplace.replace(',','.'));
                    document.getElementById('HeuresTotal').value = int;
                    document.getElementById('totalheuredynamique').innerHTML = int;
                };
            }
        
        
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

document.getElementById('enregistrer-btn').addEventListener('click', engistrerDeclaration);

async function engistrerDeclaration() {
	const annee = @json($annee);
	const mois = @json($mois);

  const details = [];
  const daysInMonth = new Date(annee, mois, 0).getDate();

  for (let day = 1; day <= daysInMonth; day++) {
    const heures = document.getElementById(`Heures[${day}]`).value;
    const conges = document.getElementById(`Conges[${day}]`).checked;
    const maladie = document.getElementById(`Maladie[${day}]`).checked;
    const remarque = document.querySelector(`[name="Remarque[${day}]"]`).value;

    details.push({
      day,
      heures,
      conges,
      maladie,
      remarque,
    });
  }

  const response = await fetch('/engistrerDeclaration', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
    },
    body: JSON.stringify({
      user_id: document.querySelector('input[name="user_id"]').value,
      annee: document.querySelector('input[name="annee"]').value,
      mois: document.querySelector('input[name="mois"]').value,
      details,
    }),
  });
  if (response.ok) {
    alert('Declaration saved successfully!');
	location.reload();
  } else {
    alert('An error occurred while saving the declaration.');
  }
}

document.getElementById('refuser-btn').addEventListener('click', function() {
    if (confirm('Êtes-vous sûr de vouloir refuser cette déclaration ?')) {
        let form = document.getElementById('declaration-form');
        form.action = '/refuser-declaration'; 
        form.submit();
    }
});

document.getElementById('valider-btn').addEventListener('click', function() {
    if (confirm('Êtes-vous sûr de vouloir valider cette déclaration ?')) {
        let form = document.getElementById('declaration-form');
        form.action = '/valider-declaration'; 
        form.submit();
    }
});
        </script>
@endsection
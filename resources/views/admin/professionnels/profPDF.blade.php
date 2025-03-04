<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
        .btn-custom {
            margin: 0 10px;
        }

        .validation-text {
            font-weight: bold;
            color: #333;
            font-size: 20px
        }

        table {
            width: 100%;
        }

        .date-col {
            width: 33%;
        }

        .theo-col,
        .real-col {
            width: 8%;
        }

        .conges-col,
        .maladie-col {
            width: 3%;
        }

        .remarque-col {
            width: 50%;
        }

        body {
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .table td,
        .table th,
        .small-text {
            font-size: 12px;
            margin: 0%;
        }

        /* Supprime l'espacement autour des cellules de tableau */
        .table tr,
        .table td,
        .table th {
            padding: 0;
            border-spacing: 0;
            border-collapse: collapse;
            line-height: 0.2;
            /* réduit l'espace entre les lignes */
        }

        /* Réduire l'espace autour des éléments <p> et <b> */
        .compact p,
        .compact b {
            margin: 0;
        }

        table {
            margin-left: 0;
            /* Aligner la table à gauche */
        }

        .table thead tr th {
            padding-left: 5px;
            padding-right: 5px;
        }

        .table tbody tr td {
            padding: 0px;
            margin: 0px;
            height: 17.5px;
            padding-top: 5px;
            padding-left: 5px;
        }

        tbody:before {
            content: "@";
            display: block;
            line-height: 2.5px;
            text-indent: -99999px;
        }

        .frcontent {
            font-size: 12px;
            line-height: 0.2 !important;
        }

        input[type="checkbox"] {
            position: absolute;
            padding: 0px;
            margin: 0px;
            transform: translate(85%, -25%)
        }

        input[type='checkbox']:checked:after {
            background-color: red;
        }

        #bandeau {
            width: 110%;
            transform: translate(-4%);
            margin-bottom: 0px;
        }

        @page {
            margin: 0px;
        }

        body {
            margin: 15px 30px;
        }

        hr {
            height: 3px;
            background-color: black;
            border: 0;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    @php

        require_once app_path() . '/ProfessionelFonction.php';
        $date = '2023-04-22';
        $timestamp = strtotime($date);
        $couleur = ColorFont($timestamp);

        $HeuresTheoriques = [
            $pro->lundi,
            $pro->mardi,
            $pro->mercredi,
            $pro->jeudi,
            $pro->vendredi,
            $pro->samedi,
            $pro->dimanche,
        ];

        $mois = $pro->LastDeclarationMonth;
        $annee = $pro->LastDeclarationYear;

        $VolumeMensueldu = round(($pro->VolumeHebdo * 52) / 12, 2);
        if (($pro->LastDeclarationMonth + 1) % 12 >= 8) {
            $NbMoisPeriode = (($pro->LastDeclarationMonth + 1) % 12) - 8;
        } else {
            $NbMoisPeriode = (($pro->LastDeclarationMonth + 1) % 12) + 4;
        }
        $TotalMensueldu = $VolumeMensueldu * $NbMoisPeriode;

        if (!function_exists('daysInMonth')) {
            function daysInMonth($month, $year)
            {
                return date('t', mktime(0, 0, 0, $month + 1, 1, $year));
            }
        }

        if (!function_exists('sumWeekdayValues')) {
            // Returns the sum of all weekday values in a given month
            function sumWeekdayValues($month, $year, $pro)
            {
                $daysInMonth = daysInMonth($month, $year);
                $totalSum = 0;

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $weekday = date('l', mktime(0, 0, 0, $month + 1, $day, $year));

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
            'Sunday' => 'Dimanche',
        ];

        $TotalHeures = sumWeekdayValues($mois, $annee, $pro);
        $TotalCongespris = 0;
        $TotalConges = $pro->SoldeConges + 2.5;
        $TotalHeuresMaladieprises = 0;
        $TotalMaladiepris = 0;
    @endphp

    <img id="bandeau" src="assets/images/unnamed1.jpg" alt="Bandeau">

    <b>
        <h2>{{ $pro->firstname }} {{ $pro->lastname }} - Mois de {{ $monthNames[$mois] }} {{ $annee }}</h2>
    </b>

    <div class="frcontent">
        <p>Mois de <b>{{ $monthNames[$mois] }} {{ $annee }}</b> -
            {{ isset($declaration) ? $declaration->heures_realisees : $TotalHeures }} Heures réalisées</p>
        <p>Jours de Congés pris {{ isset($declaration) ? $declaration->jours_conges : $TotalCongespris }} jours (soit
            {{ isset($declaration) ? $TotalConges - $declaration->jours_conges : $TotalConges }} jours restant)</p>
        <p>Jours de Maladie pris {{ isset($declaration) ? $declaration->jours_maladie : $TotalMaladiepris }} jours (soit
            {{ isset($declaration) ? $TotalHeuresMaladieprises : $TotalHeuresMaladieprises }} heures)</p>
    </div>

    <hr>
    <p style="line-height: 0.2 !important; margin-top : 20px !important" class="small-text ">Saison
        {{ $pro->Saison }}-{{ $pro->Saison + 1 }} : Du 1er Août {{ $pro->Saison }} au 31 Juillet
        {{ $pro->Saison + 1 }}</p>
    <p class="small-text ">Cumul période jusqu'au 1er {{ $monthNames[$mois] }} {{ $annee }} (inclus) :
        {{ $pro->OldHeuresRealisees }} heures réalisées / {{ $TotalMensueldu }} heures dues</p>
    <hr>

    <br>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="small-margin" style="text-align: left;">Date</th>
                <th class="small-margin" style="text-align: center;">H Théo</th>
                <th class="small-margin" style="text-align: center;">H Réal</th>
                <th class="small-margin" style="text-align: center;">Congés</th>
                <th class="small-margin" style="text-align: center;">Maladie</th>
                <th class="small-margin" style="text-align: left;">Remarque</th>
            </tr>
        </thead>
        <tbody>
            @php
                $daysInMonth = date('t', mktime(0, 0, 0, $mois + 1, 1, $annee));
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
                    $detail_data = $declaration->details_admin
                        ? json_decode($declaration->details_admin, true)
                        : $declaration->details;
                    $details =
                        isset($declaration) && array_key_exists($day - 1, $detail_data) ? $detail_data[$day - 1] : null;
                @endphp
                <tr style="background-color: {{ $color }};" valign="middle">
                    <td style="text-align: left;" class="date-col">
                        {{ $formattedDate }}
                    </td>
                    <td style="text-align: center;" class="theo-col">
                        {{ $weekdayValue }}
                    </td>
                    <td class="real-col"
                        style="text-align: center; 
                    background-color: 
                    @if ($details && $details['maladie']) #FD6C9E;
                    @elseif ($details && $details['conges']) #77B5FE; @endif
                    ">
                        {{ $details ? $details['heures'] : $weekdayValue }}
                    </td>
                    <td style="text-align: center; position: relative;" class="conges-col">
                        <input type="checkbox" @if ($details && $details['conges']) checked @endif>
                    </td>
                    <td style="text-align: center; position: relative;" class="maladie-col">
                        <input type="checkbox" @if ($details && $details['maladie']) checked @endif>
                    </td>
                    <td style="text-align: left;" class="remarque-col">
                        {{ $details ? $details['remarque'] : '' }}
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
</body>

</html>

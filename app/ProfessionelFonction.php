<?php

use App\Models\Vacance;



function VacancesScolaires($datedujour)
{
    $retour = FALSE;

    // Récupérez les vacances à partir de la table vacances
    $vacances = Vacance::all();

    // Parcourez les vacances et vérifiez si la date donnée se trouve dans l'une des périodes de vacances
    foreach ($vacances as $vacance) {
        $toussaintDebut = strtotime($vacance->toussaintDebut);
        $toussaintFin = strtotime($vacance->toussaintFin);
        $noelDebut = strtotime($vacance->noelDebut);
        $noelFin = strtotime($vacance->noelFin);
        $hiverDebut = strtotime($vacance->hiverDebut);
        $hiverFin = strtotime($vacance->hiverFin);
        $printempsDebut = strtotime($vacance->printempsDebut);
        $printempsFin = strtotime($vacance->printempsFin);
        $eteDebut = strtotime($vacance->eteDebut);
        $eteFin = strtotime($vacance->eteFin);

        if (($datedujour >= $toussaintDebut) && ($datedujour <= $toussaintFin)) $retour = TRUE;
        if (($datedujour >= $noelDebut) && ($datedujour <= $noelFin)) $retour = TRUE;
        if (($datedujour >= $hiverDebut) && ($datedujour <= $hiverFin)) $retour = TRUE;
        if (($datedujour >= $printempsDebut) && ($datedujour <= $printempsFin)) $retour = TRUE;
        if (($datedujour >= $eteDebut) && ($datedujour <= $eteFin)) $retour = TRUE;
    }

    return $retour;
}


function JourFerie($datedujour)
{
	$retour = FALSE;

	$year = date("Y", $datedujour);

	$easterDate  = easter_date($year);
	$easterDay   = date('j', $easterDate);
	$easterMonth = date('n', $easterDate);
	$easterYear   = date('Y', $easterDate);

	// Dates Fixes
	if ($datedujour == mktime(0, 0, 0, 1,  1,  $year)) $retour = TRUE;  // 1er janvier
	if ($datedujour == mktime(0, 0, 0, 5,  1,  $year)) $retour = TRUE;	// Fête du travail
	if ($datedujour == mktime(0, 0, 0, 5,  8,  $year)) $retour = TRUE;  // Victoire des alliés
	if ($datedujour == mktime(0, 0, 0, 7,  14, $year)) $retour = TRUE;  // Fête nationale
	if ($datedujour == mktime(0, 0, 0, 8,  15, $year)) $retour = TRUE; // Assomption
	if ($datedujour == mktime(0, 0, 0, 11,  1,  $year)) $retour = TRUE; // Toussaint
	if ($datedujour == mktime(0, 0, 0, 11,  11,  $year)) $retour = TRUE; // Armistice
	if ($datedujour == mktime(0, 0, 0, 12,  25,  $year)) $retour = TRUE; // Noel
	if ($datedujour == mktime(0, 0, 0, 12,  26,  $year)) $retour = TRUE; // St Etienne

	// Dates variables
	if ($datedujour == mktime(0, 0, 0, $easterMonth, $easterDay - 2,  $easterYear)) $retour = TRUE;
	if ($datedujour == mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear)) $retour = TRUE;
	if ($datedujour == mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear)) $retour = TRUE;
	if ($datedujour == mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear)) $retour = TRUE;

	return $retour;
};


function ColorFont($datedujour)
{
	$colordufont = "#FFFFFF";
	$numdujour = date("w", $datedujour);
	if (VacancesScolaires($datedujour)) $colordufont = "#AFAFAF";
	if (($numdujour == 0) || ($numdujour == 6)) $colordufont = "#FFFF00";
	if (JourFerie($datedujour)) $colordufont = "#FF4500";

	return $colordufont;
};
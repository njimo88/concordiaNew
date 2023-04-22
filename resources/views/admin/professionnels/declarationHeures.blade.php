<style type="text/css">
	table.border_1_solid_black td {
		border: 1px solid black;
	}

	.col_1 {
		position: relative;
		line-height: 15px;
		width: 220px
	}

	.col_2 {
		position: relative;
		line-height: 15px;
		width: 80px;
		text-align: center;
	}

	.col_3 {
		position: relative;
		line-height: 15px;
		width: 950px
	}
</style>

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
</script>

<?php

function AfficheMois ( $mois , $annee , $user_id )
{	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "test2";
	
	$sql = new mysqli($servername, $username, $password, $dbname);

	if ($sql->connect_errno) {
		printf("Échec de la connexion : %s\n", $sql->connect_error);
		exit();
	}

	if ($result = $sql->query("SELECT * FROM users_professionals WHERE id_user='$user_id'")) {
		foreach ($result as $ligne) {
			$VariableBDD[0] = $ligne["id_user"];
			$VariableBDD[1] = utf8_decode($ligne["lastname"]);
			$VariableBDD[2] = utf8_decode($ligne["firstname"]);
			$VariableBDD[3] = $ligne["VolumeHebdo"];
			$VariableBDD[4] = $ligne["Lundi"];
			$VariableBDD[5] = $ligne["Mardi"];
			$VariableBDD[6] = $ligne["Mercredi"];
			$VariableBDD[7] = $ligne["Jeudi"];
			$VariableBDD[8] = $ligne["Vendredi"];
			$VariableBDD[9] = $ligne["Samedi"];
			$VariableBDD[10] = $ligne["Dimanche"];
			$VariableBDD[11] = $ligne["OldHeuresRealisees"];
			$VariableBDD[12] = $ligne["SoldeConges"];
			$VariableBDD[13] = $ligne["matricule"];
			$VariableBDD[14] = $ligne["email"];
		}

		/* Libération du jeu de résultats */
		$result->close();
	}


	$resultatrenvoye = '';

	$NomEmploye = $VariableBDD[1];
	$PrenomEmploye = $VariableBDD[2];
	$Matricule = $VariableBDD[13];
	$SoldeConges = $VariableBDD[12];
	$EmailEmploye = $VariableBDD[14];
	$saison = getSeason($user_id);
	$saisonplusun = $saison + 1;
	$HeuresTheoriques = array($VariableBDD[4], $VariableBDD[5], $VariableBDD[6], $VariableBDD[7], $VariableBDD[8], $VariableBDD[9], $VariableBDD[10]);
	$OldHeuresRealisees = $VariableBDD[11];
	$VolumeHebdo = $VariableBDD[3];

	$JourSemaine = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
	$Moislettres = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

	// Ajout des jours pour 1er jour
	$firstjourperiode = mktime(0, 0, 0, $mois, 1, $annee);
	$ajout = date("w", $firstjourperiode);

	$maxdaymois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
	$TotalHeures = 0;

	for ($i = 1; $i <= $maxdaymois; $i++) {
		$TotalHeures = $TotalHeures + floatval($HeuresTheoriques[($i + $ajout + 5) % 7]);
		$NbHextrait[$i] = floatval($HeuresTheoriques[($i + $ajout + 5) % 7]);
		$NbCongesextrait[$i] = "FALSE";
		$NbMaladieextrait[$i] = "FALSE";
	};

	$TotalConges = $SoldeConges + 2.5;
	$TotalCongespris = 0;
	$TotalMaladiepris = 0;
	$TotalHeuresMaladieprises = 0;
	$VolumeMensueldu = round($VolumeHebdo * 52 / 12, 2);
	if ($mois >= 8) {
		$NbMoisPeriode = $mois - 8;
	} else {
		$NbMoisPeriode = $mois + 4;
	}
	$TotalMensueldu = $VolumeMensueldu * $NbMoisPeriode;

		$fichier_csv = public_path('employee_documents/1-sauvegarde/' . $user_id . '-' . $annee . '-' . $mois . '.csv');
	$fichier_demande_csv = public_path('employee_documents/2-demande/' . $user_id . '-' . $annee . '-' . $mois . '.csv');

	if (file_exists($fichier_demande_csv)) {
		$current = file_get_contents($fichier_demande_csv);
		$donneesextraites = explode(';', $current);
		dd ($current);

		$TotalHeures = $donneesextraites[0];
		$TotalConges = $donneesextraites[2];
		$TotalCongespris = $donneesextraites[1];
		$TotalMaladiepris = $donneesextraites[3];
		$TotalHeuresMaladieprises = $donneesextraites[4];
		for ($i = 1; $i <= $maxdaymois; $i++) {
			$NbHextrait[$i] = $donneesextraites[6 + 4 * $i];
			$NbCongesextrait[$i] = $donneesextraites[7 + 4 * $i];
			$NbMaladieextrait[$i] = $donneesextraites[8 + 4 * $i];
			$NbRemextrait[$i] = $donneesextraites[9 + 4 * $i];
		};
	};

	$demande_on = '';
	$demande_on_1 = '';

	if (file_exists($fichier_demande_csv)) {
		$demande_on = ' readonly ';
		$demande_on_1 = ' hidden="hidden" ';
	};

	$resultatrenvoye .= '<form action="' . route('Professionnels.declaration', $user_id) . '" method="post">';
				$resultatrenvoye .= csrf_field();

	$resultatrenvoye .= '<input type="hidden" name="mois" value="' . $mois . '">';
	$resultatrenvoye .= '<input type="hidden" name="annee" value="' . $annee . '">';
	$resultatrenvoye .= '<input type="hidden" name="user_id" value="' . $user_id . '">';
	//$resultatrenvoye .= '<input type="hidden" name="idemploye" value="'.$idemploye.'">' ;
	$resultatrenvoye .= '<input type="hidden" name="email" value="' . $EmailEmploye . '">';

	$fichier_csv = '../Gym-Concordia/Documents/Employes/3-validation/' . $user_id . '-' . $annee . '-' . $mois . '.csv';

	$resultatrenvoye .= '<div align="center"><table border="5px" width="80%><tbody align="left"><tr><td>';
	$resultatrenvoye .= '<table align="center">';

	if (!file_exists($fichier_csv)) {
		$resultatrenvoye .= '<tbody><tr><td><input style="background-color: #ffc107; color: black" type="submit" name="Statut" value="Sauvegarder">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input style="background-color: red; color: black" type="submit" name="Statut" value="Reinitialiser">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="background-color: #28a745; color: black" type="submit" name="Statut" value="Soumettre">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}

	// $resultatrenvoye .= '<input type="submit" name="Statut" value="Annule Demande">' ;
	$resultatrenvoye .= '</td></tr></tbody></table><hr>';

	$resultatrenvoye .= '<h1>' . $NomEmploye . ' ' . $PrenomEmploye . ' - Mois de ' . $Moislettres[$mois] . ' ' . $annee . '</h1>';
	$resultatrenvoye .= '<h3>Saison ' . $saison . '-' . $saisonplusun . ' : Du 1er Août ' . $saison . ' au 31 Juillet ' . $saisonplusun . '</h3>';
	$resultatrenvoye .= '<p>Cumul période jusqu\'au 1er ' . $Moislettres[$mois] . ' ' . $annee . ' (inclus) : ' . $OldHeuresRealisees . ' heures réalisées / ' . $TotalMensueldu . ' heures dues</p><hr>';
	$resultatrenvoye .= '<table border="0"><tbody><tr><td><p>Mois de <b>' . $Moislettres[$mois] . ' ' . $annee . '</b> - </td><td> <input type="text" style="text-align:center; width: 50px;" id="HeuresTotal" value="' . $TotalHeures . '" name="HeuresTotal" size="5" readonly></td><td><p> Heures réalisées</p></td><td></td><td></td></tr>';
	$resultatrenvoye .= '<tr><td><p> Jours de Congés pris</td><td> <input type="text" style="text-align:center;" id="JoursCongesPris" name="JoursCongesPris" value="' . $TotalCongespris . '" size="2" readonly></td><td><p> jours (soit </p></td><td><input type="text" style="text-align:center;" id="JoursCongesRestant" name="JoursCongesRestant" value="' . $TotalConges . '" size="2" readonly></td><td><p> jours restant)</p></td></tr><tr><td><p> Jours de Maladie pris</td><td> <input type="text" style="text-align:center;" id="JoursMaladiePris" name="JoursMaladiePris" value="' . $TotalMaladiepris . '" size="2" readonly></td><td><p> jours (soit </p></td><td><input type="text" style="text-align:center;" id="TotalHeuresMaladiePrises" name="TotalHeuresMaladiePrises" value="' . $TotalHeuresMaladieprises . '" size="2" readonly></td><td><p> heures)</p></td></tr></tbody></table><hr>';

	$resultatrenvoye .= '<table id="tab" border="1"><body>';
	$resultatrenvoye .= '<tr><th class="col_1">Date</th><th class="col_2">H Théo.</th><th class="col_2">H Réal.</th><th class="col_2">Congés</th><th class="col_2">Maladie</th><th class="col_3">Remarque</th></tr>';

	for ($i = 1; $i <= $maxdaymois; $i++) {
		$cachecase = 'type="checkbox"';
		if (($i + $ajout + 5) % 7 == 6) $cachecase = 'type="hidden" value="0"';
		if (JourFerie(mktime(0, 0, 0, $mois, $i, $annee))) $cachecase = 'type="hidden" value="0" ';

		$readonly = '';
		$colorchecked = '#FFFFFF';
		if (JourFerie(mktime(0, 0, 0, $mois, $i, $annee))) {
			$readonly = ' readonly ';
			$colorchecked = '#FF4500';
		};

		$congeschecked = '';
		if ($NbCongesextrait[$i] == "TRUE") {
			$congeschecked = ' checked="checked" ';
			$colorchecked = '#77B5FE';
		};
		$maladiechecked = '';
		if ($NbMaladieextrait[$i] == "TRUE") {
			$maladiechecked = ' checked="checked" ';
			$colorchecked = '#FD6C9E';
		};

		if (!isset($NbRemextrait[$i])) {
			$NbRemextrait[$i] = "";
		}

		$resultatrenvoye .= '
					<tr style="background-color :' . ColorFont(mktime(0, 0, 0, $mois, $i, $annee)) . '">
					<td class="col_1">' . $JourSemaine[($i + $ajout + 5) % 7] . ' ' . $i . ' ' . $Moislettres[$mois] . ' ' . $annee . '</td>
					<td class="col_2">' . floatval($HeuresTheoriques[($i + $ajout + 5) % 7]) . '</td><td class="col_2"><input ' . $readonly . ' type="text" style="text-align:center; background-color:' . $colorchecked . ';"  size="3" value="' . $NbHextrait[$i] . '" id="Heures[' . $i . ']" name="Heures[' . $i . ']" onkeyup="calculTotal(' . $i . ')" /></td>
					<td class="col_2"><input label="Conges" name="Conges[' . $i . ']" id="Conges[' . $i . ']" onchange="bloqueHeuresConges(' . $i . ',' . floatval($HeuresTheoriques[($i + $ajout + 5) % 7]) . ')" ' . $cachecase . $congeschecked . '></td>
					<td class="col_2"><input type="checkbox" label="Maladie" name="Maladie[' . $i . ']" id="Maladie[' . $i . ']" onchange="bloqueHeuresMaladie(' . $i . ',' . floatval($HeuresTheoriques[($i + $ajout + 5) % 7]) . ')" ' . $maladiechecked . '></td>
					<td class="col_3"><input type="text"  size="100%" style="background-color:' . ColorFont(mktime(0, 0, 0, $mois, $i, $annee)) . '" name="Remarque[' . $i . ']" value="' . $NbRemextrait[$i] . '"></td>
					</tr>';
	};

	$resultatrenvoye .= '</tbody></table>';
	$resultatrenvoye .= '</td></tr></tbody></table></div></form>';

	return $resultatrenvoye;

	// Close the database connection
	$sql->close();
} ;

function AfficheMois_valide($mois, $annee, $user_id, $valeurpdf)
{
    $user = DB::table('users_professionals')->where('id_user', $user_id)->first();

if ($user) {
$VariableBDD[0] = $user->id_user;
$VariableBDD[1] = utf8_decode($user->lastname);
$VariableBDD[2] = utf8_decode($user->firstname);
$VariableBDD[3] = $user->VolumeHebdo;
$VariableBDD[4] = $user->Lundi;
$VariableBDD[5] = $user->Mardi;
$VariableBDD[6] = $user->Mercredi;
$VariableBDD[7] = $user->Jeudi;
$VariableBDD[8] = $user->Vendredi;
$VariableBDD[9] = $user->Samedi;
$VariableBDD[10] = $user->Dimanche;
$VariableBDD[11] = $user->OldHeuresRealisees;
$VariableBDD[12] = $user->SoldeConges;
$VariableBDD[13] = $user->matricule;
$VariableBDD[14] = $user->email;
$resultatrenvoye = '';
$NomEmploye = $VariableBDD[1];
$PrenomEmploye = $VariableBDD[2];
$Matricule = $VariableBDD[13];
$SoldeConges = $VariableBDD[12];
$EmailEmploye = $VariableBDD[14];
$saison = getSeason($user_id);
$saisonplusun = $saison + 1;
$HeuresTheoriques = array($VariableBDD[4], $VariableBDD[5], $VariableBDD[6], $VariableBDD[7], $VariableBDD[8], $VariableBDD[9], $VariableBDD[10]);
$OldHeuresRealisees = $VariableBDD[11];
$VolumeHebdo = $VariableBDD[3];
} else {
// User not found, handle error accordingly
}
	

	$JourSemaine = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
	$Moislettres = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

	// Ajout des jours pour 1er jour
	$firstjourperiode = mktime(0, 0, 0, $mois, 1, $annee);
	$ajout = date("w", $firstjourperiode);

	$maxdaymois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
	$TotalHeures = 0;


	$TotalConges = $SoldeConges + 2.5;
	$TotalCongespris = 0;
	$TotalMaladiepris = 0;
	$TotalHeuresMaladieprises = 0;
	$VolumeMensueldu = round($VolumeHebdo * 52 / 12, 2);
	if ($mois >= 8) {
		$NbMoisPeriode = $mois - 8;
	} else {
		$NbMoisPeriode = $mois + 4;
	}
	$TotalMensueldu = $VolumeMensueldu * $NbMoisPeriode;

	$fichier_csv = public_path('employee_documents/1-sauvegarde/' . $user_id . '-' . $annee . '-' . $mois . '.csv');
	$fichier_demande_csv = public_path('employee_documents/3-validation/' . $user_id . '-' . $annee . '-' . $mois . '.csv');


	if (file_exists($fichier_demande_csv)) {
		$current = file_get_contents($fichier_demande_csv);
		$donneesextraites = explode(';', $current);

		$TotalHeures = $donneesextraites[0];
		$TotalConges = $donneesextraites[2];
		$TotalCongespris = $donneesextraites[1];
		$TotalMaladiepris = $donneesextraites[3];
		$TotalHeuresMaladieprises = $donneesextraites[4];
		for ($i = 1; $i <= $maxdaymois; $i++) {
			$NbHextrait[$i] = $donneesextraites[6 + 4 * $i];
			$NbCongesextrait[$i] = $donneesextraites[7 + 4 * $i];
			$NbMaladieextrait[$i] = $donneesextraites[8 + 4 * $i];
			$NbRemextrait[$i] = $donneesextraites[9 + 4 * $i];
		};
	};

	$demande_on = '';
	$demande_on_1 = '';

	if (file_exists($fichier_demande_csv)) {
		$demande_on = ' readonly ';
		$demande_on_1 = ' hidden="hidden" ';
	};

	$user = array(
		'Nom' => $NomEmploye,
		'Prenom' => $PrenomEmploye,
		'Email' => $EmailEmploye,
	);

	$resultatrenvoye .= '<form action="#" method="post">';

	$resultatrenvoye .= '<input type="hidden" name="mois" value="' . $mois . '">';
	$resultatrenvoye .= '<input type="hidden" name="annee" value="' . $annee . '">';
	$resultatrenvoye .= '<input type="hidden" name="user_id" value="' . $user_id . '">';
	$resultatrenvoye .= '<input type="hidden" name="user[0]" value="' . $user['Nom'] . '">';
	$resultatrenvoye .= '<input type="hidden" name="user[1]" value="' . $user['Prenom'] . '">';
	$resultatrenvoye .= '<input type="hidden" name="user[2]" value="' . $user['Email'] . '">

        <input type="hidden" style="text-align:center; width: 50px;" id="HeuresTotal" value="' . $TotalHeures . '" name="HeuresTotal" size="5" readonly>
        <input type="hidden" style="text-align:center;" id="JoursCongesPris" name="JoursCongesPris" value="' . $TotalCongespris . '" size="3" readonly>
        <input type="hidden" style="text-align:center;" id="JoursCongesRestant" name="JoursCongesRestant" value="' . $TotalConges . '" size="3" readonly>
        <input type="hidden" style="text-align:center;" id="JoursMaladiePris" name="JoursMaladiePris" value="' . $TotalMaladiepris . '" size="3" readonly>
        <input type="hidden" style="text-align:center;" id="TotalHeuresMaladiePrises" name="TotalHeuresMaladiePrises" value="' . $TotalHeuresMaladieprises . '" size="3" readonly>';

	$resultatrenvoye .= '<div align="center"><table border="5px" width="80%><tbody align="left"><tr><td>';
	$resultatrenvoye .= '<table align="center"><tbody><tr><td bgcolor="#FF0000" color="white"><h1>Validez-vous cette déclaration d\'heures pour ' . $Moislettres[$mois] . ' ' . $annee . ' ?&nbsp;</h1></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Statut" value="Valider">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Statut" value="Enregistrer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Statut" value="Refuser"></td></tr></tbody></table><hr>';

	$resultatrenvoye .= '<h1>' . $NomEmploye . ' ' . $PrenomEmploye . ' - Mois de ' . $Moislettres[$mois] . ' ' . $annee . '</h1><br>';
	$resultatrenvoye .= '<table><tbody><tr><td>Mois de <b>' . $Moislettres[$mois] . ' ' . $annee . '</b> - </td><td id="totalheuredynamique">' . $TotalHeures . '</td><td> Heures réalisées</td></tr>';
	$resultatrenvoye .= '<tr><td colspan="3"> Jours de Congés pris : ' . $TotalCongespris . ' jours (soit ' . $TotalConges . ' jours restant)</td></tr><tr><td colspan="3">Jours de Maladie pris : ' . $TotalMaladiepris . ' jours (soit ' . $TotalHeuresMaladieprises . ' heures)</p></td></tr></tbody></table><br><hr>';
	$resultatrenvoye .= '<p>Saison ' . $saison . '-' . $saisonplusun . ' : Du 1er Août ' . $saison . ' au 31 Juillet ' . $saisonplusun . '<br>';
	$resultatrenvoye .= 'Cumul période jusqu\'au 1er ' . $Moislettres[$mois] . ' ' . $annee . ' (non inclus) : ' . $OldHeuresRealisees . ' heures réalisées / ' . $TotalMensueldu . ' heures dues</p><hr>';

	$resultatrenvoye .= '<table id="tab" rowspacing=5><tbody>';
	$resultatrenvoye .= '<tr height="20px"><th class="col_1">Date</th><th class="col_2">H Théo.</th><th class="col_2">H Réal.</th><th class="col_2">Congés</th><th class="col_2">Maladie</th><th class="col_3">Remarque</th></tr>';
	for ($i = 1; $i <= $maxdaymois; $i++) {
		$cachecase = 'type="checkbox"';
		if (($i + $ajout + 5) % 7 == 6) $cachecase = 'type="hidden" value="0"';
		if (JourFerie(mktime(0, 0, 0, $mois, $i, $annee))) $cachecase = 'type="hidden" value="0" ';

		$readonly = '';
		$colorchecked = 'transparent';
		if (JourFerie(mktime(0, 0, 0, $mois, $i, $annee))) {
			$readonly = ' readonly ';
			$colorchecked = '#FF4500';
		};

		$cachecase = 'type="checkbox"';
		if (($i + $ajout + 5) % 7 == 6) $cachecase = 'type="hidden" value="0"';
		if (JourFerie(mktime(0, 0, 0, $mois, $i, $annee))) $cachecase = 'type="hidden" value="0" ';

		$readonly = '';
		$colorchecked = '#FFFFFF';
		if (JourFerie(mktime(0, 0, 0, $mois, $i, $annee))) {
			$readonly = ' readonly ';
			$colorchecked = '#FF4500';
		};

		$congeschecked = '';
		if ($NbCongesextrait[$i] == "TRUE") {
			$congeschecked = ' checked="checked" ';
			$colorchecked = '#77B5FE';
		};
		$maladiechecked = '';
		if ($NbMaladieextrait[$i] == "TRUE") {
			$maladiechecked = ' checked="checked" ';
			$colorchecked = '#FD6C9E';
		};



		$resultatrenvoye .= '
					<tr style="background-color :' . ColorFont(mktime(0, 0, 0, $mois, $i, $annee)) . '"  height="20px">
					<td class="col_1">' . $JourSemaine[($i + $ajout + 5) % 7] . ' ' . $i . ' ' . $Moislettres[$mois] . ' ' . $annee . '</td>
					<td class="col_2">' . floatval($HeuresTheoriques[($i + $ajout + 5) % 7]) . '</td>
					<td class="col_2"><input type="text" style="text-align:center; background-color:' . ColorFont(mktime(0, 0, 0, $mois, $i, $annee)) . '" size="3" value="' . $NbHextrait[$i] . '" id="Heures[' . $i . ']" name="Heures[' . $i . ']" onkeyup="modifyTotal(' . $i . ')"/></td>
          <td class="col_2"><input label="Conges" name="Conges[' . $i . ']" id="Conges[' . $i . ']" onchange="bloqueHeuresConges(' . $i . ',' . floatval($HeuresTheoriques[($i + $ajout + 5) % 7]) . ')" ' . $cachecase . $congeschecked . '></td>
					<td class="col_2"><input type="checkbox" label="Maladie" name="Maladie[' . $i . ']" id="Maladie[' . $i . ']" onchange="bloqueHeuresMaladie(' . $i . ',' . floatval($HeuresTheoriques[($i + $ajout + 5) % 7]) . ')" ' . $maladiechecked . '></td>
					<td class="col_3" bgcolor="transparent"><input type="text"  size="100%" style="background-color:' . ColorFont(mktime(0, 0, 0, $mois, $i, $annee)) . '" name="Remarque[' . $i . ']" value="' . $NbRemextrait[$i] . '"></td>
					</tr>';
	};


	$resultatrenvoye .= '</tbody></table>';
	$resultatrenvoye .= '</td></tr></tbody></table></div></form>';



	return $resultatrenvoye;

};

function templatePDF($mois, $annee, $user_id, $valeurpdf)
{
	$user = DB::table('users_professionals')->where('id_user', $user_id)->first();

if ($user) {
$VariableBDD[0] = $user->id_user;
$VariableBDD[1] = utf8_decode($user->lastname);
$VariableBDD[2] = utf8_decode($user->firstname);
$VariableBDD[3] = $user->VolumeHebdo;
$VariableBDD[4] = $user->Lundi;
$VariableBDD[5] = $user->Mardi;
$VariableBDD[6] = $user->Mercredi;
$VariableBDD[7] = $user->Jeudi;
$VariableBDD[8] = $user->Vendredi;
$VariableBDD[9] = $user->Samedi;
$VariableBDD[10] = $user->Dimanche;
$VariableBDD[11] = $user->OldHeuresRealisees;
$VariableBDD[12] = $user->SoldeConges;
$VariableBDD[13] = $user->matricule;
$VariableBDD[14] = $user->email;
$resultatrenvoye = '';
$NomEmploye = $VariableBDD[1];
$PrenomEmploye = $VariableBDD[2];
$Matricule = $VariableBDD[13];
$SoldeConges = $VariableBDD[12];
$EmailEmploye = $VariableBDD[14];
$saison = getSeason($user_id);
$saisonplusun = $saison + 1;
$HeuresTheoriques = array($VariableBDD[4], $VariableBDD[5], $VariableBDD[6], $VariableBDD[7], $VariableBDD[8], $VariableBDD[9], $VariableBDD[10]);
$OldHeuresRealisees = $VariableBDD[11];
$VolumeHebdo = $VariableBDD[3];
} else {
// User not found, handle error accordingly
}

	$JourSemaine = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
	$Moislettres = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

	// Ajout des jours pour 1er jour
	$firstjourperiode = mktime(0, 0, 0, $mois, 1, $annee);
	$ajout = date("w", $firstjourperiode);

	$maxdaymois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
	$TotalHeures = 0;

	
	$TotalConges = $SoldeConges + 2.5;
	$TotalCongespris = 0;
	$TotalMaladiepris = 0;
	$TotalHeuresMaladieprises = 0;
	$VolumeMensueldu = round($VolumeHebdo * 52 / 12, 2);
	if ($mois >= 8) {
		$NbMoisPeriode = $mois - 8;
	} else {
		$NbMoisPeriode = $mois + 4;
	}
	$TotalMensueldu = $VolumeMensueldu * $NbMoisPeriode;

	$fichier_csv = public_path('employee_documents/1-sauvegarde/' . $user_id . '-' . $annee . '-' . $mois . '.csv');
	$fichier_demande_csv = public_path('employee_documents/3-validation/' . $user_id . '-' . $annee . '-' . $mois . '.csv');


	if (file_exists($fichier_demande_csv)) {
		$current = file_get_contents($fichier_demande_csv);
		$donneesextraites = explode(';', $current);

		$TotalHeures = $donneesextraites[0];
		$TotalConges = $donneesextraites[2];
		$TotalCongespris = $donneesextraites[1];
		$TotalMaladiepris = $donneesextraites[3];
		$TotalHeuresMaladieprises = $donneesextraites[4];
		for ($i = 1; $i <= $maxdaymois; $i++) {
			$NbHextrait[$i] = $donneesextraites[6 + 4 * $i];
			$NbCongesextrait[$i] = $donneesextraites[7 + 4 * $i];
			$NbMaladieextrait[$i] = $donneesextraites[8 + 4 * $i];
			$NbRemextrait[$i] = $donneesextraites[9 + 4 * $i];
		};
	};

	$demande_on = '';
	$demande_on_1 = '';

	if (file_exists($fichier_demande_csv)) {
		$demande_on = ' readonly ';
		$demande_on_1 = ' hidden="hidden" ';
	};



	//------  Début création pdf ------------------------------------------

	$resultatpdfrenvoye = '';
	$resultatpdfrenvoye .= '<h1>' . $NomEmploye . ' ' . $PrenomEmploye . ' - Mois de ' . $Moislettres[$mois] . ' ' . $annee . '</h1>';
	$resultatpdfrenvoye .= '<p>Mois de <b>' . $Moislettres[$mois] . ' ' . $annee . '</b> - ' . $TotalHeures . ' Heures réalisées<br>';
	$resultatpdfrenvoye .= ' Jours de Congés pris : ' . $TotalCongespris . ' jours (soit ' . $TotalConges . ' jours restant) <br> Jours de Maladie pris : ' . $TotalMaladiepris . ' jours (soit ' . $TotalHeuresMaladieprises . ' heures)</p><hr>';
	$resultatpdfrenvoye .= '<p>Saison ' . $saison . '-' . $saisonplusun . ' : Du 1er Août ' . $saison . ' au 31 Juillet ' . $saisonplusun . '<br>';
	$OldHeuresRealisees_a = $OldHeuresRealisees + $TotalHeures;
	$TotalMensueldu_a = $TotalMensueldu + $VolumeMensueldu;
	$resultatpdfrenvoye .= 'Cumul période jusqu\'au ' . $maxdaymois . ' ' . $Moislettres[$mois] . ' ' . $annee . ' (inclus) : ' . $OldHeuresRealisees_a . ' heures réalisées / ' . $TotalMensueldu_a . ' heures dues</p><hr>';


	$resultatpdfrenvoye .= '<table id="tab" rowspacing=5><tbody>';
	$resultatpdfrenvoye .= '<tr height="20px"><th class="col_1">Date</th><th class="col_2">H Théo.</th><th class="col_2">H Réal.</th><th class="col_2">Congés</th><th class="col_2">Maladie</th><th class="col_3">Remarque</th></tr>';

	for ($i = 1; $i <= $maxdaymois; $i++) {
		$cachecase = 'type="checkbox"';
		if (($i + $ajout + 5) % 7 == 6) $cachecase = 'type="hidden" value="0"';
		if (JourFerie(mktime(0, 0, 0, $mois, $i, $annee))) $cachecase = 'type="hidden" value="0" ';

		if (!isset($NbRemextrait[$i])) {
			$NbRemextrait[$i] = "";
		}

		$readonly = '';
		$colorchecked = 'transparent';
		if (JourFerie(mktime(0, 0, 0, $mois, $i, $annee))) {
			$readonly = ' readonly ';
			$colorchecked = '#FF4500';
		};

		$congeschecked = '<img src="https://www.gym-concordia.com/assets/imgs/icon/case-vide.png" width="16">';
		if ($NbCongesextrait[$i] == "TRUE") {
			$congeschecked = '<img src="https://www.gym-concordia.com/assets/imgs/icon/case-cochee.png" width="16">';
			$colorchecked = '#77B5FE';
		};
		$maladiechecked = '<img src="https://www.gym-concordia.com/assets/imgs/icon/case-vide.png" width="16">';
		if ($NbMaladieextrait[$i] == "TRUE") {
			$maladiechecked = '<img src="https://www.gym-concordia.com/assets/imgs/icon/case-cochee.png" width="16">';
			$colorchecked = '#FD6C9E';
		};

		$resultatpdfrenvoye .= '
					<tr bgcolor="' . ColorFont(mktime(0, 0, 0, $mois, $i, $annee)) . '">
					<td class="col_1">' . $JourSemaine[($i + $ajout + 5) % 7] . ' ' . $i . ' ' . $Moislettres[$mois] . ' ' . $annee . '</td>
					<td class="col_2">' . floatval($HeuresTheoriques[($i + $ajout + 5) % 7]) . '</td>
					<td class="col_2" bgcolor="' . $colorchecked . '">' . $NbHextrait[$i] . '</td>
					<td class="col_2">' . $congeschecked . '</td>
					<td class="col_2">' . $maladiechecked . '</td>
					<td class="col_3" bgcolor="transparent">' . $NbRemextrait[$i] . '</td>
					</tr>';
	};

	$resultatpdfrenvoye .= '</tbody></table>';


	//------  Fin création pdf ------------------------------------------

	return $resultatpdfrenvoye;
};

function Sauvegarde($mois, $annee, $user_id, $info) {
    $maxdaymois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
	$directory = public_path('employee_documents/1-sauvegarde');
    $fichier_csv = $directory.'/'.$user_id.'-'.$annee.'-'.$mois.'.csv';

	$stockage = '' ;
	$stockage = $info['HeuresTotal'].';'.$info['JoursCongesPris'].';'.$info['JoursCongesRestant'] ;
	$stockage .= ';'.$info['JoursMaladiePris'].';'.$info['TotalHeuresMaladiePrises'] ;

	$stockage .= ';'.$info['HeuresTotal'].';'.$info['JoursCongesPris'].';'.$info['JoursCongesRestant'] ;
	$stockage .= ';'.$info['JoursMaladiePris'].';'.$info['TotalHeuresMaladiePrises'] ;

    for ($i = 1; $i <= $maxdaymois; $i++) {
        if (isset($info['Conges'][$i]) ) {
            $info['Conges'][$i] = "TRUE";
        } else {
            $info['Conges'][$i] = "FALSE";
        }
        if (isset($info['Maladie'][$i])) {
            $info['Maladie'][$i] = "TRUE";
        } else {
            $info['Maladie'][$i] = "FALSE";
        }
        $stockage .= ';'.(isset($info['Heures'][$i]) ? $info['Heures'][$i] : '').';'.(isset($info['Conges'][$i]) ? $info['Conges'][$i] : '').';'.(isset($info['Maladie'][$i]) ? $info['Maladie'][$i] : '').';'.(isset($info['Remarque'][$i]) ? $info['Remarque'][$i] : '');
    }

    file_put_contents($fichier_csv, $stockage);
}



function Efface($mois, $annee, $user_id)
{
	$fichier_csv = public_path('employee_documents/2-demande/' . $user_id . '-' . $annee . '-' . $mois . '.csv');

	unlink($fichier_csv);
};



function Efface_admin($mois, $annee, $user_id)
{
	$fichier_demande_csv = public_path('employee_documents/2-demande/' . $user_id . '-' . $annee . '-' . $mois . '.csv');

	unlink($fichier_demande_csv);
};



function Soumettre($mois, $annee, $user_id, $info, $action)
{

	//  var_dump($info['Maladie']);
	//  exit();

	$maxdaymois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);

	$stockage = '';
	$stockage = $info['HeuresTotal'] . ';' . $info['JoursCongesPris'] . ';' . $info['JoursCongesRestant'];
	$stockage .= ';' . $info['JoursMaladiePris'] . ';' . $info['TotalHeuresMaladiePrises'];

	$stockage .= ';' . $info['HeuresTotal'] . ';' . $info['JoursCongesPris'] . ';' . $info['JoursCongesRestant'];
	$stockage .= ';' . $info['JoursMaladiePris'] . ';' . $info['TotalHeuresMaladiePrises'];


	for ($i = 1; $i <= $maxdaymois; $i++) {

		if (isset($info['Maladie'][$i]) || isset($info['Conges'][$i])) {
			if (isset($info['Maladie'][$i])) {
				if ($info['Maladie'][$i] == "on") {
					$info['Maladie'][$i] = "TRUE";
				} else $info['Maladie'][$i] = "FALSE";



				$stockage .= ';' . $info['Heures'][$i] . ';;' . $info['Maladie'][$i] . ';' . $info['Remarque'][$i];
			}
			if (isset($info['Conges'][$i])) {
				if ($info['Conges'][$i] == "on") {
					$info['Conges'][$i] = "TRUE";
				} else $info['Conges'][$i] = "FALSE";

				$stockage .= ';' . $info['Heures'][$i] . ';' . $info['Conges'][$i] . ';;' . $info['Remarque'][$i];
			}
		} else {
			if (!isset($info['Conges'][$i])) {
				$info['Conges'][$i] = "";
			}

			$stockage .= ';' . $info['Heures'][$i] . ';' . $info['Conges'][$i] . ';;' . $info['Remarque'][$i];
		}
	}



	if ($action == "Soumettre") {
		$fichier_csv = public_path('employee_documents/1-sauvegarde/' . $user_id . '-' . $annee . '-' . $mois . '.csv');
        
		file_put_contents($fichier_csv, $stockage);

		$fichier_demande_csv = public_path('employee_documents/2-demande/' . $user_id . '-' . $annee . '-' . $mois . '.csv');

		file_put_contents($fichier_demande_csv, $stockage);

		$fichier_demande_csv = public_path('employee_documents/3-validation/' . $user_id . '-' . $annee . '-' . $mois . '.csv');


		file_put_contents($fichier_demande_csv, $stockage);
	} else if ($action == "Modifier") {
		$fichier_demande_csv = public_path('employee_documents/3-validation/' . $user_id . '-' . $annee . '-' . $mois . '.csv');

		file_put_contents($fichier_demande_csv, $stockage);
	}
};


function Savepdf($mois, $annee, $user_id, $valeurpdf)
{

	//Génération pdf
	ob_start();
	$stockage1 = '';
	$stockage1 .= '<style type="text/css">
          table.border_1_solid_black td { border : 1px solid black; }
          .col_1{
          position:relative;
          line-height:15px;
          width:220px
          }
          .col_2{
           position:relative;
           line-height:15px;
           width: 60px;
           text-align:center;
          }
          .col_3{
           position:relative;
           line-height:15px;
           width: 730px;
          }
          </style>';
	$stockage1 .= '<page  orientation="portrait" backtop="30mm" backbottom="0mm" backleft="3mm" backright="3mm">';
	$stockage1 .= '<page_header><p align="center"><img class="center" src="https://www.gym-concordia.com/assets/imgs/Entete.jpg" width="800"></p></page_header>';
	$stockage1 .= $valeurpdf;
	$stockage1 .= '</page>';

	//	require_once("../../../libraries/html2pdf-old/html2pdf.class.php");
	require_once("./application/libraries/html2pdf-old/html2pdf.class.php");

	$pdf = ob_get_clean();
	//ob_end_clean();

	$pdf = new HTML2PDF('P', 'A4', 'fr', array(0, 0, 0, 0));

	$pdf->writeHTML($stockage1);

	$fichier_pdf = '../Gym-Concordia/Documents/Employes/3-validation/' . $user_id . '-' . $annee . '-' . $mois . '.pdf';

	$pdf->output($fichier_pdf, 'F');
}



function Envoi_mail($mois, $annee, $user_id, $user)
{
	$JourSemaine = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
	$Moislettres = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

	$user_prenom = strtr(
		html_entity_decode($user[1], ENT_QUOTES, 'UTF-8'),
		'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
		'aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
	);
	$user_nom = strtr(
		html_entity_decode($user[0], ENT_QUOTES, 'UTF-8'),
		'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
		'aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
	);

	// Plusieurs destinataires
	$to  = "President - Gym Concordia <president@gym-concordia.com>";
	$to .=  ', ' . 'Tresorier - Gym Concordia <tresorier@gym-concordia.com>'; // notez la virgule
	if ($_POST['user_id'] <> 4079) {
		$to .=  ', ' . 'Tresorier - Gym Concordia <tresorier@gym-concordia.com>';
	} // notez la virgule
	else {
		$to .=  ', ' . 'Thibaut Gurtler - Gym Concordia <thibaut.gurtler@gym-concordia.com>';
	};

	// Infos
	$subject = $Moislettres[$mois] . ' ' . $annee . ' - Fiche Heures';

	$message1 = '<i>[Ceci est un message automatique]</i><br><br>Bonjour,<br><br>
Veuillez trouver ci-joint ma déclaration d\'heures pour le mois de : <b>' . $Moislettres[$mois] . ' ' . $annee . '</b>.<br><br>
Cordialement<br>
<img src="https://www.gym-concordia.com/Signatures/Signature-' . strtoupper(substr($user_prenom, 0, 1)) . strtolower(substr($user_prenom, 1)) . '-' . strtoupper(substr($user_nom, 0, 1)) . strtolower(substr($user_nom, 1)) . '.png">';

	$message = html_entity_decode($message1, ENT_QUOTES, 'UTF-8');

	// echo $message ; exit ;

	$encoding = "utf-8";

	$filename = $_POST['user_id'] . '-' . $_POST['annee'] . '-' . $_POST['mois'] . '.pdf';
	$file = '../Gym-Concordia/Documents/Employes/3-validation/' . $_POST['user_id'] . '-' . $_POST['annee'] . '-' . $_POST['mois'] . '.pdf';
	$content = file_get_contents($file);
	$content = chunk_split(base64_encode($content));
	$uid = md5(uniqid(time()));
	$name = basename($file);

	// header

	$header = "From: " . $user_prenom . " " . $user_nom . " <" . $user[2] . "> \r\n";
	$header .= "Cc: " . $user_prenom . " " . $user_nom . " <" . $user[2] . "> \r\n";
	// $header .= 'Bcc: anniversaire_verif@example.com' . "\r\n";
	$header .= "Reply-To: " . $user_prenom . " " . $user_nom . " <" . $user[2] . "> \r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";

	// message & attachment
	$nmessage = "--" . $uid . "\r\n";
	$nmessage .= "Content-type:text/html; charset=utf-8\r\n";
	$nmessage .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
	$nmessage .= $message . "\r\n\r\n";
	$nmessage .= "--" . $uid . "\r\n";
	$nmessage .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"\r\n";
	$nmessage .= "Content-Transfer-Encoding: base64\r\n";
	$nmessage .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";
	$nmessage .= $content . "\r\n\r\n";
	$nmessage .= "--" . $uid . "--";

	// Send mail
	mail($to, $subject, $nmessage, $header);
}


function updateBDD($info, $id_user, $mois_etudie)
{
	$conges = $info['JoursCongesRestant'];
$heuresTotal = $info['HeuresTotal'];

$totheure = DB::table('users_professionals')->where('id_user', $id_user)->value('OldHeuresRealisees');

$heuresTotal = $totheure + $heuresTotal;

DB::table('users_professionals')
    ->where('id_user', $id_user)
    ->update([
        'SoldeConges' => $conges,
        'OldHeuresRealisees' => $heuresTotal,
        'LastDeclarationMonth' => $mois_etudie
    ]);

if ($mois_etudie == "7") {
    DB::table('users_professionals')
        ->where('id_user', $id_user)
        ->update([
            'OldHeuresRealisees' => 0,
            'Saison' => DB::raw('Saison + 1')
        ]);
}

}


function getLastMonth($id_user)
{
    $user = DB::table('users_professionals')
        ->select('LastDeclarationMonth', 'LastDeclarationYear')
        ->where('id_user', $id_user)
        ->first();
$month = $user->LastDeclarationMonth;
$year = $user->LastDeclarationYear;

$actualYear = date('Y');

$month++;

if ($month > 12 && $year != $actualYear) {
    $month = 1;
    DB::table('users_professionals')
        ->where('id_user', $id_user)
        ->update(['LastDeclarationYear' => DB::raw('LastDeclarationYear + 1')]);
} else if ($month > 12 && $year == $actualYear) {
    $month = 1;
}

return $month;

}
function getYear($id_user)
{
    $result = DB::table('users_professionals')
        ->select('LastDeclarationYear')
        ->where('id_user', $id_user)
        ->first();

    return $result->LastDeclarationYear;
}

function getSeason($id_user) 
{
    $saison = DB::table('users_professionals')->where('id_user', $id_user)->value('Saison');
    return $saison;
}


function newSeason($mois)
{
    if ($mois == "7") {
        DB::table('users_professionals')->update([
            'OldHeuresRealisees' => 0,
            'Saison' => DB::raw('Saison+1')
        ]);
    }
}

?>

<!---------------------------------------------------------------------------------------------code------------------------------------------------------------------------------------->
@extends('layouts.template')

@section('content')
@php

	require_once(app_path().'/ProfessionelFonction.php');
	$date = "2023-04-22";
	$timestamp = strtotime($date);
	$couleur = ColorFont($timestamp);

@endphp
<main class="main" id="main">
<form action="#" method="post">
	<input type="hidden" name="mois" value="MOIS_VALUE">
	<input type="hidden" name="annee" value="ANNEE_VALUE">
	<input type="hidden" name="user_id" value="USER_ID_VALUE">
	<input type="hidden" name="email" value="EMAIL_EMPLOYE_VALUE">

	<div align="center">
		<table border="5px" width="80%">
			<tbody align="left">
				<tr>
					<td>
						<table align="center">
							<tbody>
								<tr>
									<td class="mx-3">
										<button class="btn btn-info">Sauvegarder</button>
									</td>
										<td class="">
											<button class="btn btn-danger mx-5">Reinitialiser</button>
										</td>
										<td class="mx-3">
											<button class="btn btn-success">Valider</button>
										</td>
								</tr>
							</tbody>
						</table>
						<hr>

						<h1>{{ $pro->firstname }} {{ $pro->lastname }} - Mois de MOIS_LETTRES_VALUE ANNEE_VALUE</h1>
						<h3>Saison SAISON_VALUE-SAISONPLUSUN_VALUE : Du 1er Août SAISON_VALUE au 31 Juillet SAISONPLUSUN_VALUE</h3>
						<p>Cumul période jusqu'au 1er MOIS_LETTRES_VALUE ANNEE_VALUE (inclus) : OLD_HEURES_REALISEES_VALUE heures réalisées / TOTAL_MENSUEL_DU_VALUE heures dues</p>
						<hr>
						<table border="0">
							<tbody>
								<tr>
									<td>
										<p>Mois de <b>MOIS_LETTRES_VALUE ANNEE_VALUE</b> - </td>
									<td> <input type="text" style="text-align:center; width: 50px;" id="HeuresTotal" value="TOTAL_HEURES_VALUE" name="HeuresTotal" size="5" readonly></td>
									<td>
										<p> Heures réalisées</p>
									</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>
										<p> Jours de Congés pris</td>
									<td> <input type="text" style="text-align:center;" id="JoursCongesPris" name="JoursCongesPris" value="TOTAL_CONGES_PRIS_VALUE" size="2" readonly></td>
									<td>
										<p> jours (soit </p>
									</td>
									<td><input type="text" style="text-align:center;" id="JoursCongesRestant" name="JoursCongesRestant" value="TOTAL_CONGES_VALUE" size="2" readonly></td>
									<td>
									<p> jours restant)</p>
									</td>
									</tr>
									<tr>
									<td>
									<p> Jours de Maladie pris</td>
									<td> <input type="text" style="text-align:center;" id="JoursMaladiePris" name="JoursMaladiePris" value="TOTAL_MALADIE_PRIS_VALUE" size="2" readonly></td>
									<td>
									<p> jours (soit </p>
									</td>
									<td><input type="text" style="text-align:center;" id="TotalHeuresMaladiePrises" name="TotalHeuresMaladiePrises" value="TOTAL_HEURES_MALADIE_PRISES_VALUE" size="2" readonly></td>
									<td>
									<p> heures)</p>
									</td>
									</tr>
									</tbody>
									</table>
									<hr>
									<table id="tab" border="1">
										<tbody>
											<tr>
												<th class="col_1">Date</th>
												<th class="col_2">H Théo.</th>
												<th class="col_2">H Réal.</th>
												<th class="col_2">Congés</th>
												<th class="col_2">Maladie</th>
												<th class="col_3">Remarque</th>
											</tr>
			
											<!-- You can repeat the following block for each row in the table -->
											<tr style="background-color: ROW_BACKGROUND_COLOR">
												<td class="col_1">DATE_VALUE</td>
												<td class="col_2">HEURES_THEORIQUES_VALUE</td>
												<td class="col_2"><input READONLY_ATTRIBUTE type="text" style="text-align:center; background-color:CELL_BACKGROUND_COLOR;" size="3" value="NB_HEURES_EXTRAIT_VALUE" id="Heures[INDEX]" name="Heures[INDEX]" onkeyup="calculTotal(INDEX)" /></td>
												<td class="col_2"><input label="Conges" name="Conges[INDEX]" id="Conges[INDEX]" onchange="bloqueHeuresConges(INDEX, HEURES_THEORIQUES_VALUE)" TYPE_ATTRIBUTE CONGES_CHECKED_VALUE></td>
												<td class="col_2"><input type="checkbox" label="Maladie" name="Maladie[INDEX]" id="Maladie[INDEX]" onchange="bloqueHeuresMaladie(INDEX, HEURES_THEORIQUES_VALUE)" MALADIE_CHECKED_VALUE></td>
												<td class="col_3"><input type="text" size="100%" style="background-color:ROW_BACKGROUND_COLOR" name="Remarque[INDEX]" value="NB_REM_EXTRAIT_VALUE"></td>
											</tr>
											<!-- End of the repeated block -->
			
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
		</main>	
@endsection


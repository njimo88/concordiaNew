<style type="text/css">
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

</style>

<script language="javascript">
function calculTotal(pNum){
		var nbLignes = document.getElementById("tab").rows.length;
		document.getElementById('HeuresTotal').value = 0;
		for (i=1;i<nbLignes;i++)
		{	var ChaineReplace = document.getElementById('Heures['+i+']').value ;
			document.getElementById('HeuresTotal').value= parseFloat(document.getElementById('HeuresTotal').value) + parseFloat(ChaineReplace.replace(',','.'));
			} ;
}


function bloqueHeuresConges(pNum,pValue){

	if (document.getElementById('Conges['+pNum+']').checked == true)
	    { document.getElementById('Heures['+pNum+']').value = pValue ;
		  if (document.getElementById('Maladie['+pNum+']').checked == true)
		  { document.getElementById('Maladie['+pNum+']').checked = false ;
		  	document.getElementById('JoursMaladiePris').value = document.getElementById('JoursMaladiePris').value - 1 ;
			document.getElementById('TotalHeuresMaladiePrises').value = document.getElementById('TotalHeuresMaladiePrises').value - document.getElementById('Heures['+pNum+']').value ;
		  	} ;
		  document.getElementById('JoursCongesRestant').value = document.getElementById('JoursCongesRestant').value - 1 ;
		  document.getElementById('JoursCongesPris').value = document.getElementById('JoursCongesPris').value - (-1) ;
		  document.getElementById('Heures['+pNum+']').setAttribute('readonly','true') ;
		  document.getElementById('Heures['+pNum+']').setAttribute('style','text-align:center; background-color:#77B5FE') ;
		  calculTotal(pNum) ;
		  } ;
	if (document.getElementById('Conges['+pNum+']').checked == false)
		{ document.getElementById('Heures['+pNum+']').value = pValue ;
		  document.getElementById('JoursCongesRestant').value = document.getElementById('JoursCongesRestant').value - (-1);
		  document.getElementById('JoursCongesPris').value = document.getElementById('JoursCongesPris').value - 1;
		  document.getElementById('Heures['+pNum+']').removeAttribute('readonly') ;
		  document.getElementById('Heures['+pNum+']').setAttribute('style','text-align:center; background-color:#FFFFFF;') ;
		  calculTotal(pNum) ;
		  } ;
}


function bloqueHeuresMaladie(pNum,pValue){

	if (document.getElementById('Maladie['+pNum+']').checked == true)
	    { if (document.getElementById('Conges['+pNum+']').checked == true)
			{ document.getElementById('Conges['+pNum+']').checked = false ;
			  document.getElementById('JoursCongesRestant').value = document.getElementById('JoursCongesRestant').value - (-1);
			  document.getElementById('JoursCongesPris').value = document.getElementById('JoursCongesPris').value - 1;
			 } ;
		  document.getElementById('JoursMaladiePris').value = document.getElementById('JoursMaladiePris').value - (-1) ;
		  document.getElementById('Heures['+pNum+']').value = pValue ;
		  document.getElementById('TotalHeuresMaladiePrises').value = document.getElementById('TotalHeuresMaladiePrises').value - (-document.getElementById('Heures['+pNum+']').value) ;
		  document.getElementById('Heures['+pNum+']').setAttribute('readonly','true') ;
		  document.getElementById('Heures['+pNum+']').setAttribute('style','text-align:center; background-color:#FD6C9E;') ;
		  calculTotal(pNum) ;
		  } ;
	if (document.getElementById('Maladie['+pNum+']').checked == false)
		{ document.getElementById('Heures['+pNum+']').value = pValue ;
		  document.getElementById('TotalHeuresMaladiePrises').value = document.getElementById('TotalHeuresMaladiePrises').value - document.getElementById('Heures['+pNum+']').value ;
		  document.getElementById('JoursMaladiePris').value = document.getElementById('JoursMaladiePris').value - 1 ;
		  document.getElementById('Heures['+pNum+']').removeAttribute('readonly') ;
		  document.getElementById('Heures['+pNum+']').setAttribute('style','text-align:center; background-color:#FFFFFF;') ;
		  calculTotal(pNum) ;
		  } ;
}
</script>

<?php

// Vacances Scolaires
function VacancesScolaires( $datedujour )
{
  $retour = FALSE ;

  $year = date("Y",$datedujour) ;
//2019-2020
  $toussaint[2019]   = array (mktime(0, 0, 0, 10,  20,  2019) , mktime(0, 0, 0, 11,  3,  2019)); //ok
  $noel[2019] = array (mktime(0, 0, 0, 12,  22,  2019) , mktime(0, 0, 0, 1,  5,  2020)); //ok
  $hiver[2020]   = array (mktime(0, 0, 0, 2,  16,  2020) , mktime(0, 0, 0, 3,  1,  2020)); //ok
  $printemps[2020] = array (mktime(0, 0, 0, 4,  12,  2020) , mktime(0, 0, 0, 4,  26,  2020)) ; //ok
  $ete[2020]  = array (mktime(0, 0, 0, 7,  5,  2020) , mktime(0, 0, 0, 8,  31,  2020)); //ok
//2020-2021
  $toussaint[2020]   = array (mktime(0, 0, 0, 10,  18,  2020) , mktime(0, 0, 0, 11,  1,  2020)); //ok
  $noel[2020] = array (mktime(0, 0, 0, 12,  20,  2020) , mktime(0, 0, 0, 1,  3,  2021)); //ok
  $hiver[2021]   = array (mktime(0, 0, 0, 2,  21,  2021) , mktime(0, 0, 0, 3,  7,  2021)); //ok
  $printemps[2021] = array (mktime(0, 0, 0, 4,  25,  2021) , mktime(0, 0, 0, 5,  10,  2021)) ; //ok
  $ete[2021]  = array (mktime(0, 0, 0, 7,  7,  2021) , mktime(0, 0, 0, 8,  31,  2021)); //ok

  if (($datedujour >= $noel[$year-1][0])&&($datedujour <= $noel[$year-1][1])) $retour = TRUE ;  // noel n-1
  if (($datedujour >= $hiver[$year][0])&&($datedujour <= $hiver[$year][1])) $retour = TRUE ;  // hiver
  if (($datedujour >= $printemps[$year][0])&&($datedujour <= $printemps[$year][1])) $retour = TRUE ;  // printemps
  if (($datedujour >= $ete[$year][0])&&($datedujour <= $ete[$year][1])) $retour = TRUE ;  // ete
  if (($datedujour >= $toussaint[$year][0])&&($datedujour <= $toussaint[$year][1])) $retour = TRUE ;  // toussaint
  if (($datedujour >= $noel[$year][0])&&($datedujour <= $noel[$year][1])) $retour = TRUE ;  // noel

  return $retour;
} ;

//Jours Fériés
function JourFerie( $datedujour )
{
  $retour = FALSE ;

  $year = date("Y",$datedujour) ;

  $easterDate  = easter_date($year);
  $easterDay   = date('j', $easterDate);
  $easterMonth = date('n', $easterDate);
  $easterYear   = date('Y', $easterDate);

  // Dates Fixes
  if ($datedujour == mktime(0, 0, 0, 1,  1,  $year)) $retour = TRUE ;  // 1er janvier
  if ($datedujour == mktime(0, 0, 0, 5,  1,  $year)) $retour = TRUE ;	// Fête du travail
  if ($datedujour == mktime(0, 0, 0, 5,  8,  $year)) $retour = TRUE ;  // Victoire des alliés
  if ($datedujour == mktime(0, 0, 0, 7,  14, $year)) $retour = TRUE ;  // Fête nationale
  if ($datedujour == mktime(0, 0, 0, 8,  15, $year)) $retour = TRUE ; // Assomption
  if ($datedujour == mktime(0, 0, 0, 11,  1,  $year)) $retour = TRUE ; // Toussaint
  if ($datedujour == mktime(0, 0, 0, 11,  11,  $year)) $retour = TRUE ; // Armistice
  if ($datedujour == mktime(0, 0, 0, 12,  25,  $year)) $retour = TRUE ; // Noel
  if ($datedujour == mktime(0, 0, 0, 12,  26,  $year)) $retour = TRUE ; // St Etienne

    // Dates variables
	if ($datedujour == mktime(0, 0, 0, $easterMonth, $easterDay - 2,  $easterYear)) $retour = TRUE ;
  	if ($datedujour == mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear)) $retour = TRUE ;
  	if ($datedujour == mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear)) $retour = TRUE ;
  	if ($datedujour == mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear)) $retour = TRUE ;

  return $retour;
} ;

//Couleur du Font
function ColorFont ( $datedujour )
{
	$colordufont = "#FFFFFF";
	$numdujour = date("w",$datedujour);
	if (VacancesScolaires($datedujour)) $colordufont = "#AFAFAF";
	if (($numdujour==0)||($numdujour==6)) $colordufont = "#FFFF00";
	if (JourFerie($datedujour)) $colordufont = "#FF4500";

	return $colordufont ;
} ;


//Affichage du Mois
function AfficheMois ( $mois , $annee , $user_id )
{

	$sql = new mysqli('db566489223.db.1and1.com', 'dbo566489223', 'mickmickmath', 'db566489223');

	//mysqli_query("SET NAMES UTF8");

	if ($sql->connect_errno) {
		printf("Échec de la connexion : %s\n", $sql->connect_error);
		exit();
	}

	if ($result = $sql->query("SELECT * FROM users_professionals WHERE id_user='$user_id'"))
	{
		foreach ($result as $ligne)
		{
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


		$resultatrenvoye = '' ;

		$NomEmploye = $VariableBDD[1] ;
		$PrenomEmploye = $VariableBDD[2] ;
		$Matricule = $VariableBDD[13] ;
		$SoldeConges = $VariableBDD[12] ;
		$EmailEmploye = $VariableBDD[14] ;
		$saison = 2020 ;
		$saisonplusun = $saison+1 ;
		$HeuresTheoriques = array ($VariableBDD[4],$VariableBDD[5],$VariableBDD[6],$VariableBDD[7],$VariableBDD[8],$VariableBDD[9],$VariableBDD[10]) ;
		$OldHeuresRealisees = $VariableBDD[11] ;
		$VolumeHebdo = $VariableBDD[3] ;

		$JourSemaine = array ("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche") ;
		$Moislettres = array ("", "Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre") ;

		// Ajout des jours pour 1er jour
		$firstjourperiode=mktime(0,0,0,$mois,1,$annee);
		$ajout = date("w",$firstjourperiode);

		$maxdaymois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee) ;
		$TotalHeures = 0 ;

		for ($i=1;$i<=$maxdaymois;$i++)
		{ 	$TotalHeures = $TotalHeures + floatval($HeuresTheoriques[($i+$ajout+5)%7]) ;
		    $NbHextrait[$i] = floatval($HeuresTheoriques[($i+$ajout+5)%7]) ; $NbCongesextrait[$i] = "FALSE" ; $NbMaladieextrait[$i] = "FALSE" ;
			} ;

		$TotalConges = $SoldeConges + 2.5 ;
		$TotalCongespris = 0 ;
		$TotalMaladiepris = 0 ;
		$TotalHeuresMaladieprises = 0 ;
		$VolumeMensueldu = round($VolumeHebdo*52/12 ,2) ;
		if ($mois >=8) { $NbMoisPeriode = $mois - 8 ; }
		  else { $NbMoisPeriode = $mois + 4 ; }
		$TotalMensueldu = $VolumeMensueldu*$NbMoisPeriode ;

		$fichier_csv = '1-sauvegarde/'.$user_id.'-'.$annee.'-'.$mois.'.csv';
		$fichier_demande_csv = '2-demande/'.$user_id.'-'.$annee.'-'.$mois.'.csv';

		if (file_exists ($fichier_csv))
		{
			$current = file_get_contents($fichier_csv);
			$donneesextraites = explode(';', $current) ;

			$TotalHeures = $donneesextraites[0] ;
			$TotalConges = $donneesextraites[2] ;
			$TotalCongespris = $donneesextraites[1] ;
			$TotalMaladiepris = $donneesextraites[3] ;
			$TotalHeuresMaladieprises = $donneesextraites[4] ;
			for ($i=1;$i<=$maxdaymois;$i++)
			{ $NbHextrait[$i] = $donneesextraites[6+4*$i] ; $NbCongesextrait[$i] = $donneesextraites[7+4*$i] ; $NbMaladieextrait[$i] = $donneesextraites[8+4*$i] ; $NbRemextrait[$i] = $donneesextraites[9+4*$i] ; } ;
		} ;

		$demande_on = '';
		$demande_on_1 = '' ;

		if (file_exists ($fichier_demande_csv))
		{
			$demande_on = ' readonly ';
			$demande_on_1 = ' hidden="hidden" ' ;
		} ;


				$resultatrenvoye .= '<form action="affichheures.php" method="post">' ;

				$resultatrenvoye .= '<input type="hidden" name="mois" value="'.$mois.'">' ;
				$resultatrenvoye .= '<input type="hidden" name="annee" value="'.$annee.'">' ;
				$resultatrenvoye .= '<input type="hidden" name="user_id" value="'.$user_id.'">' ;
				$resultatrenvoye .= '<input type="hidden" name="idemploye" value="'.$idemploye.'">' ;
				$resultatrenvoye .= '<input type="hidden" name="email" value="'.$EmailEmploye.'">' ;

				$resultatrenvoye .= '<div align="center"><table border="5px" width="80%><tbody align="left"><tr><td>' ;
				$resultatrenvoye .= '<table align="center"><tbody><tr><td>
				<input type="submit" name="Statut" value="Sauvegarder">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="Statut" value="Reinitialiser">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

				<input type="submit" name="Statut" value="Soumettre">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

				// $resultatrenvoye .= '<input type="submit" name="Statut" value="Annule Demande">' ;
				$resultatrenvoye .= '</td></tr></tbody></table><hr>' ;

				$resultatrenvoye .= '<h1>'.$NomEmploye.' '.$PrenomEmploye.' - Mois de '.$Moislettres[$mois].' '.$annee.'</h1>' ;
				$resultatrenvoye .= '<h3>Saison '.$saison.'-'.$saisonplusun.' : Du 1er Août '.$saison.' au 31 Juillet '.$saisonplusun.'</h3>' ;
				$resultatrenvoye .= '<p>Cumul période jusqu\'au 1er '.$Moislettres[$mois].' '.$annee.' (inclus) : '.$OldHeuresRealisees.' heures réalisées / '.$TotalMensueldu.' heures dues</p><hr>' ;
				$resultatrenvoye .= '<p>Mois de <b>'.$Moislettres[$mois].' '.$annee.'</b> - <input type="text" style="text-align:center;" id="HeuresTotal" value="'.$TotalHeures.'" name="HeuresTotal" size="5" readonly> Heures réalisées<br>' ;
				$resultatrenvoye .= ' Jours de Congés pris <input type="text" style="text-align:center;" id="JoursCongesPris" name="JoursCongesPris" value="'.$TotalCongespris.'" size="3" readonly> jours (soit <input type="text" style="text-align:center;" id="JoursCongesRestant" name="JoursCongesRestant" value="'.$TotalConges.'" size="3" readonly> jours restant) <br> Jours de Maladie pris <input type="text" style="text-align:center;" id="JoursMaladiePris" name="JoursMaladiePris" value="'.$TotalMaladiepris.'" size="3" readonly> jours (soit <input type="text" style="text-align:center;" id="TotalHeuresMaladiePrises" name="TotalHeuresMaladiePrises" value="'.$TotalHeuresMaladieprises.'" size="3" readonly> heures)</p><hr>' ;

				$resultatrenvoye .= '<table id="tab" border="1"><body>' ;
				$resultatrenvoye .= '<tr><th class="col_1">Date</th><th class="col_2">H Théo.</th><th class="col_2">H Réal.</th><th class="col_2">Congés</th><th class="col_2">Maladie</th><th class="col_3">Remarque</th></tr>' ;

				for ($i=1;$i<=$maxdaymois;$i++)
				 { 	$cachecase = 'type="checkbox"' ;
					if (($i+$ajout+5)%7 == 6) $cachecase = 'type="hidden" value="0"' ;
					if (JourFerie(mktime(0,0,0,$mois,$i,$annee))) $cachecase = 'type="hidden" value="0" ' ;

					$readonly = '' ;
					$colorchecked = '#FFFFFF' ;
					if (JourFerie(mktime(0,0,0,$mois,$i,$annee))) { $readonly = ' readonly ' ; $colorchecked ='#FF4500' ; } ;

					$congeschecked = '' ; if ($NbCongesextrait[$i] == "TRUE") { $congeschecked = ' checked="checked" ' ; $colorchecked = '#77B5FE' ; } ;
					$maladiechecked = '' ; if ($NbMaladieextrait[$i] == "TRUE") { $maladiechecked = ' checked="checked" ' ; $colorchecked = '#FD6C9E' ; } ;

					$resultatrenvoye .= '
					<tr bgcolor="'.ColorFont(mktime(0,0,0,$mois,$i,$annee)).'">
					<td class="col_1">'.$JourSemaine[($i+$ajout+5)%7].' '.$i.' '.$Moislettres[$mois].' '.$annee.'</td>
					<td class="col_2">'.floatval($HeuresTheoriques[($i+$ajout+5)%7]).'</td><td class="col_2"><input '.$readonly.' type="text" style="text-align:center; background-color:'.$colorchecked.';"  size="3" value="'.$NbHextrait[$i].'" id="Heures['.$i.']" name="Heures['.$i.']" onkeyup="calculTotal('.$i.')" '.$demande_on.'/></td>
					<td class="col_2"><input label="Conges" name="Conges['.$i.']" id="Conges['.$i.']" onchange="bloqueHeuresConges('.$i.','.floatval($HeuresTheoriques[($i+$ajout+5)%7]).')" '.$cachecase.$congeschecked.'></td>
					<td class="col_2"><input type="checkbox" label="Maladie" name="Maladie['.$i.']" id="Maladie['.$i.']" onchange="bloqueHeuresMaladie('.$i.','.floatval($HeuresTheoriques[($i+$ajout+5)%7]).')" '.$maladiechecked.'></td>
					<td class="col_3"><input type="text"  size="100%" style="background-color:'.ColorFont(mktime(0,0,0,$mois,$i,$annee)).'" name="Remarque['.$i.']" value="'.$NbRemextrait[$i].'"></td>
					</tr>' ;
				 } ;

				$resultatrenvoye .= '</tbody></table>' ;
				$resultatrenvoye .= '</td></tr></tbody></table></div></form>' ;



		return $resultatrenvoye ;


		// Close the database connection
		$sql->close();
} ;



function AfficheMois_valide ( $mois , $annee , $user_id , $valeurpdf)
{
	$sql = new mysqli('db566489223.db.1and1.com', 'dbo566489223', 'mickmickmath', 'db566489223');

	//mysqli_query("SET NAMES UTF8");

	if ($sql->connect_errno) {
		printf("Échec de la connexion : %s\n", $sql->connect_error);
		exit();
	}

	if ($result = $sql->query("SELECT * FROM users_professionals WHERE id_user='$user_id'"))
	{
		foreach ($result as $ligne)
		{
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


		$resultatrenvoye = '' ;

		$NomEmploye = $VariableBDD[1] ;
		$PrenomEmploye = $VariableBDD[2] ;
		$Matricule = $VariableBDD[13] ;
		$SoldeConges = $VariableBDD[12] ;
		$EmailEmploye = $VariableBDD[14] ;
		$saison = 2020 ;
		$saisonplusun = $saison+1 ;
		$HeuresTheoriques = array ($VariableBDD[4],$VariableBDD[5],$VariableBDD[6],$VariableBDD[7],$VariableBDD[8],$VariableBDD[9],$VariableBDD[10]) ;
		$OldHeuresRealisees = $VariableBDD[11] ;
		$VolumeHebdo = $VariableBDD[3] ;

		$JourSemaine = array ("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche") ;
		$Moislettres = array ("", "Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre") ;

		// Ajout des jours pour 1er jour
		$firstjourperiode=mktime(0,0,0,$mois,1,$annee);
		$ajout = date("w",$firstjourperiode);

		$maxdaymois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee) ;
		$TotalHeures = 0 ;

		for ($i=1;$i<=$maxdaymois;$i++)
		{ 	$TotalHeures = $TotalHeures + floatval($HeuresTheoriques[($i+$ajout+5)%7]) ;
		    $NbHextrait[$i] = floatval($HeuresTheoriques[($i+$ajout+5)%7]) ; $NbCongesextrait[$i] = "FALSE" ; $NbMaladieextrait[$i] = "FALSE" ;
			} ;

		$TotalConges = $SoldeConges + 2.5 ;
		$TotalCongespris = 0 ;
		$TotalMaladiepris = 0 ;
		$TotalHeuresMaladieprises = 0 ;
		$VolumeMensueldu = round($VolumeHebdo*52/12 ,2) ;
		if ($mois >=8) { $NbMoisPeriode = $mois - 8 ; }
		  else { $NbMoisPeriode = $mois + 4 ; }
		$TotalMensueldu = $VolumeMensueldu*$NbMoisPeriode ;

		$fichier_csv = '1-sauvegarde/'.$user_id.'-'.$annee.'-'.$mois.'.csv';
		$fichier_demande_csv = '2-demande/'.$user_id.'-'.$annee.'-'.$mois.'.csv';

		if (file_exists ($fichier_csv))
		{
			$current = file_get_contents($fichier_csv);
			$donneesextraites = explode(';', $current) ;

			$TotalHeures = $donneesextraites[0] ;
			$TotalConges = $donneesextraites[2] ;
			$TotalCongespris = $donneesextraites[1] ;
			$TotalMaladiepris = $donneesextraites[3] ;
			$TotalHeuresMaladieprises = $donneesextraites[4] ;
			for ($i=1;$i<=$maxdaymois;$i++)
			{ $NbHextrait[$i] = $donneesextraites[6+4*$i] ; $NbCongesextrait[$i] = $donneesextraites[7+4*$i] ; $NbMaladieextrait[$i] = $donneesextraites[8+4*$i] ; $NbRemextrait[$i] = $donneesextraites[9+4*$i] ; } ;
		} ;

		$demande_on = '';
		$demande_on_1 = '' ;

		if (file_exists ($fichier_demande_csv))
		{
			$demande_on = ' readonly ';
			$demande_on_1 = ' hidden="hidden" ' ;
		} ;



                //------  Début création pdf ------------------------------------------

				$resultatpdfrenvoye = '' ;
				$resultatpdfrenvoye .= '<h1>'.$NomEmploye.' '.$PrenomEmploye.' - Mois de '.$Moislettres[$mois].' '.$annee.'</h1>' ;
				$resultatpdfrenvoye .= '<p>Mois de <b>'.$Moislettres[$mois].' '.$annee.'</b> - '.$TotalHeures.' Heures réalisées<br>' ;
				$resultatpdfrenvoye .= ' Jours de Congés pris : '.$TotalCongespris.' jours (soit '.$TotalConges.' jours restant) <br> Jours de Maladie pris : '.$TotalMaladiepris.' jours (soit '.$TotalHeuresMaladieprises.' heures)</p><hr>' ;
				$resultatpdfrenvoye .= '<p>Saison '.$saison.'-'.$saisonplusun.' : Du 1er Août '.$saison.' au 31 Juillet '.$saisonplusun.'<br>' ;
				$OldHeuresRealisees_a = $OldHeuresRealisees + $TotalHeures;
				$TotalMensueldu_a = $TotalMensueldu + $VolumeMensueldu;
				$resultatpdfrenvoye .= 'Cumul période jusqu\'au '.$maxdaymois.' '.$Moislettres[$mois].' '.$annee.' (inclus) : '.$OldHeuresRealisees_a.' heures réalisées / '.$TotalMensueldu_a.' heures dues</p><hr>' ;


				$resultatpdfrenvoye .= '<table id="tab" rowspacing=5><tbody>' ;
				$resultatpdfrenvoye .= '<tr height="20px"><th class="col_1">Date</th><th class="col_2">H Théo.</th><th class="col_2">H Réal.</th><th class="col_2">Congés</th><th class="col_2">Maladie</th><th class="col_3">Remarque</th></tr>' ;

				for ($i=1;$i<=$maxdaymois;$i++)
				 { 	$cachecase = 'type="checkbox"' ;
					if (($i+$ajout+5)%7 == 6) $cachecase = 'type="hidden" value="0"' ;
					if (JourFerie(mktime(0,0,0,$mois,$i,$annee))) $cachecase = 'type="hidden" value="0" ' ;

					$readonly = '' ;
					$colorchecked = 'transparent' ;
					if (JourFerie(mktime(0,0,0,$mois,$i,$annee))) { $readonly = ' readonly ' ; $colorchecked ='#FF4500' ; } ;

					$congeschecked = '<img src="../assets/imgs/icon/case-vide.png" width="16">' ; if ($NbCongesextrait[$i] == "TRUE") { $congeschecked = '<img src="../assets/imgs/icon/case-cochee.png" width="16">' ; $colorchecked = '#77B5FE' ; } ;
					$maladiechecked = '<img src="../assets/imgs/icon/case-vide.png" width="16">' ; if ($NbMaladieextrait[$i] == "TRUE") { $maladiechecked = '<img src="https://www.gym-concordia.com/assets/imgs/icon/case-cochee.png" width="16">' ; $colorchecked = '#FD6C9E' ; } ;

					$resultatpdfrenvoye .= '
					<tr bgcolor="'.ColorFont(mktime(0,0,0,$mois,$i,$annee)).'"  height="20px">
					<td class="col_1">'.$JourSemaine[($i+$ajout+5)%7].' '.$i.' '.$Moislettres[$mois].' '.$annee.'</td>
					<td class="col_2">'.floatval($HeuresTheoriques[($i+$ajout+5)%7]).'</td>
					<td class="col_2" bgcolor="'.$colorchecked.'">'.$NbHextrait[$i].'</td>
					<td class="col_2">'.$congeschecked.'</td>
					<td class="col_2">'.$maladiechecked.'</td>
					<td class="col_3" bgcolor="transparent">'.$NbRemextrait[$i].'</td>
					</tr>' ;
				 } ;

				$resultatpdfrenvoye .= '</tbody></table>' ;


                //------  Fin création pdf ------------------------------------------

				$user = array(
				   'Nom' => $NomEmploye,
      			   'Prenom' => $PrenomEmploye,
      			   'Email' => $EmailEmploye,
				   ) ;

				$resultatrenvoye .= '<form action="valideheures.php" method="post">' ;

				$resultatrenvoye .= '<input type="hidden" name="mois" value="'.$mois.'">' ;
				$resultatrenvoye .= '<input type="hidden" name="annee" value="'.$annee.'">' ;
				$resultatrenvoye .= '<input type="hidden" name="user_id" value="'.$user_id.'">' ;
				$resultatrenvoye .= '<input type="hidden" name="idemploye" value="'.$idemploye.'">' ;
				$resultatrenvoye .= '<input type="hidden" name="user[0]" value="'.$user['Nom'].'">' ;
				$resultatrenvoye .= '<input type="hidden" name="user[1]" value="'.$user['Prenom'].'">' ;
				$resultatrenvoye .= '<input type="hidden" name="user[2]" value="'.$user['Email'].'">' ;

				$resultatrenvoye .= '<div align="center"><table border="5px" width="80%><tbody align="left"><tr><td>' ;
				$resultatrenvoye .= '<table align="center"><tbody><tr><td bgcolor="#FF0000" color="white"><h1>Validez-vous cette déclaration d\'heures pour '.$Moislettres[$mois].' '.$annee.' ?&nbsp;</h1></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input size="100%" type="hidden" name="valeurpdf" value="'.htmlentities($resultatpdfrenvoye, ENT_QUOTES, "UTF-8").'"><input type="submit" name="Statut" value="Valider">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Statut" value="Annuler"></td></tr></tbody></table><hr>' ;

				/* $resultatrenvoye .= '<h1>'.$NomEmploye.' '.$PrenomEmploye.'</h1>' ;
				$resultatrenvoye .= '<h3>Saison '.$saison.'-'.$saisonplusun.' : Du 1er Août '.$saison.' au 31 Juillet '.$saisonplusun.'</h3>' ;
				$resultatrenvoye .= '<p>Cumul période au 1er '.$Moislettres[$mois].' '.$annee.' : '.$OldHeuresRealisees.' heures réalisées / '.$TotalMensueldu.' heures dues</p><hr>' ;
				$resultatrenvoye .= '<p>Mois de <b>'.$Moislettres[$mois].' '.$annee.'</b> - <input type="text" style="text-align:center;" id="HeuresTotal" value="'.$TotalHeures.'" name="HeuresTotal" size="5" readonly> Heures réalisées<br>' ;
				$resultatrenvoye .= ' Jours de Congés pris <input type="text" style="text-align:center;" id="JoursCongesPris" name="JoursCongesPris" value="'.$TotalCongespris.'" size="3" readonly> jours (soit <input type="text" style="text-align:center;" id="JoursCongesRestant" name="JoursCongesRestant" value="'.$TotalConges.'" size="3" readonly> jours restant) <br> Jours de Maladie pris <input type="text" style="text-align:center;" id="JoursMaladiePris" name="JoursMaladiePris" value="'.$TotalMaladiepris.'" size="3" readonly> jours (soit <input type="text" style="text-align:center;" id="TotalHeuresMaladiePrises" name="TotalHeuresMaladiePrises" value="'.$TotalHeuresMaladieprises.'" size="3" readonly> heures)</p><hr>' ;

				$resultatrenvoye .= '<table id="tab" border="1"><tbody>' ;
				$resultatrenvoye .= '<tr><th class="col_1">Date</th><th class="col_2">H Théo.</th><th class="col_2">H Réal.</th><th class="col_2">Congés</th><th class="col_2">Maladie</th><th class="col_3">Remarque</th></tr>' ;

				/* for ($i=1;$i<=$maxdaymois;$i++)
				 { 	$cachecase = 'type="checkbox"' ;
					if (($i+$ajout+5)%7 == 6) $cachecase = 'type="hidden" value="0"' ;
					if (JourFerie(mktime(0,0,0,$mois,$i,$annee))) $cachecase = 'type="hidden" value="0" ' ;

					$readonly = '' ;
					$colorchecked = '#FFFFFF' ;
					if (JourFerie(mktime(0,0,0,$mois,$i,$annee))) { $readonly = ' readonly ' ; $colorchecked ='#FF4500' ; } ;

					$congeschecked = '' ; if ($NbCongesextrait[$i] == "TRUE") { $congeschecked = ' checked="checked" ' ; $colorchecked = '#77B5FE' ; } ;
					$maladiechecked = '' ; if ($NbMaladieextrait[$i] == "TRUE") { $maladiechecked = ' checked="checked" ' ; $colorchecked = '#FD6C9E' ; } ;

					$resultatrenvoye .= '
					<tr bgcolor="'.ColorFont(mktime(0,0,0,$mois,$i,$annee)).'">
					<td class="col_1">'.$JourSemaine[($i+$ajout+5)%7].' '.$i.' '.$Moislettres[$mois].' '.$annee.'</td>
					<td class="col_2">'.floatval($HeuresTheoriques[($i+$ajout+5)%7]).'</td><td class="col_2"><input '.$readonly.' type="text" style="text-align:center; background-color:'.$colorchecked.';"  size="3" value="'.$NbHextrait[$i].'" id="Heures['.$i.']" name="Heures['.$i.']" onkeyup="calculTotal('.$i.')" '.$demande_on.'/></td>
					<td class="col_2"><input label="Conges" name="Conges['.$i.']" id="Conges['.$i.']" onchange="bloqueHeuresConges('.$i.','.floatval($HeuresTheoriques[($i+$ajout+5)%7]).')" '.$cachecase.$congeschecked.'></td>
					<td class="col_2"><input type="checkbox" label="Maladie" name="Maladie['.$i.']" id="Maladie['.$i.']" onchange="bloqueHeuresMaladie('.$i.','.floatval($HeuresTheoriques[($i+$ajout+5)%7]).')" '.$maladiechecked.'></td>
					<td class="col_3"><input type="text"  size="100%" style="background-color:'.ColorFont(mktime(0,0,0,$mois,$i,$annee)).'" name="Remarque['.$i.']" value="'.$NbRemextrait[$i].'"></td>
					</tr>' ;
				 } ; */

				$resultatrenvoye .= '<h1>'.$NomEmploye.' '.$PrenomEmploye.' - Mois de '.$Moislettres[$mois].' '.$annee.'</h1>' ;
				$resultatrenvoye .= '<p>Mois de <b>'.$Moislettres[$mois].' '.$annee.'</b> - '.$TotalHeures.' Heures réalisées<br>' ;
				$resultatrenvoye .= ' Jours de Congés pris : '.$TotalCongespris.' jours (soit '.$TotalConges.' jours restant) <br> Jours de Maladie pris : '.$TotalMaladiepris.' jours (soit '.$TotalHeuresMaladieprises.' heures)</p><hr>' ;
				$resultatrenvoye .= '<p>Saison '.$saison.'-'.$saisonplusun.' : Du 1er Août '.$saison.' au 31 Juillet '.$saisonplusun.'<br>' ;
				$resultatrenvoye .= 'Cumul période jusqu\'au 1er '.$Moislettres[$mois].' '.$annee.' (non inclus) : '.$OldHeuresRealisees.' heures réalisées / '.$TotalMensueldu.' heures dues</p><hr>' ;


				$resultatrenvoye .= '<table id="tab" rowspacing=5><tbody>' ;
				$resultatrenvoye .= '<tr height="20px"><th class="col_1">Date</th><th class="col_2">H Théo.</th><th class="col_2">H Réal.</th><th class="col_2">Congés</th><th class="col_2">Maladie</th><th class="col_3">Remarque</th></tr>' ;
				 for ($i=1;$i<=$maxdaymois;$i++)
				 { 	$cachecase = 'type="checkbox"' ;
					if (($i+$ajout+5)%7 == 6) $cachecase = 'type="hidden" value="0"' ;
					if (JourFerie(mktime(0,0,0,$mois,$i,$annee))) $cachecase = 'type="hidden" value="0" ' ;

					$readonly = '' ;
					$colorchecked = 'transparent' ;
					if (JourFerie(mktime(0,0,0,$mois,$i,$annee))) { $readonly = ' readonly ' ; $colorchecked ='#FF4500' ; } ;

					$congeschecked = '<img src="../assets/imgs/icon/case-vide.png" width="16">' ; if ($NbCongesextrait[$i] == "TRUE") { $congeschecked = '<img src="../assets/imgs/icon/case-cochee.png" width="16">' ; $colorchecked = '#77B5FE' ; } ;
					$maladiechecked = '<img src="../assets/imgs/icon/case-vide.png" width="16">' ; if ($NbMaladieextrait[$i] == "TRUE") { $maladiechecked = '<img src="https://www.gym-concordia.com/assets/imgs/icon/case-cochee.png" width="16">' ; $colorchecked = '#FD6C9E' ; } ;

					$resultatrenvoye .= '
					<tr bgcolor="'.ColorFont(mktime(0,0,0,$mois,$i,$annee)).'"  height="20px">
					<td class="col_1">'.$JourSemaine[($i+$ajout+5)%7].' '.$i.' '.$Moislettres[$mois].' '.$annee.'</td>
					<td class="col_2">'.floatval($HeuresTheoriques[($i+$ajout+5)%7]).'</td>
					<td class="col_2" bgcolor="'.$colorchecked.'">'.$NbHextrait[$i].'</td>
					<td class="col_2">'.$congeschecked.'</td>
					<td class="col_2">'.$maladiechecked.'</td>
					<td class="col_3" bgcolor="transparent">'.$NbRemextrait[$i].'</td>
					</tr>' ;
				 } ;

				$resultatrenvoye .= '</tbody></table>' ;
				$resultatrenvoye .= '</td></tr></tbody></table></div></form>' ;


		return $resultatrenvoye ;

		// Close the database connection
		$sql->close();
} ;



function Sauvegarde ( $mois , $annee , $user_id , $info )
{
	$maxdaymois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee) ;

	$fichier_csv = '1-sauvegarde/'.$user_id.'-'.$annee.'-'.$mois.'.csv';

	$stockage = '' ;
	$stockage = $info['HeuresTotal'].';'.$info['JoursCongesPris'].';'.$info['JoursCongesRestant'] ;
	$stockage .= ';'.$info['JoursMaladiePris'].';'.$info['TotalHeuresMaladiePrises'] ;

	$stockage .= ';'.$info['HeuresTotal'].';'.$info['JoursCongesPris'].';'.$info['JoursCongesRestant'] ;
	$stockage .= ';'.$info['JoursMaladiePris'].';'.$info['TotalHeuresMaladiePrises'] ;

	for ($i=1;$i<=$maxdaymois;$i++)
		{ if ($info['Conges'][$i] == "on") { $info['Conges'][$i] = "TRUE" ; }
		    else $info['Conges'][$i] = "FALSE" ;
		  if ($info['Maladie'][$i] == "on") { $info['Maladie'][$i] = "TRUE" ; }
		    else $info['Maladie'][$i] = "FALSE" ;
		  $stockage .= ';'.$info['Heures'][$i].';'.$info['Conges'][$i].';'.$info['Maladie'][$i].';'.$info['Remarque'][$i] ;
		  } ;

	file_put_contents($fichier_csv, $stockage) ;

} ;



function Efface ( $mois , $annee , $user_id )
{
	$fichier_csv = '1-sauvegarde/'.$user_id.'-'.$annee.'-'.$mois.'.csv';

	unlink($fichier_csv);

} ;



function Efface_admin ( $mois , $annee , $user_id )
{
	$fichier_demande_csv = '2-demande/'.$user_id.'-'.$annee.'-'.$mois.'.csv';

	unlink($fichier_demande_csv);

} ;



function Soumettre ( $mois , $annee , $user_id , $info )
{
	$maxdaymois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee) ;

	$stockage = '' ;
	$stockage = $info['HeuresTotal'].';'.$info['JoursCongesPris'].';'.$info['JoursCongesRestant'] ;
	$stockage .= ';'.$info['JoursMaladiePris'].';'.$info['TotalHeuresMaladiePrises'] ;

	$stockage .= ';'.$info['HeuresTotal'].';'.$info['JoursCongesPris'].';'.$info['JoursCongesRestant'] ;
	$stockage .= ';'.$info['JoursMaladiePris'].';'.$info['TotalHeuresMaladiePrises'] ;

	for ($i=1;$i<=$maxdaymois;$i++)
		{ if ($info['Conges'][$i] == "on") { $info['Conges'][$i] = "TRUE" ; }
		    else $info['Conges'][$i] = "FALSE" ;
		  if ($info['Maladie'][$i] == "on") { $info['Maladie'][$i] = "TRUE" ; }
		    else $info['Maladie'][$i] = "FALSE" ;
		  $stockage .= ';'.$info['Heures'][$i].';'.$info['Conges'][$i].';'.$info['Maladie'][$i].';'.$info['Remarque'][$i] ;
		  } ;

	$fichier_csv = '1-sauvegarde/'.$user_id.'-'.$annee.'-'.$mois.'.csv';

	file_put_contents($fichier_csv, $stockage) ;

	$fichier_demande_csv = '2-demande/'.$user_id.'-'.$annee.'-'.$mois.'.csv';

	file_put_contents($fichier_demande_csv, $stockage) ;

} ;


$mois_etudie = 3 ;
if ($_POST['idemploye']!='') { $idemploye = $_POST['idemploye'] ; }
 elseif ($_POST['user_id']!='') { $idemploye = $_POST['user_id'] ; }
  else exit ;
$an = 2021 ;

if ($_POST['Statut'] == "Sauvegarder")
   { Sauvegarde ( $mois_etudie , $an , $idemploye, $_POST ) ;
     echo AfficheMois ( $mois_etudie , $an , $idemploye ) ; }
   else if ($_POST['Statut'] == "Soumettre")
     { Soumettre ( $mois_etudie , $an , $idemploye, $_POST ) ;
	   $valeurpdf = AfficheMois ( $mois_etudie , $an , $idemploye ) ;
	   echo AfficheMois_valide ( $mois_etudie , $an , $idemploye , $valeurpdf) ;
	    }
   else if ($_POST['Statut'] == "Reinitialiser")
     { Efface ( $mois_etudie , $an , $idemploye ) ;
	   echo AfficheMois ( $mois_etudie , $an , $idemploye ) ; }
   else if ($_POST['Statut'] == "Annule Demande")
     { Efface_admin ( $mois_etudie , $an , $idemploye ) ;
	   echo AfficheMois ( $mois_etudie , $an , $idemploye ) ; }
   else
     { echo AfficheMois ( $mois_etudie , $an , $idemploye ) ; } ;


?>

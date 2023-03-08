<style type="text/css">
table.border_1_solid_black td { border : 0px solid black; }

.col_1{
position:relative;
line-height:15px;
width:220px
}
.col_2{
 position:relative;
 line-height:20px;
 width: 50%;
 text-align:left;
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
</script>

<?php

//calcul du score (chaque paramètre est sur 100)
function CalculScore ( $ValeursEntree )
{
	$ValeurSortie = array (0, 0, 0, 0, 0, 0, 1) ;

	// Q1:Sexe : (0) Masculin - (1) Féminin
	$Q1Valeurs = array () ;
	$Q1Valeurs[0] = array (1, 0, 0.8, 0.1, 0.6, 1, 1) ;
	$Q1Valeurs[1] = array (0, 1, 1, 1, 1, 1, 1) ;
	$Q1 = $Q1Valeurs[$ValeursEntree[0]] ;

	// Q2:Age : (0) 6-10ans - (1) 10-14ans - (2) +14ans
	$Q2Valeurs = array () ;
	$Q2Valeurs[0] = array (1, 1, 1, 1, 0.75, 0, 0) ;
	$Q2Valeurs[1] = array (1, 1, 1, 1, 1, 0.5, 0.5) ;
	$Q2Valeurs[2] = array (1, 1, 1, 1, 1, 1, 1) ;
	$Q2 = $Q2Valeurs[$ValeursEntree[1]] ;

	// Q3:Danser : (0) Oui - (1) Non
	$Q3Valeurs = array () ;
	$Q3Valeurs[0] = array (1, 8, 8, 10, 10, 1, 3) ;
	$Q3Valeurs[1] = array (10, 3, 3, 1, 1, 10, 8) ;
	$Q3 = $Q3Valeurs[$ValeursEntree[2]] ;

	// Q4:Equipe : (0) Oui - (1) Non
	$Q4Valeurs = array () ;
	$Q4Valeurs[0] = array (1, 1, 1, 1, 1, 1, 1) ;
	$Q4Valeurs[1] = array (1, 1, 0.2, 0.75, 0.9, 1, 1) ;
	$Q4 = $Q4Valeurs[$ValeursEntree[3]] ;

	// Q5:Souplesse : (0) Oui - (1) Non
	$Q5Valeurs = array () ;
	$Q5Valeurs[0] = array (10, 10, 10, 10, 10, 5, 5) ;
	$Q5Valeurs[1] = array (10, 10, 1, 3, 3, 8, 7) ;
	$Q5 = $Q5Valeurs[$ValeursEntree[4]] ;

	// Q6:Vertige : (0) Oui - (1) Non
	$Q6Valeurs = array () ;
	$Q6Valeurs[0] = array (2, 2, 4, 10, 8, 5, 3) ;
	$Q6Valeurs[1] = array (10, 10, 8, 2, 2, 5, 10) ;
	$Q6 = $Q6Valeurs[$ValeursEntree[5]] ;

	// Q7:Acrobaties : (0) Oui - (1) Non
	$Q7Valeurs = array () ;
	$Q7Valeurs[0] = array (10, 10, 8, 3, 6, 3, 10) ;
	$Q7Valeurs[1] = array (3, 3, 6, 8, 4, 9, 2) ;
	$Q7 = $Q7Valeurs[$ValeursEntree[6]] ;

	// Q8:Persévérance : (0) Oui - (1) Non
	$Q8Valeurs = array () ;
	$Q8Valeurs[0] = array (10, 10, 8, 10, 8, 8, 4) ;
	$Q8Valeurs[1] = array (4, 4, 5, 4, 5, 5, 8) ;
	$Q8 = $Q8Valeurs[$ValeursEntree[7]] ;

	// Calcul
	for ($i=0; $i<7; $i++)
	{ $ValeurSortie[$i] = $Q1[$i]*$Q2[$i]*$Q4[$i]*($Q3[$i]+$Q5[$i]+$Q6[$i]+$Q7[$i]+$Q8[$i]) ;
	  $ValeurSortie[$i] = round($ValeurSortie[$i]/5*10)
    ; } ;
	var_dump($ValeurSortie);
	// Disciplines : (0) GAM - (1) GAF - (2) GAc - (3) GR - (4) AER - (5) CrsFt - (6) Parkour
	return $ValeurSortie ;

} ;

//Affichage du Test
function AfficheTest ()
{

	if ($_POST <> NULL) $Options = $_POST ;

	$resultatrenvoye .= '<form action="determinesectionjeune.php" method="post">' ;
	$resultatrenvoye .= '<div align="center"><table border="5px"><tbody align="left"><tr><td>' ;
	$resultatrenvoye .= '<table align="center"><tbody><tr><td>
				<input type="submit" name="Statut" value="Envoyer">
				</td></tr></tbody></table><hr>' ;

	$resultatrenvoye .= '<h1>Test de Détermination d\'Activité</h1>' ;
	$resultatrenvoye .= '<h3>Concerne les enfants à partir de 6 ans</h3>' ;
	$resultatrenvoye .= '<p>Veuillez répondre aux 10 questions suivantes :</p><hr>' ;

	$resultatrenvoye .= '<table id="tab" border="1" bgcolor="#FFFFFF"><body>';

	if ($Options[0] == 1) { $Option['Sexe'][1] = 'checked' ;  $Option['Sexe'][0] = '' ; }
	else { $Option['Sexe'][0] = 'checked' ; $Option['Sexe'][1] = '' ; } ;
	$resultatrenvoye .= '   <tr>
		  					<td>Je suis un(e) : </td>
		  						<td><input type="radio" id="0" name="0" value="0" '.$Option['Sexe'][0].'><label for="0">Garçon</label></td>
								<td><input type="radio" id="0" name="0" value="1" '.$Option['Sexe'][1].'><label for="0">Fille</label></td>
							</tr>
							';

	if ($Options[1] == 2) { $Option['Age'][2] = 'checked' ; $Option['Age'][1] = '' ;  $Option['Age'][0] = '' ; }
	else
		{ if ($Options[1] == 1) { $Option['Age'][1] = 'checked' ; $Option['Age'][2] = '' ;  $Option['Age'][0] = '' ; }
		  else { $Option['Age'][0] = 'checked' ; $Option['Age'][1] = '' ;  $Option['Age'][2] = '' ;  } ;
		  } ;
	$resultatrenvoye .= '   <tr>
							<td>Mon âge :</td>
		  						<td><input type="radio" id="1" name="1" value="0" '.$Option['Age'][0].'><label for="1">6-10 ans</label><br />
								<td><input type="radio" id="1" name="1" value="1" '.$Option['Age'][1].'><label for="1">10-14 ans</label><br />
								<td><input type="radio" id="1" name="1" value="2" '.$Option['Age'][2].'><label for="1">+ de 14 ans</label>
							</td>
							</tr>';

	if ($Options[2] == 1) { $Option['Danse'][1] = 'checked' ;  $Option['Danse'][0] = '' ; }
	else { $Option['Danse'][0] = 'checked' ; $Option['Danse'][1] = '' ; } ;
	$resultatrenvoye .= '   <tr>
		  					<td>J\'aime danser :</td>
		  						<td><input type="radio" id="2" name="2" value="0" '.$Option['Danse'][0].'><label for="2">Oui</label></td>
								<td><input type="radio" id="2" name="2" value="1" '.$Option['Danse'][1].'><label for="2">Non</label></td>
							</tr>
							';

	if ($Options[3] == 1) { $Option['Equipe'][1] = 'checked' ;  $Option['Equipe'][0] = '' ; }
	else { $Option['Equipe'][0] = 'checked' ; $Option['Equipe'][1] = '' ; } ;
	$resultatrenvoye .= '   <tr>
							<td>J\'aime travailler en équipe :</td>
		  						<td><input type="radio" id="3" name="3" value="0" '.$Option['Equipe'][0].'><label for="3">Oui</label></td>
								<td><input type="radio" id="3" name="3" value="1" '.$Option['Equipe'][1].'><label for="3">Non</label></td>
							</tr>';

	if ($Options[4] == 1) { $Option['Souple'][1] = 'checked' ;  $Option['Souple'][0] = '' ; }
	else { $Option['Souple'][0] = 'checked' ; $Option['Souple'][1] = '' ; } ;
	$resultatrenvoye .= '   <tr>
		  					<td>Je suis souple :</td>
		  						<td><input type="radio" id="4" name="4" value="0" '.$Option['Souple'][0].'><label for="4">Oui</label></td>
								<td><input type="radio" id="4" name="4" value="1" '.$Option['Souple'][1].'><label for="4">Non</label></td>
							</tr>
							';

	if ($Options[5] == 1) { $Option['Vertige'][1] = 'checked' ;  $Option['Vertige'][0] = '' ; }
	else { $Option['Vertige'][0] = 'checked' ; $Option['Vertige'][1] = '' ; } ;
	$resultatrenvoye .= '   <tr>
							<td>J\'ai le vertige :</td>
		  						<td><input type="radio" id="5" name="5" value="0" '.$Option['Vertige'][0].'><label for="5">Oui</label></td>
								<td><input type="radio" id="5" name="5" value="1" '.$Option['Vertige'][1].'><label for="5">Non</label></td>
							</tr>';

	if ($Options[6] == 1) { $Option['Acro'][1] = 'checked' ;  $Option['Acro'][0] = '' ; }
	else { $Option['Acro'][0] = 'checked' ; $Option['Acro'][1] = '' ; } ;
	$resultatrenvoye .= '   <tr>
							<td>J\'aime faire des Acrobaties :</td>
		  						<td><input type="radio" id="6" name="6" value="0" '.$Option['Acro'][0].'><label for="6">Oui</label></td>
								<td><input type="radio" id="6" name="6" value="1" '.$Option['Acro'][1].'><label for="6">Non</label></td>
							</tr>';

	if ($Options[7] == 1) { $Option['Persev'][1] = 'checked' ;  $Option['Persev'][0] = '' ; }
	else { $Option['Persev'][0] = 'checked' ; $Option['Persev'][1] = '' ; } ;
	$resultatrenvoye .= '   <tr>
							<td>Je ne lâche rien (même si c\'est difficile) :</td>
		  						<td><input type="radio" id="7" name="7" value="0" '.$Option['Persev'][0].'><label for="7">Oui</label></td>
								<td><input type="radio" id="7" name="7" value="1" '.$Option['Persev'][1].'><label for="7">Non</label></td>
							</tr>';

	$resultatrenvoye .= '</tbody></table>' ;
	$resultatrenvoye .= '</td></tr></tbody></table></div></form>' ;

		return $resultatrenvoye ;
} ;



function AfficheScore ( $ResultatsTest )
{
		$ResultatsTest1[0] = 2*$ResultatsTest[0] ;
		$ResultatsTest1[1] = 2*$ResultatsTest[1] ;
		$ResultatsTest1[2] = 2*$ResultatsTest[2] ;
		$ResultatsTest1[3] = 2*$ResultatsTest[3] ;
		$ResultatsTest1[4] = 2*$ResultatsTest[4] ;
		$ResultatsTest1[5] = 2*$ResultatsTest[5] ;
		$ResultatsTest1[6] = 2*$ResultatsTest[6] ;

		$resultatrenvoye = '<table align="center" border ="1px"><tbody><tr ALIGN=CENTER VALIGN=BOTTOM>
							<td>GAM</td><td>GAF</td><td>GAc</td><td>GR</td><td>AER</td><td>Cross<br>Training</td><td>Parkour</td></tr>' ;
		for ($i=0; $i<7; $i++)
		  { if ($ResultatsTest1[$i] == 0) { $SensInterdit[$i] = '<img src="SensInterdit.png" alt="GAM" width="40">' ; $AffHisto[$i] = '' ; }
		     else { $SensInterdit[$i] = '' ; $AffHisto[$i] = '<img src="Histo.png" alt="GAM" width="40" height="'.$ResultatsTest1[$i].'">' ; } ;
			 } ;
		$resultatrenvoye .=  '<tr HEIGHT=200px ALIGN=CENTER VALIGN=BOTTOM>
							  <td>'.$AffHisto[0].$SensInterdit[0].'</td>';
		$resultatrenvoye .=  '<td>'.$AffHisto[1].$SensInterdit[1].'</td>';
		$resultatrenvoye .=  '<td>'.$AffHisto[2].$SensInterdit[2].'</td>';
		$resultatrenvoye .=  '<td>'.$AffHisto[3].$SensInterdit[3].'</td>';
		$resultatrenvoye .=  '<td>'.$AffHisto[4].$SensInterdit[4].'</td>';
		$resultatrenvoye .=  '<td>'.$AffHisto[5].$SensInterdit[5].'</td>' ;
		$resultatrenvoye .=  '<td>'.$AffHisto[6].$SensInterdit[6].'</td></tr>';

		$resultatrenvoye .= '<tr ALIGN=CENTER VALIGN=BOTTOM><td>'.$ResultatsTest[0].' %</td><td>'.$ResultatsTest[1].' %</td><td>'.$ResultatsTest[2].' %</td><td>'.$ResultatsTest[3].' %</td><td>'.$ResultatsTest[4].' %</td><td>'.$ResultatsTest[5].' %</td><td>'.$ResultatsTest[6].' %</td></tr></tbody></table>' ;


		return $resultatrenvoye ;
} ;


// Début

if ($_POST['Statut'] == "Envoyer")
   { echo AfficheScore (CalculScore ( $_POST )) ;
     echo "<br><hr><br>" ;
     echo AfficheTest () ; }
   else
     { echo AfficheTest () ; } ;


?>

<?php

function diffMois($dateDebut)
	{
		$dtDeb = new DateTime($dateDebut); //echo $dtDeb->format('Y-m');
		$dtFin = new DateTime(); //echo $dtFin->format('Y-m');
		$interval = $dtDeb->diff($dtFin);
		$nbmonth= $interval->format('%m');
		$nbyear = $interval->format('%y');
		return 12 * $nbyear + $nbmonth;
	}



function CalculSalaireCCNS($HeuresSemaine,$Groupe,$DateEmbauche,$SMCMensuel,$SMICMensuel)
	{
		$CoeffGroupe = array (0, 6, 9, 18, 24.75, 39.72, 74.31, 24.88, 28.86 ) ;
		$SMCGroupe = $SMCMensuel*(100+$CoeffGroupe[$Groupe])/100 ;
		if ($Groupe>6) { $SMCGroupe = $SMCMensuel*$CoeffGroupe[$Groupe]/12 ;} ;

		if ($SMICMensuel>$SMCGroupe) {$SMin = $SMICMensuel ;}
			else {$SMin = $SMCGroupe ;} ;

		$SalaireMinimum = $SMin/35*$HeuresSemaine ;

		if ($HeuresSemaine<=10) { $SalaireMinimum = $SalaireMinimum*1.05 ;}
		 elseif ($HeuresSemaine<=20) { $SalaireMinimum = $SalaireMinimum*1.02 ;}
		   else { $SalaireMinimum = $SalaireMinimum*1.00 ;}

		return $SalaireMinimum ;
	}



function CalculPrimeCCNS($HeuresSemaine,$GroupeInput,$DateEmbauche,$SMCMensuel,$SMICMensuel)
	{
		$Groupe = 3;

		$CoeffGroupe = array (0, 6, 9, 18, 24.75, 39.72, 74.31, 24.88, 28.86 ) ;
		$SMCGroupe = $SMCMensuel*(100+$CoeffGroupe[$Groupe])/100 ;
		if ($Groupe>6) { $SMCGroupe = $SMC*$CoeffGroupe[$Groupe]/12 ;} ;

		if ($SMICMensuel>$SMCGroupe) {$SMin = $SMICMensuel ;}
			else {$SMin = $SMCGroupe ;} ;

		$SalaireMinimum = $SMin/35*$HeuresSemaine ;

		if ($HeuresSemaine<=10) { $SalaireMinimum = $SalaireMinimum*1.05 ;}
		 elseif ($HeuresSemaine<=20) { $SalaireMinimum = $SalaireMinimum*1.02 ;}
		   else { $SalaireMinimum = $SalaireMinimum*1.00 ;}

		$RefPrime = $SalaireMinimum*0.01 ;

		$Prime = intdiv(diffMois($DateEmbauche),24)*$RefPrime ;

		if (($GroupeInput==1)&&(diffMois($DateEmbauche)>35)) {$Prime = $Prime + 5*$RefPrime ; } ;
		//echo diffMois($DateEmbauche)."  -  ";

		return $Prime ;
	}

$Groupe = 1 ; $HeuresSemaine = 35 ; $DateEmbauche = "2016-12-01";


function AfficheSalarie($user_id)
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
    		$VariableBDD[12] = $ligne["SoldeConges"];
    		$VariableBDD[13] = $ligne["matricule"];
    		$VariableBDD[14] = $ligne["email"];
			$VariableBDD[15] = $ligne["Groupe"];
			$VariableBDD[16] = $ligne["Embauche"];
			$VariableBDD[17] = $ligne["Salaire"];
			$VariableBDD[18] = $ligne["Prime"];
			}

		/* Libération du jeu de résultats */
		$result->close();
	}


		$resultatrenvoye = '' ;

		$NomEmploye = $VariableBDD[1] ;
		$PrenomEmploye = $VariableBDD[2] ;
		$VolumeHebdo = $VariableBDD[3] ;
		$Matricule = $VariableBDD[13] ;
		$SoldeConges = $VariableBDD[12] ;
		$EmailEmploye = $VariableBDD[14] ;
		$Groupe = $VariableBDD[15] ;
		$Embauche = $VariableBDD[16] ;
		$Salaire = $VariableBDD[17] ;
		$Prime = $VariableBDD[18] ;

		$resultatrenvoye = $PrenomEmploye." ".$NomEmploye." (".$VolumeHebdo."h) - Ancienneté : ".diffMois($Embauche)." Mois : &nbsp;&nbsp;&nbsp;&nbsp;  Salaire CCNS : ".number_format(CalculSalaireCCNS ($VolumeHebdo,$Groupe,$Embauche,1469.24,1554.58),2,',','')." € &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;     -  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    Prime CCNS : ".number_format(CalculPrimeCCNS ($VolumeHebdo,$Groupe,$Embauche,1469.24,1554.58),2,',','')." €<br>" ;



		return $resultatrenvoye ;

		// Close the database connection
		$sql->close();
} ;


$Salaries = array (149, 381, 144, 4079, 4086, 5271) ;
for ($i=0;$i<6;$i++)
 echo AfficheSalarie($Salaries[$i]) ;

?>

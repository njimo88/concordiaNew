<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;
require_once(app_path().'/fonction.php');



class CalculSalaire extends Model
{

	function diffMois($dateDebut)
	{
		$dtDeb = new DateTime($dateDebut);
		$dtFin = new DateTime();
		$interval = $dtDeb->diff($dtFin);
		$nbmonth = $interval->format('%m');
		$nbyear = $interval->format('%y');
		return 12 * $nbyear + $nbmonth;
	}

	function CalculSalaireCCNS($HeuresSemaine, $Groupe, $DateEmbauche, $SMCMensuel, $SMICMensuel)
	{
		$CoeffGroupe = array(0, 6, 9, 18, 24.75, 39.72, 74.31, 24.88, 28.86);
		$SMCGroupe = $SMCMensuel * (100 + $CoeffGroupe[$Groupe]) / 100;
		if ($Groupe > 6) {
			$SMCGroupe = $SMCMensuel * $CoeffGroupe[$Groupe] / 12;
		};

		if ($SMICMensuel > $SMCGroupe) {
			$SMin = $SMICMensuel;
		} else {
			$SMin = $SMCGroupe;
		};

		$SalaireMinimum = $SMin / 35 * $HeuresSemaine;

		if ($HeuresSemaine <= 10) {
			$SalaireMinimum = $SalaireMinimum * 1.05;
		} elseif ($HeuresSemaine <= 20) {
			$SalaireMinimum = $SalaireMinimum * 1.02;
		} else {
			$SalaireMinimum = $SalaireMinimum * 1.00;
		}
		return $SalaireMinimum;
	}

	function CalculPrimeCCNS($HeuresSemaine, $GroupeInput, $DateEmbauche, $SMCMensuel, $SMICMensuel)
	{
		$Groupe = 3;

		$CoeffGroupe = array(0, 6, 9, 18, 24.75, 39.72, 74.31, 24.88, 28.86);
		$SMCGroupe = $SMCMensuel * (100 + $CoeffGroupe[$Groupe]) / 100;
		if ($Groupe > 6) {
			$SMCGroupe = $SMC * $CoeffGroupe[$Groupe] / 12;
		};

		if ($SMICMensuel > $SMCGroupe) {
			$SMin = $SMICMensuel;
		} else {
			$SMin = $SMCGroupe;
		};

		$SalaireMinimum = $SMin / 35 * $HeuresSemaine;

		if ($HeuresSemaine <= 10) {
			$SalaireMinimum = $SalaireMinimum * 1.05;
		} elseif ($HeuresSemaine <= 20) {
			$SalaireMinimum = $SalaireMinimum * 1.02;
		} else {
			$SalaireMinimum = $SalaireMinimum * 1.00;
		}

		$RefPrime = $SalaireMinimum * 0.01;

		$Prime = intdiv($this->diffMois($DateEmbauche), 24) * $RefPrime;

		if (($GroupeInput == 1) && ($this->diffMois($DateEmbauche) > 35)) {
			$Prime = $Prime + 5 * $RefPrime;
		};

		return $Prime;
	}



	function AfficheSalarie($user_id)
	{
		$professional = DB::table('users_professionals')
                ->where('id_user', $user_id)
                ->first();

	
		$VariableBDD[0] = $professional->id_user;
		$VariableBDD[1] = utf8_decode($professional->lastname);
		$VariableBDD[2] = utf8_decode($professional->firstname);
		$VariableBDD[3] = $professional->VolumeHebdo;
		$VariableBDD[12] = $professional->SoldeConges;
		$VariableBDD[13] = $professional->matricule;
		$VariableBDD[14] = $professional->email;
		$VariableBDD[15] = $professional->Groupe;
		$VariableBDD[16] = $professional->Embauche;
		$VariableBDD[17] = $professional->Salaire;
		$VariableBDD[18] = $professional->Prime;
	
		$resultatrenvoye = [];
	
		$VolumeHebdo = $VariableBDD[3];
		$Groupe = $VariableBDD[15];
		$Embauche = $VariableBDD[16];
	
		$divers = DB::table('divers')->first();
		$SMIC = $divers->SMIC;
		$SMC = $divers->SMC;
	
		$Anciennete = $this->diffMois($Embauche);
		$SalaireMin = number_format($this->CalculSalaireCCNS($VolumeHebdo, $Groupe, $Embauche, $SMC, $SMIC), 2, ',', '');
		$PrimeAnciennete = number_format($this->CalculPrimeCCNS($VolumeHebdo, $Groupe, $Embauche, $SMC, $SMIC), 2, ',', '');
	
		$resultatrenvoye['anciennete'] = $Anciennete;
		$resultatrenvoye['salaireMin'] = $SalaireMin;
		$resultatrenvoye['primeAnciennete'] = $PrimeAnciennete;
	
		return $resultatrenvoye;
	}
	
	

	public function getProfessionals($id_user = null)
	{
		if ($id_user == null) {
			$array_professionals = DB::table('users_professionals')->get()->toArray();
		} else {
			$array_professionals = DB::table('users_professionals')->where('id_user', $id_user)->get()->toArray();
		}
	
		return $array_professionals;
	}
}

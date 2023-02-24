<?php
function Savepdf ( $mois , $annee , $user_id , $valeurpdf)
{
	//Génération pdf
	ob_start();
	$stockage1 = '' ;
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
					</style>' ;
	$stockage1 .= '<page  orientation="portrait" backtop="30mm" backbottom="0mm" backleft="3mm" backright="3mm">';
	$stockage1 .= '<page_header><p align="center"><img class="center" src="https://www.gym-concordia.com/assets/imgs/Entete.jpg" width="800"></p></page_header>';
	$stockage1 .= $valeurpdf ;
	$stockage1 .= '</page>';

	require_once("../application/libraries/html2pdf-old/html2pdf.class.php");

	$pdf = ob_get_clean();
	ob_end_clean();

	$pdf = new HTML2PDF('P','A4','fr', array(0, 0, 0, 0));

	$pdf->writeHTML($stockage1);

	$fichier_pdf = '2-demande/'.$user_id.'-'.$annee.'-'.$mois.'.pdf';

	$pdf->output($fichier_pdf, 'F');

}



function Envoi_mail ( $mois , $annee , $user_id , $user)
{
	    $JourSemaine = array ("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche") ;
		$Moislettres = array ("", "Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre") ;

		$user_prenom = strtr(
				html_entity_decode($user[1], ENT_QUOTES,'UTF-8'),
				'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
				'aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
			);
		$user_nom = strtr(
				html_entity_decode($user[0], ENT_QUOTES,'UTF-8'),
				'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
				'aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
			);

     	// Plusieurs destinataires
		$to  = "President - Gym Concordia <president@gym-concordia.com>";
		$to .=  ', '.'Tresorier - Gym Concordia <tresorier@gym-concordia.com>'; // notez la virgule
		if ($_POST['user_id']<>4079) { $to .=  ', '.'Tresorier - Gym Concordia <tresorier@gym-concordia.com>'; } // notez la virgule
		  else { $to .=  ', '.'Thibaut Gurtler - Gym Concordia <thibaut.gurtler@gym-concordia.com>'; } ;

     	// Infos
     	$subject = $Moislettres[$mois].' '.$annee.' - Fiche Heures';

		$message1 = '<i>[Ceci est un message automatique]</i><br><br>Bonjour,<br><br>
Veuillez trouver ci-joint ma déclaration d\'heures pour le mois de : <b>'.$Moislettres[$mois].' '.$annee.'</b>.<br><br>
Cordialement<br>
<img src="https://www.gym-concordia.com/Signatures/Signature-'.strtoupper(substr($user_prenom, 0, 1)).strtolower(substr($user_prenom, 1)).'-'.strtoupper(substr($user_nom, 0, 1)).strtolower(substr($user_nom, 1)).'.png">' ;

		$message = html_entity_decode($message1, ENT_QUOTES,'UTF-8') ;

		// echo $message ; exit ;

		$encoding = "utf-8";

		$filename = $_POST['user_id'].'-'.$_POST['annee'].'-'.$_POST['mois'].'.pdf';
		$file = '2-demande/'.$_POST['user_id'].'-'.$_POST['annee'].'-'.$_POST['mois'].'.pdf';
		$content = file_get_contents( $file);
		$content = chunk_split(base64_encode($content));
		$uid = md5(uniqid(time()));
		$name = basename($file);

		// header

		$header = "From: ".$user_prenom." ".$user_nom." <".$user[2]."> \r\n";
	    $header .= "Cc: ".$user_prenom." ".$user_nom." <".$user[2]."> \r\n";
     	// $header .= 'Bcc: anniversaire_verif@example.com' . "\r\n";
		$header .= "Reply-To: ".$user_prenom." ".$user_nom." <".$user[2]."> \r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";

		// message & attachment
		$nmessage = "--".$uid."\r\n";
		$nmessage .= "Content-type:text/html; charset=utf-8\r\n";
		$nmessage .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
		$nmessage .= $message."\r\n\r\n";
		$nmessage .= "--".$uid."\r\n";
		$nmessage .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
		$nmessage .= "Content-Transfer-Encoding: base64\r\n";
		$nmessage .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
		$nmessage .= $content."\r\n\r\n";
		$nmessage .= "--".$uid."--";

    // Send mail
    	mail($to, $subject, $nmessage, $header);

} ;


if ($_POST['Statut'] == "Valider")
   { Savepdf ( $_POST['mois'] , $_POST['annee'] , $_POST['user_id'], $_POST['valeurpdf'] ) ;
     Envoi_mail ( $_POST['mois'] , $_POST['annee'] , $_POST['user_id'], $_POST['user'] ) ;
     echo "Demande Envoyée" ;
     }
   else
     {
	 	$fichier_demande_csv = '2-demande/'.$_POST['user_id'].'-'.$_POST['annee'].'-'.$_POST['mois'].'.csv';
		unlink($fichier_demande_csv);
		include ("./affichheures.php") ; } ;

?>

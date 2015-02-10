<?php

echo '<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Transitional//EN\' \'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\'>';
echo '<html xmlns=\'http://www.w3.org/1999/xhtml\'>';
echo '<head>';
echo '<meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\' />';
echo '<meta name=\'author\' content=\'Région Poitou-Charentes\'>';
echo '<meta name=\'Identifier-URL\' content=\'http://www.poitou-charentes.fr/accueil.html\'>';
echo '<meta http-equiv=\'Content-Language\' content=\'fr\'>';
echo '<meta name=\'language\' content=\'fr\'>';
echo '<meta name=\'keywords\' content=\'Borne,Voiture,Recharge,Electrique,Région,Poitou-Charentes\'>';
echo '<title>Bornes de recharge &eacute;lectrique</title>';
// echo '<link rel=\'stylesheet\' media=\'all\' type=\'text/css\' href=\'./css/style_bornes_electriques.css\'>';
echo '<link rel=\'stylesheet\' media=\'screen\' type=\'text/css\' href=\'./css/style_bornes_electriques.css\'>';
echo '<link rel=\'stylesheet\' media=\'print\' type=\'text/css\' href=\'./css/style_bornes_electriques_imprimante.css\'>';
echo '</head>';

	require_once('./config/con_postgres.php');
	// require_once('variable.php');

echo '<body>';
echo '<div id=\'conteneur\'>';

		echo '<div id=\'principal\'>';
		echo '<div id=\'cadre_vert\'>';
/******************************************* MESSAGE SI LA BASE DE DONNEES A DES PROBLEME **************************************************************/		
/*	if(! $sql_fiche_borne){
	echo '<div align=\'center\'>';
	echo '<BR /><BR /><BR /><BR />';
	echo '<BR /><BR /><BR /><BR />';
	echo '<span class=\'gras_titre2\'>';
	echo "Veuillez nous excuser,<BR />la connexion &agrave; la base de donn&eacute;es<BR />&laquo; des bornes &eacute;lectriques en r&eacute;gion Poitou-Charentes &raquo;<BR />est momentan&eacute;ment indisponible.";
	echo '</span >';
	echo '<BR /><BR />';
	echo '<img class=\'displayed\' src=\'images/photos/photo_par_default.gif\' alt=\'Imagette d&acute;une voiture &eacute;lectrique\' title=\'Imagette d&acute;une voiture &eacute;lectrique\' border=\'0\' />';
	echo '</div>';
	echo '<BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR />';
	}else{*/
/******************************************* TEST SI LA BASE A DES PROBLEME **************************************************************/
	  if ($_REQUEST['code'] != ''){
	  $sql_fiche_borne = "SELECT DISTINCT tlrecharge_code, tlrecharge_libelle, tlrecharge_adresse, tlrecharge_code_postal,tlrecharge_ville, tlrecharge_x_wgs84, tlrecharge_y_wgs84, tlrecharge_commentaire, tlrecharge_photo, tgest_code, tborne_modalite, tborne_paiement, tlrecharge_date_crea, tlrecharge_date_modif FROM bd_borne_elec.v_lieu_recharge where v_lieu_recharge.tlrecharge_code = '".$_REQUEST['code']."' LIMIT 1";
	  $req_fiche_borne = pg_query($sql_fiche_borne) or die ('Error in query procedural --> '.pg_last_error());
	  $nb_total_fiche_borne = pg_num_rows($req_fiche_borne);

		if ($nb_total_fiche_borne == 0){
		echo '<div align=\'center\'>';
		echo '<BR /><BR /><BR /><BR />';
		echo '<BR /><BR /><BR /><BR />';
		echo '<BR /><BR /><BR /><BR />';
		echo '<span class=\'gras_titre2\'>';
		echo 'D&eacute;sol&eacute;, aucune information<BR />n&acute;est disponible sur votre requ&ecirc;te';
		echo '</span >';
		echo '<BR /><BR />';
		echo '<img class=\'displayed\' src=\'images/photos/photo_par_default.jpg\' alt=\'Imagette d&acute;une voiture &eacute;lectrique\' title=\'Imagette d&acute;une voiture &eacute;lectrique\' border=\'0\' />';
		echo '</div>';
		}

while($row_fiche_borne = pg_fetch_array($req_fiche_borne)) {
	
			$var_id = NULL;
			$var_code = NULL;
			$var_lieu_recharge = NULL;
			$var_adresse_recharge = NULL;
			$var_cp_recharge = NULL;
			$var_ville_recharge = NULL;
			$var_proprietaire_recharge = NULL;
			$var_x_recharge = NULL;
			$var_y_recharge = NULL;
			$var_date_crea = NULL;
			$var_date_modif = NULL;
			$var_capacite_recharge = NULL;
			$var_commentaire_recharge = NULL;
			$var_photos = NULL;
			$var_gestionnaire = NULL;
			$var_modalite = NULL;
			$var_paiement = NULL;
			
			$var_id = $row_fiche_borne['tlrecharge_id'];
			$var_code = $row_fiche_borne['tlrecharge_code'];
			$var_lieu_recharge = $row_fiche_borne['tlrecharge_libelle'];
			$var_adresse_recharge = $row_fiche_borne['tlrecharge_adresse'];
			$var_cp_recharge = $row_fiche_borne['tlrecharge_code_postal'];
			$var_ville_recharge = $row_fiche_borne['tlrecharge_ville'];
			$var_x_recharge = $row_fiche_borne['tlrecharge_x_wgs84'];
			$var_y_recharge = $row_fiche_borne['tlrecharge_y_wgs84'];
			$var_capacite_recharge = $row_fiche_borne['tlrecharge_capacite'];
			// $var_commentaire_recharge = $row_fiche_borne['tlrecharge_commentaire'];
			$var_photos = $row_fiche_borne['tlrecharge_photo'];
			$var_num_gestionnaire = $row_fiche_borne['tgest_code'];
			// $var_code_bornes = $row_fiche_borne['tborne_code'];
			$var_modalite = $row_fiche_borne['tborne_modalite'];
			$var_paiement = $row_fiche_borne['tborne_paiement'];
	
			list($year,$month,$day) = preg_split('/[: -]/',$row_fiche_borne['tlrecharge_date_crea']);
			switch ($month)
			{
			case 1:$mois = "janvier";break;
			case 2:$mois = "f&eacute;vrier";break;
			case 3:$mois = "mars";break;
			case 4:$mois = "avril";break;
    		case 5:$mois = "mai";break;
			case 6:$mois = "juin";break;
			case 7:$mois = "juillet";break;
			case 8:$mois = "ao&ucirc;t";break;
			case 9:$mois = "septembre";break;
			case 10:$mois = "octobre";break;
    		case 11:$mois = "novembre";break; 
			case 12:$mois = "d&eacute;cembre";break; 
			}
			$var_date_crea = $day." ".$mois." ".$year;
		
			list($year,$month,$day) = preg_split('/[: -]/',$row_fiche_borne['tlrecharge_date_modif']);
			switch ($month)
			{
			case 1:$mois = "janvier";break;
			case 2:$mois = "f&eacute;vrier";break;
			case 3:$mois = "mars";break;
			case 4:$mois = "avril";break;
    		case 5:$mois = "mai";break;
			case 6:$mois = "juin";break;
			case 7:$mois = "juillet";break;
			case 8:$mois = "ao&ucirc;t";break;
			case 9:$mois = "septembre";break;
			case 10:$mois = "octobre";break;
    		case 11:$mois = "novembre";break; 
			case 12:$mois = "d&eacute;cembre";break; 
			}
			$var_date_modif = $day." ".$mois." ".$year;
			
		echo '<div id=\'mise_jour\'>';
		if ($row_fiche_borne['tlrecharge_date_crea'] != ''){
		echo 'Mis &agrave; jour le ';
		if ($row_fiche_borne['tlrecharge_date_modif'] == ''){echo ''.$var_date_crea.' ';}else{echo ''.$var_date_modif.' ';}
		}else{echo '&nbsp;';}
		echo '</div>';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////  DIV DE DROITE  ///////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			echo '<div id=\'gauche_localisation\'>';
			echo '<span class=\'texte_gras_noir\'>';
			echo '&nbsp;&nbsp;Localisation&nbsp;';
			echo '</span>';
			echo '<BR />';
			echo '<span class=\'gras_titre3\'>';
			echo ''.$var_lieu_recharge.'';
			echo '</span >';
			echo '<BR />';
			
			echo ''.ucfirst($var_adresse_recharge).'';
			echo '&nbsp;-&nbsp;';
			
			if (($var_cp_recharge != '') && ($var_cp_recharge != "0")){
			echo ''.$var_cp_recharge.'';
			echo '&nbsp;-&nbsp;';
			}
			echo ''.ucfirst($var_ville_recharge).'';
			
			if (($var_y_recharge != '') && ($var_x_recharge != "0")){
			echo '<BR />';			
			echo '<span class=\'texte_fiche\'>';
			echo 'Coordonn&eacute;es GPS : ';
			echo '</span >';
			echo '<span class=\'gras_titre\'>';
			echo ''.$var_y_recharge.'';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			echo ''.$var_x_recharge.'';
			echo '</span >';
			}
			
			$sql_gestionnaire = "SELECT * FROM bd_borne_elec.t_gestionnaire where t_gestionnaire.tgest_code = '".$var_num_gestionnaire."'";
	  		$req_gestionnaire = pg_query($sql_gestionnaire) or die ('Error in query procedural --> '.pg_last_error());
			
			while($row_gestionnaire = pg_fetch_array($req_gestionnaire)) {
				/*tony*/
			$var_code_gestionnaire = NULL;
				/*tony*/
			$var_structure_gestionnaire = NULL;
			$var_nom_contact_gestionnaire = NULL;
			$var_prenom_contact_gestionnaire = NULL;
			$var_fonction_contact_gestionnaire = NULL;
			$var_tel_fixe_gestionnaire = NULL;
			$var_tel_fixe_bis_gestionnaire = NULL;
			$var_tel_portable_gestionnaire = NULL;
			$var_couriel_gestionnaire = NULL;
			$var_site_gestionnaire = NULL;
			
				/*tony*/
			$var_code_gestionnaire = $row_gestionnaire['tgest_code'];
				/*tony*/
			$var_structure_gestionnaire = $row_gestionnaire['tgest_libelle'];
			$var_nom_contact_gestionnaire = $row_gestionnaire['tgest_contact_nom'];
			$var_prenom_contact_gestionnaire = $row_gestionnaire['tgest_contact_prenom'];
			$var_fonction_contact_gestionnaire = $row_gestionnaire['tgest_contact_role'];
			$var_tel_fixe_gestionnaire = $row_gestionnaire['tgest_telephone'];
			$var_tel_fixe_bis_gestionnaire = $row_gestionnaire['tgest_telephone1'];
			$var_tel_portable_gestionnaire = $row_gestionnaire['tgest_portable'];
			$var_couriel_gestionnaire = $row_gestionnaire['tgest_mail'];
			$var_site_gestionnaire = $row_gestionnaire['tgest_url'];
			}
			
			
			//if ($var_structure_gestionnaire != ''){
					/*tony*/
			if (($var_structure_gestionnaire != '') AND ($var_code_gestionnaire !== "GEST_0001")){
					/*tony*/
			echo '<BR /><BR />';
			echo '<span class=\'titre_gestionnaire\'>';
			echo '&nbsp;&nbsp;Gestionnaire&nbsp;';
			echo '</span>';
			echo '<BR />';
			echo '<span class=\'gras_titre\'>';
			echo ''.$var_structure_gestionnaire.'';
			echo '</span>';
			}
			/*if ($var_nom_contact_gestionnaire != ''){
			echo '<BR />';
			echo '<span class=\'texte_fiche\'>';
			echo 'Contact : ';
			echo '</span>';
			echo '<span class=\'gras_titre\'>';
			echo ''.$var_prenom_contact_gestionnaire.' '.$var_nom_contact_gestionnaire.'';
			echo '</span>';
			}
			if ($var_fonction_contact_gestionnaire != ''){
			echo '<span class=\'texte_definition\'>';
			echo ' ('.$var_fonction_contact_gestionnaire.')';
			echo '</span>';
			}else{
			echo '<span class=\'texte_definition\'>';
			echo ''.$var_fonction_contact_gestionnaire.'';	
			echo '</span>';
			}
			if ($var_tel_fixe_gestionnaire != ''){
			echo '<BR />';
			echo '<span class=\'texte_fiche\'>';
			echo 'T&eacute;l fixe : ';
			echo '</span>';
			echo ''.$var_tel_fixe_gestionnaire.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$var_tel_fixe_bis_gestionnaire.'';
			}
			if ($var_tel_portable_gestionnaire != ''){
			echo '&nbsp;&nbsp;&nbsp;';
			echo '<span class=\'texte_fiche\'>';
			echo 'T&eacute;l portable : ';
			echo '</span>';
			echo ''.$var_tel_portable_gestionnaire.'';
			}
			if ($var_couriel_gestionnaire != ''){
			echo '<BR />';
			echo '<span class=\'texte_fiche\'>';
			echo 'Courriel : ';
			echo '</span>';
			echo '<a href=\'mailto:'.$var_couriel_gestionnaire.'\'><font color=\'#000000\'><u>'.$var_couriel_gestionnaire.'</u></font></a>';
			}
			if ($var_site_gestionnaire != ''){
			echo '<BR />';
			echo '<span class=\'texte_fiche\'>';
			echo 'Site internet : ';
			echo '</span>';
			echo '<a href=\''.$var_site_gestionnaire.'\' target=\'_blank\'><font color=\'#000000\'><u>'.$var_site_gestionnaire.'</u></font></a>';
			}
			*/
			// echo '</div>';			
			
			
			
			echo '</div>';
			
			
			echo '<div id=\'droite_imagette\'>';
			echo '<table width="100%" cellspacing="2" cellpadding="2">';
			echo '<tr>';
			echo '<td class=\'gras_titre2\' align=\'center\' valign=\'top\' width=\'40%\'>';
								

/*******************************************************************************************************************************************************************/				  
/*	  		$sql_affiche_nombre = "SELECT * FROM bd_borne_elec_tmp.v_propose where v_propose.tlrecharge_code = '".$_REQUEST['code']."'";
	  		$req_affiche_nombre = pg_query($sql_affiche_nombre) or die ('Error in query procedural --> '.pg_last_error());
	  		$nb_total_affiche_nombre = pg_num_rows($req_affiche_nombre);
			
			$sql_affiche_nombre_prise = "SELECT * FROM bd_borne_elec_tmp.v_lieu_recharge where v_lieu_recharge.tlrecharge_code = '".$_REQUEST['code']."'";
	  		$req_affiche_nombre_prise = pg_query($sql_affiche_nombre_prise) or die ('Error in query procedural --> '.pg_last_error());
	  		$nb_total_affiche_nombre_prise = pg_num_rows($req_affiche_nombre_prise);
	 		echo '<BR />';
			echo '<span class=\'chiffre_recharge\'>';
			echo '&nbsp;'.$nb_total_affiche_nombre.'&nbsp;';
			echo '</span >';
			echo '&nbsp;';
			if ($nb_total_affiche_nombre > 1){
			echo 'bornes de recharge &eacute;lectrique<BR />sur ce lieu<BR /><BR /><span class=\'gras_titre\'>avec</span><span class=\'chiffre_point_recharge\'>&nbsp;'.$nb_total_affiche_nombre_prise.'&nbsp;</span><BR /><span class=\'gras_titre\'>points de charge</span>';
			}else{
			echo 'borne de recharge &eacute;lectrique<BR />sur ce lieu<BR /><BR /><span class=\'gras_titre\'>avec</span><span class=\'chiffre_point_recharge\'>&nbsp;'.$nb_total_affiche_nombre_prise.'&nbsp;</span><BR /><span class=\'gras_titre\'>points de charge</span>';
			}*/
/*******************************************************************************************************************************************************************/			
			echo '</td>';
			echo '<td align=\'left\' valign=\'middle\'>';
			
			if ($var_photos == 't'){
			echo '<img class=\'displayed\' src=\'images/photos/'.$var_code.'.JPG\' alt=\'Photo de la borne '.ucfirst($var_adresse_recharge).' &agrave; '.ucfirst($var_ville_recharge).'\' title=\'Photo de la borne '.ucfirst($var_adresse_recharge).' &agrave; '.ucfirst($var_ville_recharge).'\' border=\'0\' />';
			}else{
			echo '<img class=\'displayed\' src=\'images/photos/photo_par_default1.JPG\' alt=\'Imagette d&acute;une voiture &eacute;lectrique\' title=\'Imagette d&acute;une voiture &eacute;lectrique\' border=\'0\' />';
			}
			
			echo '</td>';
			echo '</tr>';
			echo '</table>';
			
			echo '</div>';
			
			echo '<div id=\'gauche_vitesse\'>';
	  		$sql_affiche_nombre = "SELECT * FROM bd_borne_elec.v_propose where v_propose.tlrecharge_code = '".$_REQUEST['code']."'";
	  		$req_affiche_nombre = pg_query($sql_affiche_nombre) or die ('Error in query procedural --> '.pg_last_error());
	  		$nb_total_affiche_nombre = pg_num_rows($req_affiche_nombre);
			
			$sql_affiche_nombre_prise = "SELECT * FROM bd_borne_elec.v_lieu_recharge where v_lieu_recharge.tlrecharge_code = '".$_REQUEST['code']."'";
	  		$req_affiche_nombre_prise = pg_query($sql_affiche_nombre_prise) or die ('Error in query procedural --> '.pg_last_error());
	  		$nb_total_affiche_nombre_prise = pg_num_rows($req_affiche_nombre_prise);
	 		echo '<BR />';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\'chiffre_recharge\'>';
			echo '&nbsp;'.$nb_total_affiche_nombre.'&nbsp;';
			echo '</span >';
			echo '&nbsp;';
			if ($nb_total_affiche_nombre > 1){
			echo '<span class=\'gras_titre\'>bornes de recharge &eacute;lectrique sur ce lieu</span>&nbsp;<span class=\'gras_titre\'>avec</span><span class=\'chiffre_point_recharge\'>&nbsp;'.$nb_total_affiche_nombre_prise.'&nbsp;</span> <span class=\'gras_titre\'>points de charge</span>';
			}else{
			echo '<span class=\'gras_titre\'>bornes de recharge &eacute;lectrique sur ce lieu</span>&nbsp;<span class=\'gras_titre\'>avec</span><span class=\'chiffre_point_recharge\'>&nbsp;'.$nb_total_affiche_nombre_prise.'&nbsp;</span> <span class=\'gras_titre\'>points de charge</span>';
			}
			echo '</div>';
			
			/*
			 * 
			 * Type de prise - Vitesse de recharge
			 * 
			 */			
			echo '<div id=\'gauche_large\'>';
			//echo '<div id=\'gauche\'>';
			    echo '<span class=\'texte_gras_noir\'>';
				echo '&nbsp;Type de prise&nbsp; - &nbsp;Vitesse de recharge&nbsp;';
			    echo '</span >';
			    echo '<br />';
			    //echo '<div id=\'gauche\'>';
			    //echo '</div>';
			    //echo '<div id=\'gauche\'>';
			    //echo '</div>';
			    //require_once('type_prise_tony.php');
			     require_once('type_prise_vitesse.php');
			echo '</div>';
			
			/*
			 * 
			 * Fin Type de prise - Vitesse de recharge
			 * 
			 */				
			
			
			
////////////////////////////////////////////////////////////////////////////////////////////////////////			
/*			echo '<div id=\'gauche\'>';
			echo '<span class=\'texte_gras_noir\'>';
			echo '&nbsp;Vitesse de recharge&nbsp;';			
			echo '</span >';
			
echo '<BR /><BR />';

$sql_vitesse_type_0 = "SELECT * FROM bd_borne_elec.v_lieu_recharge where v_lieu_recharge.tlrecharge_code = '".$_REQUEST['code']."' AND ttrecharge_code = '0'";
$req_vitesse_type_0 = pg_query($sql_vitesse_type_0) or die ('Error in query procedural --> '.pg_last_error());
$nb_total_vitesse_type_0 = pg_num_rows($req_vitesse_type_0);

$sql_vitesse_type_1 = "SELECT * FROM bd_borne_elec.v_lieu_recharge where v_lieu_recharge.tlrecharge_code = '".$_REQUEST['code']."' AND ttrecharge_code = '1'";
$req_vitesse_type_1 = pg_query($sql_vitesse_type_1) or die ('Error in query procedural --> '.pg_last_error());
$nb_total_vitesse_type_1 = pg_num_rows($req_vitesse_type_1);

$sql_vitesse_type_2 = "SELECT * FROM bd_borne_elec.v_lieu_recharge where v_lieu_recharge.tlrecharge_code = '".$_REQUEST['code']."' AND ttrecharge_code = '2'";
$req_vitesse_type_2 = pg_query($sql_vitesse_type_2) or die ('Error in query procedural --> '.pg_last_error());
$nb_total_vitesse_type_2 = pg_num_rows($req_vitesse_type_2);

$sql_vitesse_type_3 = "SELECT * FROM bd_borne_elec.v_lieu_recharge where v_lieu_recharge.tlrecharge_code = '".$_REQUEST['code']."' AND ttrecharge_code = '3'";
$req_vitesse_type_3 = pg_query($sql_vitesse_type_3) or die ('Error in query procedural --> '.pg_last_error());
$nb_total_vitesse_type_3 = pg_num_rows($req_vitesse_type_3);

echo "<table width=\'100%\' cellspacing=\'2\' cellpadding=\'2\'>";
* */
/*******************************************************************************************************/
/*if ($nb_total_vitesse_type_0 != '0'){
echo "<tr>";
echo '<td height=\'25px\' align=\'right\' width=\'200px\'>';
echo '<span class=\'gras_titre_vitesse\'>';
echo 'Non sp&eacute;cifi&eacute;';
echo '</span>';
echo '</td>';
echo '<td>';
echo '<span class=\'chiffre_vitesse_yes\'>';
echo '&nbsp;'.$nb_total_vitesse_type_0.'&nbsp;';
echo '</span>';
echo '</td>';
echo "</tr>";
 }*//*else{
echo "<tr>";
echo '<td height=\'25px\' align=\'right\' width=\'200px\'>';
echo '<span class=\'texte_chiffre_vitesse_no\'>';
echo 'Non sp&eacute;cifi&eacute;';
echo '</span>';
echo '</td>';
echo '<td>';
echo '<span class=\'chiffre_vitesse_no\'>';
echo '&nbsp;'.$nb_total_vitesse_type_0.'&nbsp;';
echo '</span>';
echo '</td>';
echo "</tr>";
}*/
/*******************************************************************************************************/
/*if ($nb_total_vitesse_type_1 != '0'){
echo "<tr>";
echo '<td height=\'25px\' align=\'right\' width=\'200px\'>';
echo '<span class=\'gras_titre_vitesse\'>';
echo 'Normale (3 KVA)';
echo '</span>';
echo '</td>';
echo '<td>';
echo '<span class=\'chiffre_vitesse_yes\'>';
echo '&nbsp;'.$nb_total_vitesse_type_1.'&nbsp;';
echo '</span>';
echo '</td>';
echo "</tr>";
 }*//*else{
echo "<tr>";
echo '<td height=\'25px\' align=\'right\' width=\'200px\'>';
echo '<span class=\'texte_chiffre_vitesse_no\'>';
echo 'Normale (3 KVA)';
echo '</span>';
echo '</td>';
echo '<td>';
echo '<span class=\'chiffre_vitesse_no\'>';
echo '&nbsp;'.$nb_total_vitesse_type_1.'&nbsp;';
echo '</span>';
echo '</td>';
echo "</tr>";
}*/
/*******************************************************************************************************/
/*if ($nb_total_vitesse_type_2 != '0'){
echo "<tr>";
echo '<td height=\'25px\' align=\'right\' width=\'200px\'>';
echo '<span class=\'gras_titre_vitesse\'>';
echo 'Acc&eacute;l&eacute;r&eacute;e (22 KVA)';
echo '</span>';
echo '</td>';
echo '<td>';
echo '<span class=\'chiffre_vitesse_yes\'>';
echo '&nbsp;'.$nb_total_vitesse_type_2.'&nbsp;';
echo '</span>';
echo '</td>';
echo "</tr>";
 }*//*else{
echo "<tr>";
echo '<td height=\'25px\' align=\'right\' width=\'200px\'>';
echo '<span class=\'texte_chiffre_vitesse_no\'>';
echo 'Acc&eacute;l&eacute;r&eacute;e (22 KVA)';
echo '</span>';
echo '</td>';
echo '<td>';
echo '<span class=\'chiffre_vitesse_no\'>';
echo '&nbsp;'.$nb_total_vitesse_type_2.'&nbsp;';
echo '</span>';
echo '</td>';
echo "</tr>";
}*/
/*******************************************************************************************************/
/*if ($nb_total_vitesse_type_3 != '0'){
echo "<tr>";
echo '<td height=\'25px\' align=\'right\' width=\'200px\'>';
echo '<span class=\'gras_titre_vitesse\'>';
echo 'Rapide (43 KVA)';
echo '</span>';
echo '</td>';
echo '<td>';
echo '<span class=\'chiffre_vitesse_yes\'>';
echo '&nbsp;'.$nb_total_vitesse_type_3.'&nbsp;';
echo '</span>';
echo '</td>';
echo "</tr>";
 }*//*else{
echo "<tr>";
echo '<td height=\'25px\' align=\'right\' width=\'200px\'>';
echo '<span class=\'texte_chiffre_vitesse_no\'>';
echo 'Rapide (43 KVA)';
echo '</span>';
echo '</td>';
echo '<td>';
echo '<span class=\'chiffre_vitesse_no\'>';
echo '&nbsp;'.$nb_total_vitesse_type_3.'&nbsp;';
echo '</span>';
echo '</td>';
echo "</tr>";
}*/



//echo "</table>";
			
/*	  $sql_vitesse_recharge = "SELECT * FROM bd_borne_elec.t_type_recharge ORDER BY ttrecharge_ordre_affich ASC";
	  $req_vitesse_recharge = pg_query($sql_vitesse_recharge) or die ('Error in query procedural --> '.pg_last_error());

			echo '<BR /><BR />';			
			
$nb_cell = $nb_total_vitesse_recharge; 

$nb_colonne = 1;

echo "<table width=\'100%\' cellspacing=\'2\' cellpadding=\'2\'>";

$z = 0;

while($row_vitesse_recharge = pg_fetch_assoc($req_vitesse_recharge)) 
{

	
		if ($z % $nb_colonne == 0) 
		{
		echo "\n<tr>\n";
		}

		if($z % $nb_colonne !=0 OR $z % $nb_colonne == 0) 
		{
		echo '<td height=\'25px\' align=\'right\' width=\'155px\'>';
		echo ''.ucfirst($row_vitesse_recharge['ttrecharge_libelle_long']).'';
		echo '</td>';
		echo '<td>';
		echo '<span class=\'chiffre_gras_vert_gris\'>';
		
	  
		echo '&nbsp;'.$row_vitesse_recharge['ttrecharge_code'].'&nbsp;';


		echo '</span >';
		echo '</td>';
		}
		$z++; 

		if ($z % $nb_colonne == 0 OR $z == $nb_cell) 
		{
		echo "\n</tr>\n";
		}
}
echo "</table>";

*/
			//echo '</div>';
			
			
			
			
			
			
			
			
			
			
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			//if ($var_commentaire_recharge != ''){
			if ($var_modalite != ''){
			echo '<div id=\'gauche\'>';
			}else{
			echo '<div id=\'gauche_not_commentaire\'>';
			}
			
			echo '<span class=\'texte_gras_noir\'>';
			echo '&nbsp;Condition d&acute;acc&egrave;s&nbsp;';
			echo '</span >';
			echo '<BR /><BR />';
			
	  $sql_code_borne = "SELECT DISTINCT tlrecharge_code, tborne_code FROM bd_borne_elec.v_propose where v_propose.tlrecharge_code = '".$_REQUEST['code']."'";
	  $req_code_borne = pg_query($sql_code_borne) or die ('Error in query procedural --> '.pg_last_error());
	  $nb_total_code_borne = pg_num_rows($req_code_borne);
	  
	  while($row_code_borne = pg_fetch_assoc($req_code_borne)) 
		{
		$var_code_bornes = $row_code_borne['tborne_code'];
		$nbr = 8;
		$var_code_bornes2 = substr($var_code_bornes, 0, -$nbr);
		}
		
/********************************************************************************************************************************/		
			$sql_acces_borne = "SELECT DISTINCT tborne_code, tacces_code, tacces_libelle FROM bd_borne_elec.v_accede where v_accede.tborne_code LIKE '".$var_code_bornes2."%' and tacces_code = '1'";
	  		$req_acces_borne = pg_query($sql_acces_borne) or die ('Error in query procedural --> '.pg_last_error());
	  		$nb_total_acces_borne = pg_num_rows($req_acces_borne);
			// echo $sql_acces_borne.'<BR />';
			
			$sql_acces_borne2 = "SELECT DISTINCT tborne_code, tacces_code, tacces_libelle, tacces_description, tbenef_libelle FROM bd_borne_elec.v_accede where v_accede.tborne_code LIKE '".$var_code_bornes2."%' and tacces_code = '1' LIMIT 1";
	  		$req_acces_borne2 = pg_query($sql_acces_borne2) or die ('Error in query procedural --> '.pg_last_error());
/********************************************************************************************************************************/
			$sql_acces_borne_0 = "SELECT DISTINCT tborne_code, tacces_code, tacces_libelle FROM bd_borne_elec.v_accede where v_accede.tborne_code LIKE '".$var_code_bornes2."%' and tacces_code = '0'";
	  		$req_acces_borne_0 = pg_query($sql_acces_borne_0) or die ('Error in query procedural --> '.pg_last_error());
	  		$nb_total_acces_borne_0 = pg_num_rows($req_acces_borne_0);
			// echo $sql_acces_borne_0.'<BR />';
			
			$sql_acces_borne_0_2 = "SELECT DISTINCT tborne_code, tacces_code, tacces_libelle, tacces_description, tbenef_libelle FROM bd_borne_elec.v_accede where v_accede.tborne_code LIKE '".$var_code_bornes2."%' and tacces_code = '0' LIMIT 1";
	  		$req_acces_borne_0_2 = pg_query($sql_acces_borne_0_2) or die ('Error in query procedural --> '.pg_last_error());
/********************************************************************************************************************************/
	  		
			if ($nb_total_acces_borne != ''){
			$nb_total_acces_borne2 = pg_num_rows($req_acces_borne2);
			echo '<span class=\'gras_titre3\'>'.$nb_total_acces_borne.'</span> bornes sont en ';
			while($row_acces_borne2 = pg_fetch_assoc($req_acces_borne2)) 
					{
						echo ''.$row_acces_borne2['tacces_libelle'].' ('.$row_acces_borne2['tacces_description'].'';
						
						if (($row_acces_borne2['tacces_description'] != '') && ($row_acces_borne2['tbenef_libelle'] != '')){
						echo ' - ';
						}
						
						echo ''.$row_acces_borne2['tbenef_libelle'].')';
						echo '<BR />';
					}
			}
			if ($nb_total_acces_borne_0 != ''){
			$nb_total_acces_borne_0 = pg_num_rows($req_acces_borne_0);
			echo '<span class=\'gras_titre3\'>'.$nb_total_acces_borne_0.'</span> bornes sont en ';
			while($row_acces_borne_0_2 = pg_fetch_assoc($req_acces_borne_0_2)) 
					{
						echo ''.$row_acces_borne_0_2['tacces_libelle'].' ('.$row_acces_borne_0_2['tacces_description'].'';
						
						if (($row_acces_borne_0_2['tacces_description'] != '') && ($row_acces_borne_0_2['tbenef_libelle'] != '')){
						echo ' - ';
						}
						
						echo ''.$row_acces_borne_0_2['tbenef_libelle'].')';
						
						echo '<BR />';
					}
			}
		
			echo '<BR />';
			if ($var_paiement != 'ns'){
			echo '<span class=\'texte_definition\'>';
			echo 'Acquittement : ';
			echo '</span >';
			echo '<span class=\'gras_titre\'>';
			echo ''.ucfirst($var_paiement).'.';
			echo '</span >';
			}else{
			echo '<span class=\'texte_definition\'>';
			echo 'Acquittement : ';
			echo '</span >';
			echo '<span class=\'gras_titre\'>';
			echo 'Non sp&eacute;cifi&eacute;.';
			echo '</span >';
			}
			echo '<BR />';
			
/*			if ($var_modalite != ''){
			echo '<div id=\'description\'>';
			// echo '<span class=\'texte_definition\'>';
			// echo 'Description : ';
			// echo '</span >';
			echo ''.$var_modalite.'';
			echo '</div>';
			}*/
			echo '</div>';
/*			echo '<div id=\'gauche\'>';
			echo '<span class=\'texte_gras_noir\'>';
			echo '&nbsp;Commentaire&nbsp;';
			echo '</span >'*/;
			if ($var_modalite != ''){
				/*tony*/
			echo '<div id=\'gauche\'>';
			echo '<span class=\'texte_gras_noir\'>';
			echo '&nbsp;Commentaire&nbsp;';
			echo '</span >';
				/*tony*/
			// echo '<div id=\'description\'>';
			// echo '<span class=\'texte_definition\'>';
			// echo 'Description : ';
			// echo '</span >';
			echo '<BR /><BR />';
			echo ''.$var_modalite.'';
			// echo '</div>';
				/*tony*/
			echo '</div>';
				/*tony*/
			}
			/*echo '</div>';*/
			
			
			
			/*if ($var_commentaire_recharge != ''){
			echo '<div id=\'gauche_commentaire\'>';
			echo '<span class=\'titre_commentaire\'>';
			echo '&nbsp;Commentaire&nbsp;';
			echo '</span >';
			echo '<BR />';
			echo '<span class=\'texte_definition\'>';
			echo ''.nl2br($var_commentaire_recharge).'';
			
			echo '</span >';
			echo '</div>';
			}*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		 
		echo '</div>';
		
}

}else if ($_REQUEST['code'] == ''){
	echo '<div align=\'center\'>';
	echo '<BR /><BR /><BR /><BR />';
	echo '<BR /><BR /><BR /><BR />';
	echo '<BR /><BR /><BR /><BR />';
	echo '<BR /><BR /><BR /><BR />';
	echo '<span class=\'gras_titre2\'>';
	echo 'D&eacute;sol&eacute;, aucune information<BR />n&acute;est disponible sur votre requ&ecirc;te';
	echo '</span >';
	echo '<BR /><BR />';
	echo '<img class=\'displayed\' src=\'images/photos/photo_par_default.gif\' alt=\'Imagette d&acute;une voiture &eacute;lectrique\' title=\'Imagette d&acute;une voiture &eacute;lectrique\' border=\'0\' />';
	echo '</div>';
}
/******************************************* FIN MESSAGE SI LA BASE DE DONNEES A DES PROBLEME **************************************************************/
// }
/******************************************* FIN MESSAGE SI LA BASE DE DONNEES A DES PROBLEME **************************************************************/
		echo '<div id=\'logo_region\'>';
		echo '<img src=\'images/logo_region.gif\' alt=\'Logo de la R&eacute;gion Poitou-Charentes\' title=\'Logo de la R&eacute;gion Poitou-Charentes\' border=\'0\' />';
		echo '</div>';

echo '</div>';
echo '</body>';
echo '</html>';

?>

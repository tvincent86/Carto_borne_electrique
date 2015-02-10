<?php
// On définit les variables
$var_nom_type_domestique_UE = 'Domestique E/F';
$var_nom_type_1 = 'Type 1';
$var_nom_type_2 = 'Type 2';
$var_nom_type_3 = 'Type 3';
$var_nom_type_4 = 'Type 4';
$var_nom_ChAdeMO = 'ChAdeMO';
$var_nom_autres = 'Autres';


$var_groupe_prise = Array();
$var_groupe_prise[0]["libelle"] = "Domestique E/F";
$var_groupe_prise[0]["socle"][0] = 4;
$var_groupe_prise[0]["socle"][1] = 15;

$var_groupe_prise[1]["libelle"] = "Type 1";
$var_groupe_prise[1]["socle"][0] = 7;

$var_groupe_prise[2]["libelle"] = "Type 2";
$var_groupe_prise[2]["socle"][0] = 8;

$var_groupe_prise[3]["libelle"] = "Type 3";
$var_groupe_prise[3]["socle"][0] = 10;
$var_groupe_prise[3]["socle"][1] = 12;

$var_groupe_prise[4]["libelle"] = "Type 4";
$var_groupe_prise[4]["socle"][0] = 14;

$var_groupe_prise[5]["libelle"] = "ChAdeMO";
$var_groupe_prise[5]["socle"][0] = 2;

$var_groupe_prise[6]["libelle"] = "Autres";
$var_groupe_prise[6]["socle"][0] = 13;

$color = true;

// On test si la variable est bien un tableau
if (is_array($var_groupe_prise)) {
    // On boucle en fonction des types de prise
    foreach ($var_groupe_prise as $valuePrise) {
	$titre_groupe = $valuePrise["libelle"];
    
	// On test si la variable est bien un tableau
	if (is_array($var_groupe_prise)) {
	    // On construit le SQL pour les socles
	    foreach ($valuePrise as $socle) {
		$prises = "('";
		foreach ($socle as $v2) {
		    $prises .= $v2."','";
		}
		$grp_socles = substr($prises,0,strlen($prises)-3); 
		$grp_socles .= "')";
	    }
	    //echo $grp_socles."<br />";
    
	    // SQL
	    $sql_type_recharge = "SELECT DISTINCT ttrecharge_code FROM bd_borne_elec.v_lieu_recharge where v_lieu_recharge.tlrecharge_code = '".$_REQUEST['code']."' AND v_lieu_recharge.tsocle_code IN ".$grp_socles." ";
	    //echo $sql_type_recharge."<br />";
	    $req_type_recharge = pg_query($sql_type_recharge) or die ('Error in query procedural --> '.pg_last_error());
	    $nb_total_type_recharge = pg_num_rows($req_type_recharge);
	    
	    // On affiche les informations que s'il existe un type de socle
	    if ($nb_total_type_recharge != 0) {
    
		$var_titre_groupe = ucfirst($titre_groupe);
		//echo ucfirst($titre_groupe); 
	
		$nb_prise = 0;
		$data = Array();
	
		while($row_type_recharge = pg_fetch_array($req_type_recharge))
		{
		    $sql_type_recharge_vitesse = "SELECT tlrecharge_code, tsocle_code, ttrecharge_code, ttrecharge_libelle, ttrecharge_libelle_long, tsocle_libelle_long, tsocle_libelle FROM bd_borne_elec.v_lieu_recharge where v_lieu_recharge.ttrecharge_code = '".$row_type_recharge['ttrecharge_code']."' AND v_lieu_recharge.tlrecharge_code = '".$_REQUEST['code']."' AND v_lieu_recharge.tsocle_code IN ".$grp_socles." ";
		    //echo $sql_type_recharge_vitesse."<hr>";
		    $req_type_recharge_vitesse = pg_query($sql_type_recharge_vitesse) or die ('Error in query procedural --> '.pg_last_error());
		    $nb_total_type_recharge_vitesse = pg_num_rows($req_type_recharge_vitesse);
		    //echo $nb_total_type_recharge_vitesse."<br />";
		    
		    while($row_type_recharge_vitesse = pg_fetch_array($req_type_recharge_vitesse))
		    {
			// On récupére le nom de la vitesse de charge
			$var_ttrecharge_libelle_long = $row_type_recharge_vitesse['ttrecharge_libelle_long'];
				    
		    }
		    // On récupére le nombre de prise en fonction de leur vitesse
		    $data[$row_type_recharge['ttrecharge_code']]["nb"] =  $nb_total_type_recharge_vitesse;
		    $data[$row_type_recharge['ttrecharge_code']]["lib_long"] =  $var_ttrecharge_libelle_long;
		    
		    // On récupére le nombre de prise en fonction du type
		    $nb_prise += $nb_total_type_recharge_vitesse;
		    
		    //print_r($data);
		}
		// Permet d'appliquer un style pour la div
		if ($color) {
		    $color = false;
		    $class_color = 'grey';
		}else{
		    $color = true;
		    $class_color = '';
		}	
		?>
		<div id="global1" class="<?php echo $class_color; ?>">
		    <div id="gauche1" >
			<b><?php echo ucfirst($titre_groupe); ?></b>
			<span class="chiffre_vitesse_yes">
			    <?php  echo $nb_prise; ?>
			</span>
		    </div>
		    <div id="droit1" >
		    <?php
			foreach ($data as $value) {
			?>
			    &#9632; <span class="chiffre_vitesse_no"><?php echo $value["nb"] ?></span><?php echo $value["lib_long"]; ?> 
			    <br>
			<?php
			}
			?>
		    </div>
		</div>	
<?php
	    }
	} // END is_array
    } // END foreach	
} // END is_array
?>




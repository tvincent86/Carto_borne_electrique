<?php

// $var_type_socle_domestique_UE = NULL;


/*

$var_type_socle_0 = NULL;$var_type_socle_1 = NULL;$var_type_socle_2 = NULL;$var_type_socle_3 = NULL;$var_type_socle_4 = NULL;$var_type_socle_5 = NULL;
$var_type_socle_6 = NULL;$var_type_socle_7 = NULL;$var_type_socle_8 = NULL;$var_type_socle_9 = NULL;$var_type_socle_10 = NULL;$var_type_socle_11 = NULL;
$var_type_socle_12 = NULL;$var_type_socle_13 = NULL;$var_type_socle_14 = NULL;$var_type_socle_15 = NULL;
	
$sql_socle_0 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '0'";$req_socle_0 = pg_query($sql_socle_0) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_0 = pg_fetch_array($req_socle_0)){$var_type_socle_0 = $row_socle_0['tsocle_libelle_long'];$var_type_socle_nom_0 = $row_socle_0['tsocle_libelle'];}

$sql_socle_1 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '1'";$req_socle_1 = pg_query($sql_socle_1) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_1 = pg_fetch_array($req_socle_1)){$var_type_socle_1 = $row_socle_1['tsocle_libelle_long'];$var_type_socle_nom_1 = $row_socle_1['tsocle_libelle'];}

$sql_socle_2 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '2'";$req_socle_2 = pg_query($sql_socle_2) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_2 = pg_fetch_array($req_socle_2)){$var_type_socle_2 = $row_socle_2['tsocle_libelle_long'];$var_type_socle_nom_2 = $row_socle_2['tsocle_libelle'];}

$sql_socle_3 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '3'";$req_socle_3 = pg_query($sql_socle_3) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_3 = pg_fetch_array($req_socle_3)){$var_type_socle_3 = $row_socle_3['tsocle_libelle_long'];$var_type_socle_nom_3 = $row_socle_3['tsocle_libelle'];}

$sql_socle_4 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '4'";$req_socle_4 = pg_query($sql_socle_4) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_4 = pg_fetch_array($req_socle_4)){$var_type_socle_4 = $row_socle_4['tsocle_libelle_long'];$var_type_socle_nom_4 = $row_socle_4['tsocle_libelle'];}

$sql_socle_5 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '5'";$req_socle_5 = pg_query($sql_socle_5) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_5 = pg_fetch_array($req_socle_5)){$var_type_socle_5 = $row_socle_5['tsocle_libelle_long'];$var_type_socle_nom_5 = $row_socle_5['tsocle_libelle'];}

$sql_socle_6 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '6'";$req_socle_6 = pg_query($sql_socle_6) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_6 = pg_fetch_array($req_socle_6)){$var_type_socle_6 = $row_socle_6['tsocle_libelle_long'];$var_type_socle_nom_6 = $row_socle_6['tsocle_libelle'];}

$sql_socle_7 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '7'";$req_socle_7 = pg_query($sql_socle_7) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_7 = pg_fetch_array($req_socle_7)){$var_type_socle_7 = $row_socle_7['tsocle_libelle_long'];$var_type_socle_nom_7 = $row_socle_7['tsocle_libelle'];}

$sql_socle_8 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '8'";$req_socle_8 = pg_query($sql_socle_8) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_8 = pg_fetch_array($req_socle_8)){$var_type_socle_8 = $row_socle_8['tsocle_libelle_long'];$var_type_socle_nom_8 = $row_socle_8['tsocle_libelle'];}

$sql_socle_9 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '9'";$req_socle_9 = pg_query($sql_socle_9) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_9 = pg_fetch_array($req_socle_9)){$var_type_socle_9 = $row_socle_9['tsocle_libelle_long'];$var_type_socle_nom_9 = $row_socle_9['tsocle_libelle'];}

$sql_socle_10 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '10'";$req_socle_10 = pg_query($sql_socle_10) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_10 = pg_fetch_array($req_socle_10)){$var_type_socle_10 = $row_socle_10['tsocle_libelle_long'];$var_type_socle_nom_10 = $row_socle_10['tsocle_libelle'];}

$sql_socle_11 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '11'";$req_socle_11 = pg_query($sql_socle_11) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_11 = pg_fetch_array($req_socle_11)){$var_type_socle_11 = $row_socle_11['tsocle_libelle_long'];$var_type_socle_nom_11 = $row_socle_11['tsocle_libelle'];}

$sql_socle_12 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '12'";$req_socle_12 = pg_query($sql_socle_12) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_12 = pg_fetch_array($req_socle_12)){$var_type_socle_12 = $row_socle_12['tsocle_libelle_long'];$var_type_socle_nom_12 = $row_socle_12['tsocle_libelle'];}

$sql_socle_13 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '13'";$req_socle_13 = pg_query($sql_socle_13) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_13 = pg_fetch_array($req_socle_13)){$var_type_socle_13 = $row_socle_13['tsocle_libelle_long'];$var_type_socle_nom_13 = $row_socle_13['tsocle_libelle'];}

$sql_socle_14 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '14'";$req_socle_14 = pg_query($sql_socle_14) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_14 = pg_fetch_array($req_socle_14)){$var_type_socle_14 = $row_socle_14['tsocle_libelle_long'];$var_type_socle_nom_14 = $row_socle_14['tsocle_libelle'];}

$sql_socle_15 = "SELECT * FROM bd_borne_elec.t_socle where tsocle_code = '15'";$req_socle_15 = pg_query($sql_socle_15) or die ('Error in query procedural --> '.pg_last_error());
while($row_socle_15 = pg_fetch_array($req_socle_15)){$var_type_socle_15 = $row_socle_15['tsocle_libelle_long'];$var_type_socle_nom_15 = $row_socle_15['tsocle_libelle'];}
*/	

?>
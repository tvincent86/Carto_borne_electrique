<?php
/*
if (!defined('RACINE_ROOT')){
    die("D&eacute;sol&eacute;, vous ne pouvez pas acc&eacute;der directement à ce fichier");
}
 */
//Paramètres de connexion à la BDD PgSQL
$chaine_conn = "host=10.0.0.207 dbname=bdiaat user=postgres password=";
$bd_conn = pg_connect($chaine_conn) or die("Connexion impossible");
$bd_schema = "bd_borne_elec";
$bd_schema_commun = "bd_commun_projet";
$bd_table_01 = "v_lieu_recharge_carto";
$bd_table_02 = "v_propose1";
$bd_table_lst_commune = "t_com_code_postal_pc";

//Fermeture de la connexion PgSQL.
//pg_close($dbh);
//pg_close($conn);


<?php
///t On défini la racine du site
define('RACINE_ROOT', '..');
if (!defined('RACINE_ROOT')){
    die("D&eacute;sol&eacute;, vous ne pouvez pas acc&eacute;der directement à ce fichier");
}
//Header de type texte
header('Content-type: text/html; charset=UTF-8');

//Paramètres de connexion à la BDD PgSQL
//require(RACINE_ROOT.'/include/conn_pg_bdiaat2.php');
require(RACINE_ROOT.'/include/conn_pg_bdiaat.php');

/*$bd_schema = "ref_ign";
$bd_table_01 = "v_commune_pc";*/
$bd_schema = "bd_borne_elec";
$bd_table_01 = "t_com_code_postal_pc";

///////////////////////////////////////////
//// Récupération du contenu des paramètres
$callback = (isset($_REQUEST['callback'])) ? addslashes($_REQUEST['callback']) : "";
$nomcom_startsWith = (isset($_REQUEST['nomcom_startsWith'])) ? mb_strtoupper($_REQUEST['nomcom_startsWith'],'UTF-8') : "";

//// On test si la demande vient bien de l'interface carto
if ($_REQUEST['callback'] != "") {
    ////
    $sql = "SELECT code_postal, numcom, nomcom, numdep, ST_AsGeoJSON(ST_Centroid(the_geom_4326)) FROM ".$bd_schema_commun.".".$bd_table_lst_commune." WHERE the_geom_4326 IS NOT NULL AND upper(nomcom) LIKE '".$nomcom_startsWith."%' ";
    ////
    //echo $sql;

    $res = pg_query($sql) or die(pg_last_error());
    $num_rows = pg_num_rows($res);
    //echo "num_rows : ".$num_rows."--";

    //Génération de la chaîne GeoJSON
    $str = '';
    $str .= $callback.'({
        "totalResultsCount": '.$num_rows.',
            "features": 
            [';
        $i = 1;
        
        if ($num_rows > 0) {
            while ($result = pg_fetch_array($res)) 
            {
                $coords = substr($result['st_asgeojson'], 30, strlen($result['st_asgeojson'])-2);
                $pdelim = strpos($coords, ',');
                $long = substr($coords, 1, $pdelim - 1);
                $lat = substr($coords, $pdelim + 1, (strlen($coords) - $pdelim) - 3);
                $nomcom = str_replace("'","\'",$result['nomcom']);

                //// Formatage du fichier GeoJSON
                $str .= '{
                    "id": '.$result['numcom'].',
                        "nomcom": "'.$nomcom.' ('.$result['code_postal'].')",
                        "code_postal": "'.$result['code_postal'].'",
                        "numdep": "'.$result['numdep'].'",
                        "lng": '.$long.', 
                        "lat": '.$lat.'
            },';
            }
            $str2 = substr($str,0,strlen($str)-1);    
            $str2 .= ']})';
        }else{
            $str2 = $str . ']})';
        }
        //La chaîne GeoJSON est prête, on l'écrit sortie standard, en réponse à l'appel du script PHP.
        echo $str2;
        //Fermeture de la connexion PgSQL.
        pg_close($bd_conn);
}
?>

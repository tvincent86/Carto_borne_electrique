<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//include("file_with_errors.php"); 

// Désactiver le rapport d'erreurs
//error_reporting(0);
// Rapporte les erreurs d'exécution de script
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
// Rapporter les E_NOTICE peut vous aider à améliorer vos scripts
// (variables non initialisées, variables mal orthographiées..)
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
// Rapporte toutes les erreurs à part les E_NOTICE
// C'est la configuration par défaut de php.ini
//error_reporting(E_ALL ^ E_NOTICE);
// Reporte toutes les erreurs PHP (Voir l'historique des modifications)
//error_reporting(E_ALL);
// Reporte toutes les erreurs PHP
//error_reporting(-1);
// Même chose que error_reporting(E_ALL);
//ini_set('error_reporting', E_ALL);

// On défini la racine du site
//define('RACINE_ROOT', '..');
//if (!defined('RACINE_ROOT')){
    //die("D&eacute;sol&eacute;, vous ne pouvez pas acc&eacute;der directement à ce fichier");
//}

// On défini la racine du site
define('RACINE_ROOT', '..');
if (!defined('RACINE_ROOT')){
    die("D&eacute;sol&eacute;, vous ne pouvez pas acc&eacute;der directement à ce fichier");
}

//Header de type texte
header('Content-type: text/html; charset=UTF-8');

// Paramètres de connexion à la BDD PgSQL
require(RACINE_ROOT.'/include/conn_pg_bdiaat2.php');
//require(RACINE_ROOT.'/include/conn_pg_bdiaat.php');

// Récupération du contenu des paramètres
$params1 = isset($_REQUEST['tsocle_code']) ? " tsocle_code IN ('".str_replace('_','\',\'',pg_escape_string($_REQUEST['tsocle_code']))."') AND ": "" ;
$params2 = isset($_REQUEST['ttrecharge_code']) ? " ttrecharge_code IN ('".str_replace('_','\',\'',pg_escape_string($_REQUEST['ttrecharge_code']))."') AND ": "" ;
$params3 = isset($_REQUEST['tacces_code']) ? " tacces_code IN ('".str_replace('_','\',\'',pg_escape_string($_REQUEST['tacces_code']))."') AND ": "" ;
$params4 = isset($_REQUEST['tborne_paiement']) ? " tborne_paiement IN ('".str_replace('_','\',\'',pg_escape_string($_REQUEST['tborne_paiement']))."') AND ": "" ;
//echo $params1." ------ ".$params2;
//

//$params1 = "tsocle_code IN ('T3') AND ";

// Requête SQL pour récupérer les centres des départements et les données de population, en joignant les deux tables
//$sql = "SELECT tsite_id, tsite_libelle, nomcom, tsite_x, tsite_y, tsite_access_libre, tsite_accompagnement_code, tsite_atelier_code, ST_AsGeoJSON(the_geom_4326) FROM ".$bd_schema.".".$bd_table_01." WHERE the_geom_4326 IS NOT NULL ";
//$sql = "SELECT * FROM ".$bd_schema.".".$bd_table_01." WHERE ".$params1." tlrecharge_x_wgs84 IS NOT NULL;";
$sql = "SELECT DISTINCT tlrecharge_code, tlrecharge_code_insee, tlrecharge_libelle, tlrecharge_code_postal, tlrecharge_ville, tlrecharge_x_wgs84, tlrecharge_y_wgs84, tetat_code FROM ".$bd_schema.".".$bd_table_01." WHERE ".$params1." ".$params2." ".$params3." ".$params4." tlrecharge_x_wgs84 IS NOT NULL ORDER BY tlrecharge_code;";
//AND tbenef_code != '4'

//ttrecharge_code, tsocle_code, , tacces_code, tbenef_code, tborne_paiement

//".$params3." 
//echo $sql;

$res = pg_query($sql) or die(pg_last_error());
//echo pg_num_rows($res);

// Génération de la chaîne GeoJSON
$str = '';
$str .= '{
"type": "FeatureCollection",
"crs": {"type": "name", "properties": {"name": "urn:ogc:def:crs:OGC:1.3:CRS84"} },
"features": 
[';
//$i = 1; 
while ($result = pg_fetch_array($res)) 
{       
    //$coords = substr($result['st_asgeojson'], 30, strlen($result['st_asgeojson'])-2);
    //$pdelim = strpos($coords, ',');
    //$long = substr($coords, 1, $pdelim - 1);
    //$lat = substr($coords, $pdelim + 1, (strlen($coords) - $pdelim) - 3);
    $long = $result['tlrecharge_x_wgs84'];
    $lat = $result['tlrecharge_y_wgs84'];
    $titre_fiche = str_replace('"','\"',$result['tlrecharge_libelle']).' ('.$result['tlrecharge_code_postal'].' '.str_replace('"','\"',$result['tlrecharge_ville']).')';
     
    //$acces_libre = ($result['tsite_access_libre'] == null) ? 1 : $result['tsite_access_libre'] ;
    //$ttype_prise = $result['ttype_prise_id'];
    // Formatage du fichier GeoJSON
    $str .= '{
    "geometry": {"type":"Point","coordinates":['.$long.','.$lat.']},
    "code": "'.$result['tlrecharge_code'].'",
    "type": "Feature",
    "bbox": ['.$long.','.$lat.','.$long.','.$lat.'],
    "properties": {
        "name": "'.$titre_fiche.'",
        "tlrecharge_code": "'.$result['tlrecharge_code'].'",
        "tlrecharge_libelle": "'.str_replace('"','\"',$result['tlrecharge_libelle']).'",
        "titre_fiche": "'.$titre_fiche.'",
        "tetat_code": "'.$result['tetat_code'].'"
	}
},';
}
if (pg_num_rows($res) == 0) {
    $str .= '{
        "geometry": {"type":"Point","coordinates":[0,0]},
        "code": "aaaaaaaaa",
        "type": "Feature",
        "bbox": [0,0,0,0],
        "properties": {
            "name": "aaaaaa",
            "tlrecharge_code": "aaaaaa",
            "tlrecharge_libelle": "aaaaaa",
            "titre_fiche": "aaaaaa",
            "tetat_code": "aaaaaa"
        }
    }';
}
//"ttrecharge_code": "'.$result['ttrecharge_code'].'",
//echo pg_num_rows($res);
$str2 = (pg_num_rows($res) == 0) ? $str : substr($str,0,strlen($str)-1); 
//$str2 = substr($str,0,strlen($str)-1);   
//$str2 .= (count($res) == 1) ? '['; 
$str2 .= ']}';
// La chaîne GeoJSON est prête, on l'écrit sortie standard, en réponse à l'appel du script PHP.
echo $str2;
// Fermeture de la connexion PgSQL.
pg_close($bd_conn);
?>

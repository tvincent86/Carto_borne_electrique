<?php
// On défini la racine du site 
define('RACINE_ROOT', '..');
if (!defined('RACINE_ROOT')){
    die("D&eacute;sol&eacute;, vous ne pouvez pas acc&eacute;der directement &agrave; ce fichier");
}
//Header de type texte

if ($_POST["frmDownload"] == "Excel") {
    header("Content-disposition: attachment; filename=employee_details.xls");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-type: application/x-msexcel");
}else{
    header('Content-type: text/html; charset=UTF-8');    
}


include(RACINE_ROOT.'/xls/biff/biff.php');

//$chco = "host=10.0.0.207 dbname=bdiaat2 user=postgres password=";
//$dbconn = pg_connect($chco) or die("Connexion impossible");

//Paramètres de connexion à la BDD PgSQL
//require(RACINE_ROOT.'/include/conn_pg_bdiaat2.php');
require(RACINE_ROOT.'/include/conn_pg_bdiaat.php');
 

//// Initialisation des variables
$temps = time();
$vardate = date( d , $temps).date( m , $temps).date( Y , $temps); 
//$texte_mentions_legales = "La reproduction des informations est autorisée, sous réserve du respect de la gratuité de la diffusion, de l'intégrité des documents reproduits ainsi que la citation claire et lisible de la source.";
//$texte_arrondi = "Les données sont arrondies, la somme des items par département peut donc être légèrement différente de 100.";

if (isset($_REQUEST['id'])){
    //// Récupération des variables
    $varid = $_REQUEST['id'];
    $varstatut = (isset($_REQUEST['statut']))? " AND tsta_id = ".$_REQUEST['statut']." ": "";

    $varid = explode("_",$varid);
    //print_r($varid);

    $groupe = array (
        1 => "('CFA','CFAI','UFA','CCI','CDD','SA','ANNEXE','SITE')",
        2 => "('MFR','MFREO','IREO')",
        3 => "('LP','LPA','EREA','LPP')",
        4 => "('LGT','LPO','LEGTA','LEAP','LT','LTP','LTPR')",
        5 => "('IUP','UFR','IUT','UNIVERSITE','IAE')"
    );
    //print_r($groupe);

    $option = "";
    $type_etab = "";

    if (sizeof($varid)>0) {
        ////
        $option = "WHERE tets_note IN (";
        ////
        for ($i = 0; $i < sizeof($varid); $i++) {
            $option.= "'".$varid[$i]."',"; 
        }
        $option = substr($option, 0, strlen($option)-1);
        $option.= ")";
    }
    //echo $option."<br /><br />";
    
    //$sql = "SELECT * FROM offre_formation_2010.v_etablissement ".$option;
    $sql = "SELECT DISTINCT tets_libelle, tets_code, ttets_libelle, tsets_libelle, tets_adresse, tets_code_postal, tets_ville, tets_tel, tets_mail, tets_web, numcom, nomcom  FROM offre_formation_2010.v_formation_diplome ".$option." ".$varstatut.";";

    // A décommenter pour test
    //echo $sql."<br>";

    $res = pg_query($sql) or die(pg_last_error());
    //
    //$rows = pg_num_rows($res);
    //echo $rows . " row(s) returned.\n";

///////////////////////////////////////////////////////////////////////
//// Génération / Affichage des tableaux

////
$myxls = new BiffWriter();

//// On définit les polices 
//$myxls->xlsSetFont('times new roman',8,FONT_NORMAL);
//$myxls->xlsSetFont('arial',10,FONT_BOLD);
$myxls->xlsSetFont('arial',10,FONT_NORMAL);
//$myxls->xlsSetFont('arial',10,FONT_ITALIC);
//$myxls->xlsSetFont('courrier',10,FONT_UNDERLINE + FONT_BOLD);

//// CREATION DU NOM DE FICHIER
$nom_fichier = "export".$vardate;
$clean_nom_fichier = str_replace(" ", "_", $nom_fichier);


//// On donne un titre à l'export 
$titre="Offre de formation professionnelle initiale en Poitou-Charentes";
//// On donne une date à l'export 
$date=date("d/m/Y");
//// Affiche le libelle 
$libelle = "Carte interactive des \"Etablissements\"";


//$myxls->xlsWriteText(1, 0, $titre." ".$vartheme, -1, 0, FONT_0); //
$myxls->xlsWriteText(1, 0, $titre, -1, 0, FONT_0); //
////
$myxls->xlsWriteText(2, 0, " le ".$date, -1, 0, FONT_0); //
////
$myxls->xlsWriteText(5, 0, $libelle, -1, 0, FONT_0); //
$myxls->xlsWriteText(6, 0, $type_etab, -1, 0, FONT_0); //

////
$ligne_debut_tableau = 11;
////
$colonne = 0;

$entete = array(
    0 => "Nom",
    1 => "Code",
    2 => "Type",
    3 => "Statut",
    4 => "Adresse",
    5 => "Code postal",
    6 => "Ville",
    7 => "Télephone",
    8 => "Mail",
    9 => "Site internet", 
    10 => "Code insee",
    11 => "Commune"
);
//// Créer l'entete des colonnes
for ($i=0;$i<sizeof($entete);$i++)
{
////
$myxls->xlsWriteText($ligne_debut_tableau,$colonne,utf8_decode($entete[$i]), 0, 0, FONT_2);
////
        $colonne =  $colonne + 1;
}

////
$ligne = $ligne_debut_tableau+1;


////
$colonne = 0;
while ($result = pg_fetch_array($res)) 
{
        ////
        $myxls->xlsWriteText($ligne,$colonne,utf8_decode($result['tets_libelle']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+1,utf8_decode($result['tets_code']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+2,utf8_decode($result['ttets_libelle']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+3,utf8_decode($result['tsets_libelle']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+4,utf8_decode($result['tets_adresse']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+5,utf8_decode($result['tets_code_postal']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+6,utf8_decode($result['tets_ville']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+7,utf8_decode($result['tets_tel']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+8,utf8_decode($result['tets_mail']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+9,utf8_decode($result['tets_web']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+10,utf8_decode($result['numcom']), 0, 0, FONT_2);
        $myxls->xlsWriteText($ligne,$colonne+11,utf8_decode($result['nomcom']), 0, 0, FONT_2);
        
////
        $ligne = $ligne+1;
}


//// Source de la données
$myxls->xlsWriteText($ligne+3, 0, "Source : ARFTLV, Rectorat, Région Poitou-Charente, juillet ".$var_annee, -1, 0, FONT_0); //


//// On génére le fichier Excel
        $myxls->xlsParse();
 
}else{
    echo "Vous n'avez fait aucun choix ==> donc aucune données à télécharger";
}
?>

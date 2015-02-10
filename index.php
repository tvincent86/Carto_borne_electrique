<?php
// On défini la racine du site
define('RACINE_ROOT', '.');
if (!defined('RACINE_ROOT')){
die("D&eacute;sol&eacute;, vous ne pouvez pas acc&eacute;der directement à ce fichier");
}
// Inclusion des variables communes à toutes les pages 
include(RACINE_ROOT.'/include/var_ini.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $var_titre; ?></title>
<meta name="author" content="<?php echo $var_author; ?>" />
<meta name="email" content="<?php echo $var_author_mail ?>" />

<!--
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
-->

<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>

<!--
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
-->
<!--
<script src="http://openlayers.org/api/OpenLayers.js"></script>
-->

    <link rel="Shortcut Icon" href="./img/liferay.ico" />
</head>
<body>

<div id="loading-mask" style=""></div>
<div id="loading">
    <div class="loading-indicator">
        <img src="./lib/extjs/examples/shared/extjs/images/extanim32.gif" alt="" width="32" height="32" style="margin-right:8px;float:left;vertical-align:top;"/>
        <?php echo $var_txt_chargement; ?>
    </div>
</div>

<script type="text/javascript" src="./lib/extjs/adapter/ext/ext-base.js"></script>                               
<script type="text/javascript" src="./lib/extjs/ext-all.js"></script>

<!--
<script src="http://www.openlayers.org/api/2.11/OpenLayers.js" type="text/javascript"></script>
-->
<script src="./lib/openlayers/OpenLayers.js"></script>
<script type="text/javascript" src="./js/AnimatedCluster_crpc.js"></script>


<script src="./lib/geoext/script/GeoExt.js" type="text/javascript"></script>


<script type="text/javascript" src="./js/olExtToolTipsCluster_crpc.js"></script>
<script type="text/javascript" src="./js/ShortcutCombo_crpc.js"></script>
<script type="text/javascript" src="./js/GeoNamesSearchCombo_crpc.js"></script>
<style type="text/css" media="all">
    @import './css/main.css';
<!--
    @import './lib/extjs/resources/css/ext-all.css';
-->
<!--
    @import './lib/extjs/examples/shared/examples.css';
-->
    
</style>
<!--
<script src="./js/help.js"></script>
-->
<script src="./js/map.js"></script>
<!-- tout le reste //// Utile pour masquer le contenu des div au chargement de la page //// -->
<div style="display:none;">
<?php include(RACINE_ROOT.'/include/footer.php') ?>

</div>
<div id="map" style="width:800px;height:400px;"></div>
<script src="./js/app.js"></script>
<style type="text/css" media="all">
    @import './css/iaat.css';
</style>
<style type="text/css" media="print">
    @import './css/iaat_print.css';
</style>
</body>
</html>

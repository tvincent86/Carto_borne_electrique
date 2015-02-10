<?php
//// On définit la racine du site
define('RACINE_ROOT', '..');
if (!defined('RACINE_ROOT')){
    die("D&eacute;sol&eacute;, vous ne pouvez pas acc&eacute;der directement &agrave; ce fichier");
}
//// On redirige vers la page d'accueil 
header('Location: '.RACINE_ROOT.'/');
?>

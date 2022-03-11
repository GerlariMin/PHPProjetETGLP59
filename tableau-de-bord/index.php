<?php
session_start();

include("../ressources/php/fichiers_communs.php");
include("texte.php");

global $render;

/*
var_dump(
    shell_exec('c:\\"Program Files"\\Tesseract-OCR\\tesseract.exe c:\Users\gerla\Downloads\tesseract2.jpeg c:\Users\gerla\Downloads\resultat3SHELLEXEC'),
    exec('c:\"Program Files"\Tesseract-OCR\tesseract.exe c:\Users\gerla\Downloads\tesseract2.jpeg c:\Users\gerla\Downloads\resultat3EXEC')
);
*/

$traitement = new TraitementTableauDeBord($render);
$traitement->traitementRendu();
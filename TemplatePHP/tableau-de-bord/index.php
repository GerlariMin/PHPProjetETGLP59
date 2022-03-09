<?php
session_start();

include("../ressources/php/fichiers_communs.php");
include("texte.php");

global $render;
global $config;

$repertoires = str_split($_SESSION['identifiant'], 5);
$repertoireUtilisateur = $config['variables']['repertoires']['utilisateurs'] . $repertoires[0] . '/' . $repertoires[1] . '/';
if(is_dir($repertoireUtilisateur))
{
    if($iteration = opendir($repertoireUtilisateur))
    {
        while(($fichier = readdir($iteration)) !== false)
        {
            if($fichier != "." && $fichier != ".." && $fichier != "Thumbs.db")
            {
                echo '<a href="' . $repertoireUtilisateur . $fichier . '" target="_blank" >' . $fichier . ' ' . filesize($repertoireUtilisateur . $fichier) . '</a><br />'."\n";
            }
        }
        closedir($iteration);
    }
}
var_dump(
    shell_exec('c:\\"Program Files"\\Tesseract-OCR\\tesseract.exe c:\Users\gerla\Downloads\tesseract2.jpeg c:\Users\gerla\Downloads\resultat3SHELLEXEC'),
    exec('c:\"Program Files"\Tesseract-OCR\tesseract.exe c:\Users\gerla\Downloads\tesseract2.jpeg c:\Users\gerla\Downloads\resultat3EXEC')
);

$traitement = new TraitementTableauDeBord($render);
$traitement->traitementRendu();
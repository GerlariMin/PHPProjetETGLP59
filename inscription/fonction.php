<?php  
require_once("../ressources/config/config.inc.php");
require_once("ressources\php\Model.php");

global $config;


$model = Model::get_model($config);

//$con = mysqli_connect($host, $user, $passwd, $bdd);

//Je recupère la valeur envoyé depuis ma fonction js
$email = $_GET['email'];

$resultat = $model->verifierEmail($email);

// On va verifier l'email n'est pas dejà dans la BDD les utilisateurs ne sont pas dejà
//$sql = "SELECT emailUtilisateur FROM utilisateurs WHERE emailUtilisateur='{$email}'";

// Préparation de la requête 
//$stmt = mysqli_prepare($con, $sql);
//mysqli_stmt_execute($stmt);

// Lier des variables à une déclaration préparée 
//mysqli_stmt_bind_result($stmt, $col1);

// Récupération des valeurs 
//mysqli_stmt_fetch($stmt);

// On retourne la valeur
//echo $col1;
if ($resulat) {
    echo $resultat["EMAIL"];
}else {
    echo ("NULL");
}


mysqli_close($con);


//return $checkmail;
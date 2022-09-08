<?php

session_start();

session_regenerate_id();
include("../ressources/config/config.inc.php");
global $config;
include($config['variables']['chemin'] . "ressources/php/Model.php");

$infosUtilisateurs = Model::get_model($config);
$donneesUtilisateur = $infosUtilisateurs->donneesUtilisateur($_SESSION['identifiant']);

//$logs = new Logs($config);

$email = $donneesUtilisateur['emailUtilisateur'];

$model = Model::get_model($config);

// si le token correspond bien a une demande de modif (à définir)
if($model->confirmerMotDePasseModifie($_SESSION['identifiant'], $_GET['token'])){
    echo '
<head>
<link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">    
</head>
<body>
<div class="card">
<div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
  <i class="checkmark">✓</i>
</div>
  <h1>Success</h1> ';
echo "<p>Confirmation du changement de votre mot de passe !<br/></p> </div></body>";
echo '<a href="../connexion"><p>Retour à la page de connexion</p></a>';
echo '
<style>
body {
  text-align: center;
  padding: 40px 0;
  background: #EBF0F5;
}
  h1 {
    color: #88B04B;
    font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
    font-weight: 900;
    font-size: 40px;
    margin-bottom: 10px;
  }
  p {
    color: #404F5E;
    font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
    font-size:20px;
    margin: 0;
  }
i {
  color: #9ABC66;
  font-size: 100px;
  line-height: 200px;
  margin-left:-15px;
}
.card {
  background: white;
  padding: 60px;
  border-radius: 4px;
  box-shadow: 0 2px 3px #C8D0D8;
  display: inline-block;
  margin: 0 auto;
  margin-top: 150px;
}
</style>
';
}else{
    header("Location: ./erreur pas de modifs");
}
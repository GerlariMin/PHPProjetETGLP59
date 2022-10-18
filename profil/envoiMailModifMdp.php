<?php

session_start();

session_regenerate_id();
include('../ressources/php/Logs.php');
include("../ressources/config/config.inc.php");
global $config;
include($config['variables']['chemin'] . "ressources/php/Model.php");

$logs = new Logs($config);

$infosUtilisateurs = Model::get_model($config);
$donneesUtilisateur = $infosUtilisateurs->donneesUtilisateur($_SESSION['identifiant']);

$email = $donneesUtilisateur['emailUtilisateur'];

$sujet = '[OCRSQUARE] Confirmation changement mot de passe';

$token = bin2hex(random_bytes(15));

// header pour l'intégration d'html dans le mail
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
$headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";

if(isset($email)){

  $model = Model::get_model($config);
  $nouveauMotDePasseChiffre = password_hash($_SESSION['mdp'], PASSWORD_DEFAULT);

  // message formaté en HTML
  $message = '
  <html>
  <head>
  <title>Bonjour</title>
  </head>
  <body>
  <p>Bonjour ,</p>
  <p>Vous venez de faire la demande de modification de mot de passe. Pour la confirmer cliquer <a href="http://'. $config['variables']['redirection']['mail']['ip'] .'/PHPProjetETGLP59-sandbox/profil/confirmationMotDePasseModifie.php?token='. $token .'&identifiant='.$donneesUtilisateur['identifiantUtilisateur'].'">ici</a>.</p>
  <p>Merci,</p>
  <p>L\'équipe OCRSQUARE<p>
  </body>
  </html>
  ';

  // verification si mail dans la bdd
  if($model-> verifierEmail($email)){
      // 
      $model->motDePasseModifie($_SESSION['identifiant'], $nouveauMotDePasseChiffre, $token);
      $model->envoyerMail($email, $sujet, $message, $headers);
      $logs->messageLog('l\'email : '.$email.' existe en base et mail a été envoyé');
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
        echo "<p>Un lien de confirmation de modification de mot de passe a été envoyé à l'adresse suivante :<br/></p>";
        echo "<b>". $email ."</b></div>";
        echo '<a href="../tableauDeBord"><p>Retour au tableau de bord</p></a>';
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
      // email inexistant
      header("Location: ./?erreur=mauvais-email");
      $logs->messageLog('l\'email : '.$email.' n\'existe pas en base', $logs->typeError);
  };

} else {
  header("Location: ./?erreur=mauvais-email");
  $logs->messageLog('formatage de l\'email erroné', $logs->typeError);
}
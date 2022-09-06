<?php  
require_once('../ressources/php/Model.php');
require_once('../ressources/php/fichiers_communs.php');
session_start();

$model= Model::get_model($config);

// récuperation des entetes 
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];
$password = password_hash($pass, PASSWORD_DEFAULT);
$uuid = getUuid($model);

try {
   
   if ($uuid != null){
    $verif= $model->insererUtilisateur($uuid,$nom,$prenom,$user,$email,$password);
      //Si la requête est bonne alors on envoie en message de félicitation
      if ($verif){
         // subject
         $subject = '[OCRSQUARE] Confirmation inscription';
         $email="davidtaha18@gmail.com";
         
         // message
         $message = '
         <html>
         <head>
         <title>Bonjour</title>
         </head>
         <body>
         <p>Bonjour ' . $nom . ',</p>
         <p> Félicitations ! Votre compte a bien été crée, pour confirmer votre inscription veuillez cliquer <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">ici</a>.</p>
         <p>Merci,</p>
         <p>L\'équipe OCRSQUARE<p>
         <!--<img src="images/logo_principal.JPG" width="175" height="100">-->
         </body>
         </html>
         ';

         // To send HTML mail, the Content-type header must be set
         $headers  = 'MIME-Version: 1.0' . "\r\n";
         $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
         $headers .= 'From: OCRSQUARE <etglsquare@gmail.com>' . "\r\n";

         // renvoi true ou false (à ajuster quand il y aura accès à la base de donnée)
         ob_start();
         var_dump(mail("$email", $subject, $message, $headers));
         $output = ob_get_clean();

         //-------------------------------creation du répertoire----------------------------//

         $dir = substr($uuid,0,5);
         $subdir=substr($uuid,5,10);
         $path =  $config['variables']['repertoires']['utilisateurs']."./{$dir}/{$subdir}";
         var_dump($path);
         
         setDir($path);

         header("Location: ../connexion");
      }
   }else{
      //INITIALISATION DE LA SESSION
      header("Location: index.php");
      die();
   }
 } catch (PDOException $e) {
    echo ($e->getMessage() .' '. $e->getCode());
    die();
 }


function getUuid($model){

    $var = substr(uniqid(),0,10);

    var_dump($var);
    $test = $model->isUuid($var);

    var_dump($test);
    if($test === 0){
        return $var;
    }else{
        return Null;
    }
}

function setDir($mypath){
    if(!mkdir($mypath,0777,TRUE)){
      die('Échec lors de la création des dossiers...');
    }
}

?>


<?php  

session_start();

//Preparation de la connexion à la BDD
$host = 'localhost:3306';
$user = 'root';
$passwd = '';
$bdd = 'projetetglp59';

$con = mysqli_connect($host, $user, $passwd, $bdd);

// Vérification de la connexion
if (!$con) {
    
    die("<h3 class='container bg-dark p-3 text-center text-warning rounded-lg mt-5'>Not able to establish Database Connection<h3>". 
        mysqli_connect_error());
}

// récuperation des entetes 
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];
$password = password_hash($pass, PASSWORD_DEFAULT);
$uuid = getUuid($con);

var_dump($uuid);

 // Get data to display on index page
try {

   
   //Si le mail n'existe pas
   if ($uuid != null){
      //Insertion de l'utilisateur dans la BDD
      $sql = "INSERT INTO utilisateurs (identifiantUtilisateur,nomUtilisateur, prenomUtilisateur, loginUtilisateur, emailUtilisateur, motDePasseChiffreUtilisateur, AbonnementUtilisateur) 
      VALUES ('{$uuid}','{$nom}','{$prenom}','{$user}','{$email}','{$password}','1')";
      $query = mysqli_query($con, $sql);

      //Si la requête est bonne alors on envoie en message de félicitation
      if ($query){
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
         $path = "./{$dir}/{$subdir}";
         var_dump($path);
         
         //$config['variables']['repertoires']['utilisateurs']
         setDir($path);
      }
   }else{
      //INITIALISATION DE LA SESSION
      header("Location: index.php");
      die();
   }
 } catch (mysqli_sql_exception $e) {
    echo ($e->getMessage() .' '. $e->getCode());
 }


//------------------------FONCTION-----------------------------------------




function isUniq($myVar){
   // var_dump($myVar);
    $host = 'localhost:3307';
    $user = 'root';
    $passwd = '';
    $bdd = 'projetetglp59';

    $con = mysqli_connect($host, $user, $passwd, $bdd);
    //Je recupère la valeur envoyé depuis ma fonction js

    // On va verifier l'id n'est pas dejà dans la BDD les utilisateurs ne sont pas dejà
    $sql = "SELECT identifiantUtilisateur FROM utilisateurs WHERE identifiantUtilisateur='{$myVar}'";

    // Préparation de la requête 
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_execute($stmt);

    // Lier des variables à une déclaration préparée 
    mysqli_stmt_bind_result($stmt, $col1);
    //var_dump($myVar);
    // Récupération des valeurs 
    mysqli_stmt_fetch($stmt);

    mysqli_close($con);

    //var_dump($col1);
    // On retourne la valeur
    //var_dump(is_null($col1));

    if (is_null($col1)){
        return true;
    }

    return false;
}

function getUuid(){

    $var = substr(uniqid(),0,10);

    //var_dump($var);
    $test = isUniq($var);

    //var_dump($test);
    if($test){
        return $var;
    }else{
        return null;
    }
}

function setDir($mypath){
    if(!mkdir($mypath,0777,TRUE)){
      die('Échec lors de la création des dossiers...');
    }
}


mysqli_close($con);
?>


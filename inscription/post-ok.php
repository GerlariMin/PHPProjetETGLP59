<?php  

$host = 'localhost:3307';
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
$nom = $_POST['name'];
$prenom = $_POST['prenom'];
$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];
$password = password_hash($pass, PASSWORD_DEFAULT);

 // Get data to display on index page
 try {
    $sql = "INSERT INTO utilisateurs (nomUtilisateur, prenomUtilisateur, loginUtilisateur, emailUtilisateur, motDePasseChiffreUtilisateur, AbonnementUtilisateur) 
    VALUES ('{$nom}','{$prenom}','{$user}','{$email}','{$password}','1')";
 $query = mysqli_query($con, $sql);
 } catch (mysqli_sql_exception $e) {
    echo ($e->getMessage() .' '. $e->getCode());
 }



mysqli_close($con);


?>


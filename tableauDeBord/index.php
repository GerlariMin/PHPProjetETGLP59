<?php
    session_start();
    // Cette partie n'est accessible que si l'utilisateur est connecté
    if(isset($_SESSION['login'])) {
        var_dump($_SESSION);
        include("../ressources/php/fichiers_communs.php");
        /*
        var_dump(
            shell_exec('c:\\"Program Files"\\Tesseract-OCR\\tesseract.exe c:\Users\gerla\Downloads\tesseract2.jpeg c:\Users\gerla\Downloads\resultat3SHELLEXEC'),
            exec('c:\"Program Files"\Tesseract-OCR\tesseract.exe c:\Users\gerla\Downloads\tesseract2.jpeg c:\Users\gerla\Downloads\resultat3EXEC')
        );
        */
        $traitement = new TraitementTableauDeBord($render);
        $traitement->traitementRendu();
    } else {
        header('Location: ../connexion/?erreur=5');
        exit();
    }
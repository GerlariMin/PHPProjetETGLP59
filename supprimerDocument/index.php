<?php
session_start();
include("../ressources/php/fichiers_communs.php");
// Cette partie n'est accessible que si l'utilisateur est connecté
if (isset($_SESSION['login'])) {
    $logs->messageLog('Utilisateur "' . $_SESSION['login'] .'" veut supprimer un fichier.', $logs->typeNotice);
    // Si on a bien un document dans l'URL
    if (isset($_GET['document'])) {
        $logs->messageLog('Suppression souhaitée du document "' . $_GET['document'] .'.', $logs->typeNotice);
        require_once("./requetes.php");
        $requetes = new RequetesSupprimerDocument($config, $logs);
        // Récupération de l'identifiant de l'utilsateur connecté
        $identifiantUtilisateur = $_SESSION['identifiant'];
        // Décomposition de l'identifiant utilisateur pour trouver le répertoire et sous répertoire où placer les fichiers
        $repertoireUtilisateur = substr($identifiantUtilisateur,0,5);
        $sousRepertoireUtilisateur = substr($identifiantUtilisateur,5,10);
        // Chemin répertoire complet
        $cheminRepertoire = $config['variables']['repertoires']['utilisateurs'] . $repertoireUtilisateur . '/' . $sousRepertoireUtilisateur . '/';
        // Si il s'agit d'un fichier dans l'espace résultat
        if (isset($_GET['resultat'])) {
            $logs->messageLog('Document "' . $_GET['document'] .' issu du répertoire résultat.', $logs->typeNotice);
            $cheminRepertoire .= 'resultat/';
            // On supprime le fichier du répertoire dans lequel on vient de le placer
            if (exec('rm -f ' . $cheminRepertoire . $_GET['document']) !== false) {
                $logs->messageLog('Suppression effectuée dans le répertoire résultats.', $logs->typeInfo);
                header('Location: ../tableauDeBord/?succes=fsup');
                exit();
            }
            // Si la suppression n'est pas faite sur l'espace disque
            $logs->messageLog('La suppression sur l\'espace disque a échoué.', $logs->typeError);
            header('Location: ../tableauDeBord/?erreur=pfsup');
            exit();
        }
        // On commence par supprimer les informations en base
        if ($requetes->supprimerDocument($_GET['document'], $_SESSION['identifiant'])) {
            $logs->messageLog('Suppression effectuée en base.', $logs->typeInfo);
            // On supprime le fichier du répertoire dans lequel on vient de le placer
            if (exec('rm -f ' . $cheminRepertoire . $_GET['document']) !== false) {
                $logs->messageLog('Suppression effectuée dans le répertoire.', $logs->typeInfo);
                header('Location: ../tableauDeBord/?succes=fsup');
                exit();
            }
            // Si la suppression n'est pas faite sur l'espace disque
            $logs->messageLog('La suppression sur l\'espace disque a échoué.', $logs->typeError);
            header('Location: ../tableauDeBord/?erreur=pfsup');
            exit();
        }
        // Si la suppression ne s'est pas faite en base
        $logs->messageLog('Suppression en base échouée.', $logs->typeError);
        header('Location: ../tableauDeBord/?erreur=pfsup');
        exit();
    }
    // Si pas de document dans le GET
    $logs->messageLog('Pas de document dans l\'URL.', $logs->typeWarning);
    header('Location: ../tableauDeBord/?erreur=nfsup');
    exit();
}
// Si l'utilisateur n'est pas connecté
$logs->messageLog('Utilisateur non connecté. Redirection vers la page de connexion.', $logs->typeError);
header('Location: ../connexion/?erreur=5');
exit();
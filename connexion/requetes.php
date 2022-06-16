<?php
require_once('../ressources/php/Model.php');
class RequetesConnexion extends Model {

    private Model $model;

    public function __construct(array $config) {
        $this->model = Model::get_model($config);
    }

    public function recupererIdentifiant(String $login): mixed
    {
        $req = $this->model->bdd->prepare("SELECT identifiantUtilisateur AS IDENTIFIANT FROM utilisateurs WHERE emailUtilisateur = :login OR loginUtilisateur = :login;");
        $req->bindValue(":login", $login);

        $req->execute();
        
        return $req->fetch(PDO::FETCH_ASSOC)['IDENTIFIANT'];
    }

    public function recupererMotDePasseCourant(String $identifiant)
    {
        $req = $this->model->bdd->prepare("SELECT motDePasseChiffreUtilisateur AS PHRASE FROM utilisateurs WHERE identifiantUtilisateur = :identifiant AND motDePasseOublie = false;");
        $req->bindValue(":identifiant", $identifiant);

        $req->execute();

        return $req->fetch(PDO::FETCH_ASSOC)['PHRASE'];
    }

    public function recupererUtilisateur(string $login, string $identifiant)
    {
        $req = $this->model->bdd->prepare("SELECT loginUtilisateur AS LOGIN, nomUtilisateur AS NOM, prenomUtilisateur AS PRENOM, emailUtilisateur AS EMAIL FROM utilisateurs WHERE (emailUtilisateur = :login OR loginUtilisateur = :login) AND identifiantUtilisateur = :identifiant;");
        $req->bindValue(":login", $login);
        $req->bindValue(":identifiant", $identifiant);

        $req->execute();

        return $req->fetch(PDO::FETCH_ASSOC);
    }
}
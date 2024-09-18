<?php

namespace Gsbfrais\Controllers;

use Gsbfrais\models\ProfilManager;
use Gsbfrais\models\RegionManager;
use Gsbfrais\models\UtilisateurManager;

class UtilisateurController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(): void
    {
        $errorMessage = '';

        if (count($_POST) > 0) {
            $errorMessage = $this->validerConnexion();

            if (empty($errorMessage) == true) {
                header("Location: " . ROOT_URL . "accueil");
                exit;
            }
        }

        $this->render('utilisateur/connexion', [
            'title' => 'Connexion',
            'errorMessage' => $errorMessage
        ]);
    }

    private function validerConnexion(): string
    {
        $errors = '';
        $utilisateurManager = new UtilisateurManager();

        $login = $_POST['login'];
        $mdp = $_POST['mdp'];
        $utilisateur = $utilisateurManager->getUtilisateur($login);

        if ($utilisateur === false || password_verify($mdp, $utilisateur->mot_passe) == false) {
            $errors .= "Login et/ou mot de passe incorrects";
        } else {
            $_SESSION['idUtil'] = $utilisateur->id;
            $_SESSION['nomUtil'] = $utilisateur->nom;
            $_SESSION['prenomUtil'] = $utilisateur->prenom;
            $_SESSION['profilUtil'] = $utilisateur->nom_profil;
            $_SESSION['idRegion'] = $utilisateur->id_region;
            $_SESSION['date_embauche'] = $utilisateur->date_embauche;
        }
        return $errors;
    }

    public function logout(): void
    {
        // Détruit toutes les variables de session
        $_SESSION = [];

        // Supprime le cookie de session.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Détruit la session.
        session_destroy();

        header("Location: " . ROOT_URL . "login");
    }

    public function voirUtilisateurs()
    {
        if ($_SESSION['profilUtil'] != 'administrateur') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité voirUtilisateurs (UtilisateurController) non autorisée');
        }

        $utilisateurManager = new UtilisateurManager();
        $profilManager = new ProfilManager();

        $utilisateurs = $utilisateurManager->getUtilisateurs();
        $profils = $profilManager->getProfils();

        $this->render('utilisateur/voirUtilisateurs', [
            'title' => 'Gestion des utilisateurs',
            'utilisateurs' => $utilisateurs,
            'profils' => $profils
        ]);
    }

    public function modifierUtilisateur($id): void
    {
        if ($_SESSION['profilUtil'] != 'administrateur') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité voirUtilisateurs (UtilisateurController) non autorisée');
        }

        if (count($_POST) > 0) {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_DEFAULT);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_DEFAULT);
            $login = filter_input(INPUT_POST, 'login', FILTER_DEFAULT);
            $date_embauche = filter_input(INPUT_POST, 'date_embauche', FILTER_DEFAULT);
            $date_depart = filter_input(INPUT_POST, 'date_depart', FILTER_DEFAULT);
            $id_region = filter_input(INPUT_POST, 'id_region', FILTER_DEFAULT);
            $id_profil = filter_input(INPUT_POST, 'id_profil', FILTER_DEFAULT);

            $utilisateurManager = new UtilisateurManager();
            $utilisateur = $utilisateurManager->modifierUtilisateur($id, $nom, $prenom, $date_embauche, $date_depart, $id_region, $id_profil);
            return;
        }

        $utilisateurManager = new UtilisateurManager();
        $profilManager = new ProfilManager();
        $regionManager = new RegionManager();

        $utilisateur = $utilisateurManager->getUtilisateurById($id);
        $profils = $profilManager->getProfils();
        $regions = $regionManager->getRegions();

        if ($utilisateur == false) {
            http_response_code(404);
            throw new \Exception("Tentative de suppression d'un utilisateur qui n'existe plus");
        }

        $this->render('utilisateur/modifierUtilisateurs', [
            'title' => 'Modifier un utilisateur',
            'utilisateur' => $utilisateur,
            'profils' => $profils,
            'regions' => $regions
        ]);
    }

    public function supprimerUtilisateur($id): void
    {
        if ($_SESSION['profilUtil'] != 'administrateur') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité voirUtilisateurs (UtilisateurController) non autorisée');
        }

        $utilisateurManager = new UtilisateurManager();

        $utilisateur = $utilisateurManager->getUtilisateurById($id);

        if ($utilisateur == false) {
            http_response_code(404);
            throw new \Exception("Tentative de suppression d'un utilisateur qui n'existe plus");
        }

        $utilisateurManager->supprimerUtilisateur($id);

        $this->voirUtilisateurs();
    }
}
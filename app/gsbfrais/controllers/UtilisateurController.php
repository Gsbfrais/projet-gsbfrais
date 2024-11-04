<?php

namespace Gsbfrais\Controllers;

use Gsbfrais\models\EchelonManager;
use Gsbfrais\models\ProfilManager;
use Gsbfrais\models\RegionManager;
use Gsbfrais\models\UtilisateurManager;

class UtilisateurController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     * @throws \Exception
     */
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

    /**
     * @return string
     * @throws \Exception
     */
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
            $_SESSION['plafondKm'] = $utilisateur->plafondKm;
            $_SESSION['plafondetp'] = $utilisateur->plafondetp;
            $_SESSION['id_niveauexpertise'] = $utilisateur->id_niveauexpertise;
        }
        return $errors;
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     * @throws \Exception
     */
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

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public function modifierUtilisateur($id)
    {
        if ($_SESSION['profilUtil'] != 'administrateur') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité voirUtilisateurs (UtilisateurController) non autorisée');
        }

        $errorMessage = '';

        $utilisateurManager = new UtilisateurManager();
        $profilManager = new ProfilManager();
        $regionManager = new RegionManager();
        $echelonManager = new EchelonManager();

        $utilisateur = $utilisateurManager->getUtilisateurById($id);
        $profils = $profilManager->getProfils();
        $regions = $regionManager->getRegions();
        $echelons = $echelonManager->getLesEchelons();

        if (count($_POST) > 0) {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_DEFAULT);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_DEFAULT);
            $login = filter_input(INPUT_POST, 'login', FILTER_DEFAULT);
            $date_embauche = filter_input(INPUT_POST, 'date_embauche', FILTER_DEFAULT);
            $date_depart = filter_input(INPUT_POST, 'date_depart', FILTER_DEFAULT);
            $id_region = filter_input(INPUT_POST, 'id_region', FILTER_DEFAULT);
            $id_profil = filter_input(INPUT_POST, 'id_profil', FILTER_DEFAULT);
            $id_echelon = filter_input(INPUT_POST, 'id_echelon', FILTER_DEFAULT);

            if ($date_depart == '') {
                $date_depart = null;
            }

            $errorMessage = $this->verifierUtilisateur($nom, $prenom, $login, $date_embauche, $id_region, $id_profil, $id_echelon);

            if (empty($errorMessage) == true) {
                $utilisateurManager = new UtilisateurManager();
                $utilisateurManager->modifierUtilisateur($id, $nom, $prenom, $login, $date_embauche, $date_depart, $id_region, $id_profil, $id_echelon);
                header('Location: voirUtilisateurs');
            }
        }

        $this->render('utilisateur/modifierUtilisateurs', [
            'title' => 'Modifier un utilisateur',
            'utilisateur' => $utilisateur,
            'profils' => $profils,
            'regions' => $regions,
            'echelons' => $echelons,
            'errorMessage' => $errorMessage
        ]);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function ajouterUtilisateur()
    {
        if ($_SESSION['profilUtil'] != 'administrateur') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité ajouterUtilisateurs (UtilisateurController) non autorisée');
        }

        $errorMessage = '';

        if (count($_POST) > 0) {
            $nom = filter_input(INPUT_POST, 'nom', FILTER_DEFAULT);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_DEFAULT);
            $login = filter_input(INPUT_POST, 'login', FILTER_DEFAULT);
            $date_embauche = filter_input(INPUT_POST, 'date_embauche', FILTER_DEFAULT);
            $date_depart = filter_input(INPUT_POST, 'date_depart', FILTER_DEFAULT);
            $id_region = filter_input(INPUT_POST, 'id_region', FILTER_DEFAULT);
            $id_profil = filter_input(INPUT_POST, 'id_profil', FILTER_DEFAULT);
            $id_echelon = filter_input(INPUT_POST, 'id_niveauexpertise', FILTER_DEFAULT);

            if ($date_depart == '') {
                $date_depart = null;
            }

            if ($date_embauche == '') {
                $date_embauche = null;
            }

            $errorMessage .= $this->verifierUtilisateur($nom, $prenom, $login, $date_embauche, $id_region, $id_profil, $id_echelon);

            if (empty($errorMessage) == true) {
                $utilisateurManager = new UtilisateurManager();
                $utilisateur = $utilisateurManager->ajouterUtilisateur($nom, $prenom, $login, $date_embauche, $date_depart, $id_region, $id_profil, $id_echelon);
                header('Location: voirUtilisateurs');
            }
        }

        $profilManager = new ProfilManager();
        $regionManager = new RegionManager();
        $echelonManager = new EchelonManager();

        $profils = $profilManager->getProfils();
        $regions = $regionManager->getRegions();
        $echelons = $echelonManager->getLesEchelons();

        $this->render('utilisateur/ajouterUtilisateur', [
            'title' => 'Ajouter un utilisateur',
            'profils' => $profils,
            'regions' => $regions,
            'echelons' => $echelons,
            'errorMessage' => $errorMessage,
        ]);
    }

    /**
     * @param string $nom
     * @param string $prenom
     * @param string $login
     * @param string|null $date_embauche
     * @param int|null $id_region
     * @param int|null $id_profil
     * @return string
     */
    private function verifierUtilisateur(string $nom, string $prenom, string $login, ?string $date_embauche, ?int $id_region, ?int $id_profil, ?int $id_echelon): string
    {
        $errors = '';
        if (empty($nom) == true) {
            $errors .= "Vous devez renseigner le nom<br>";
        }
        if (empty($prenom) == true) {
            $errors .= "Vous devez renseigner le prenom<br>";
        }
        if (empty($login) == true) {
            $errors .= "Vous devez renseigner le login<br>";
        }
        if ($id_region == null) {
            $errors .= "Vous devez renseigner la region<br>";
        }
        if ($id_profil == null) {
            $errors .= "Vous devez renseigner le profil<br>";
        }
        if ($id_profil == null) {
            $errors .= "Vous devez renseigner l'échelon<br>";
        }
        if ($date_embauche == null) {
            $errors .= "Vous devez renseigner la date d'embauche<br>";
        }

        return $errors;
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
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

        header('Location: voirUtilisateurs');
    }
    // Autres méthodes ...

    /**
     * Méthode pour afficher et traiter la modification du mot de passe
     */
    public function changerMotPasse(): void
    {
        $utilisateurManager = new UtilisateurManager();
        $errorMessage = '';
        $changed = false;

        if (count($_POST) > 0) { // Le formulaire est validé
            // Recupère les données saisies dans le formulaire
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            $errorMessage = $this->validerModificationMotDePasse($currentPassword, $newPassword, $confirmPassword);

            // S'il n'y a pas d'erreurs, on met à jour le mot de passe
            if (empty($errorMessage)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $utilisateurManager->updatePassword($_SESSION['idUtil'], $hashedPassword);
                $changed = true;
            }
        }

        // Affiche le formulaire de modification

        $this->render('utilisateur/changerMotPasse', [
            'title' => 'Connexion',
            'errorMessage' => $errorMessage, 
            'changed' => $changed
        ]);
    }

    /**
     * Valide et traite la modification du mot de passe
     */
    private function validerModificationMotDePasse($currentPassword, $newPassword, $confirmPassword): string
    {
        $errors = '';
        $utilisateurManager = new UtilisateurManager();
        $idUtil = $_SESSION['idUtil'];

        // Récupère l'utilisateur actuel
        $utilisateur = $utilisateurManager->getUtilisateurById($idUtil);

        // Vérifie si le mot de passe actuel est correct
        if (!$utilisateur || !password_verify($currentPassword, $utilisateur->mot_passe)) {
            $errors .= "Votre mot de passe actuel est incorrect.<br>";
        }

        // Vérifie si les nouveaux mots de passe correspondent
        if ($newPassword !== $confirmPassword) {
            $errors .= "Le nouveau mot de passe et la confirmation ne correspondent pas.<br>";
        }

        // Vérifier que le nouveau mot de passe est suffisamment sécurisé
        if (strlen($newPassword) < 8) {
            $errors .= "Le nouveau mot de passe doit contenir au moins 8 caractères.<br>";
        }
        
        if ($currentPassword == $newPassword) {
            $errors .= "Le nouveau mot de passe doit être différent de l'actuel.<br>";
        }
        return $errors;
    }
}

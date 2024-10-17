<?php

namespace Gsbfrais\Controllers;

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
            $_SESSION['nomUtil'] =  $utilisateur->nom;
            $_SESSION['prenomUtil'] =  $utilisateur->prenom;
            $_SESSION['profilUtil'] =  $utilisateur->nom_profil;
            $_SESSION['idRegion'] =  $utilisateur->id_region;
            $_SESSION['date_embauche'] =  $utilisateur->date_embauche;
            $_SESSION['plafondetp'] = $utilisateur->plafondetp;
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

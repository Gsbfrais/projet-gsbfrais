<?php

use Gsbfrais\Autoloader;
use Gsbfrais\Controllers\{AccueilController, FicheFraisController, FraisForfaitController, FraisHorsForfaitController, UtilisateurController,IndicateursController};

session_start();
require_once('config/config.php');
require_once('includes/fonctions.php');
require_once "./autoloader.php";

Autoloader::register();

$action = "accueil";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}
if (estVisiteurConnecte() == false) {
    $action = 'login';
}
try {
    switch ($action) {
        case 'accueil':
            $controller = new AccueilController();
            $controller->accueil();
            break;
            // Gestion utilisateurs
        case 'login':
            $controller = new UtilisateurController();
            $controller->login();
            break;
        case 'logout':
            $controller = new UtilisateurController();
            $controller->logout();
            break;
        // Gestion des frais forfaitisés
        case 'changerMotPasse':
            $controller = new UtilisateurController();
            $controller->changerMotPasse();
            break;
            // Gestion des frais forfaitisés
        case 'saisirFraisForfait':
            $controller = new FraisForfaitController();
            $controller->saisirFraisForfait();
            break;
        case 'supprimerFraisForfait':
            $codeCategorie = filter_input(INPUT_GET, 'codeCategorie', FILTER_SANITIZE_SPECIAL_CHARS);
            $codeCategorie = trim($codeCategorie);
            if (empty($codeCategorie)) {
                http_response_code(400);
                throw new Exception('Bad request');
            }
            $controller = new FraisForfaitController();
            $controller->supprimerFraisForfait($codeCategorie);
            break;
            // Gestion des frais hors forfait
        case 'saisirFraisHorsForfait':
            $controller = new FraisHorsForfaitController();
            $controller->saisirFraisHorsForfait();
            break;
        case 'supprimerFraisHorsForfait':
            $numFrais = filter_input(INPUT_GET, 'numFrais', FILTER_VALIDATE_INT);
            if (empty($numFrais)) {
                http_response_code(400);
                throw new Exception('Bad request');
            }
            $controller = new FraisHorsForfaitController();
            $controller->supprimerFraisHorsForfait($numFrais);
            break;
            // Affichage des fiches de frais
        case 'voirFicheFrais':
            $controller = new FicheFraisController();
            $controller->voirFicheFrais();
            break;
            // Validation et clotûre des fiches de frais
        case 'cloturerFichesFrais':
            $controller = new FicheFraisController();
            $controller->cloturerFichesFrais();
            break;
        case 'voirFichesFraisEnAttenteValidation':
            $controller = new FicheFraisController();
            $controller->voirFichesFraisEnAttenteValidation();
            break;
        case 'validerFicheFrais':
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_VALIDATE_INT);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_VALIDATE_INT);
            if (empty($idVisiteur) or empty($mois)) {
                http_response_code(400);
                throw new Exception('Bad request');
            }
            $controller = new FicheFraisController();
            $controller->validerFicheFrais($idVisiteur, $mois);
            break;
            // Consultations pour délégué régional
        case 'voirFichesFraisRegion':
            $controller = new FicheFraisController();
            $controller->voirFichesFraisRegion();
            break;
        // Gestion des utilisateurs
        case 'voirUtilisateurs':
            $controller = new UtilisateurController();
            $controller->voirUtilisateurs();
            break;
        case 'supprimerUtilisateur':
            $idUtilisateur = filter_input(INPUT_GET, 'idUtilisateur', FILTER_VALIDATE_INT);
            if (empty($idUtilisateur)) {
                http_response_code(400);
                throw new Exception('Bad request');
            }
            $controller = new UtilisateurController();
            $controller->supprimerUtilisateur($idUtilisateur);
            break;
        case 'modifierUtilisateur':
            $idUtilisateur = filter_input(INPUT_GET, 'idUtilisateur', FILTER_VALIDATE_INT);
            if (empty($idUtilisateur)) {
                http_response_code(400);
                throw new Exception('Bad request');
            }
            $controller = new UtilisateurController();
            $controller->modifierUtilisateur($idUtilisateur);
            break;
        case 'ajouterUtilisateur':
            $controller = new UtilisateurController();
            $controller->ajouterUtilisateur();
            break;
        case 'voirDetailFicheFrais':
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_VALIDATE_INT);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_VALIDATE_INT);
            if (empty($idVisiteur) or empty($mois)) {
                http_response_code(400);
                throw new Exception('Bad request');
            }
            $controller = new FicheFraisController();
            $controller->voirDetailFicheFrais($idVisiteur, $mois);
            break;
        // Indicateurs
        case 'voirIndicateurs':
            $controller = new IndicateursController();
            $controller->voirIndicateursVM();
            break;


        default:
            http_response_code(404);
            throw new Exception('Not Found');
    }
} catch (Exception $exception) {
    $mesg = genereMessageException($exception);
    if (MOD_DEV == true) {
        die($mesg);
    } else {
        $codeHttp = http_response_code();
        $erreurHttp = STATUT_ERREUR_HTTP[$codeHttp][0];
        $errorMsg = STATUT_ERREUR_HTTP[$codeHttp][1];
        journaliser('ERREUR', $mesg, $codeHttp);
        require('views/vue-erreurs.php');
    }
}

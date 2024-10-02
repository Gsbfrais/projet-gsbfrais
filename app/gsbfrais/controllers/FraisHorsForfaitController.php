<?php

namespace Gsbfrais\Controllers;

use DateTime;
use Gsbfrais\models\{EchelonManager, FicheFraisManager, FraisHorsForfaitManager, UtilisateurManager};

class FraisHorsForfaitController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saisirFraisHorsForfait(): void
    {
        $ficheFraisManager = new FicheFraisManager();
        $fraisHorsForfaitManager = new FraisHorsForfaitManager();
        $echelonManager = new EchelonManager();
        $echelonUtilisateur = $this->getEchelonUtilisateur();

        // Création de la fiche de frais du mois si elle n'existe pas encore
        if ($ficheFraisManager->estPremierFraisMois($_SESSION['idUtil'], $this->mois)) {
            $ficheFraisManager->ajouteFicheFrais($_SESSION['idUtil'], $this->mois);
        }

        $errorMessage = '';
        $dateFrais = '';
        $libelle = '';
        $montant = '';

        // Le formulaire est posté
        if (count($_POST) > 0) {
            $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_DEFAULT);
            $libelle = filter_input(INPUT_POST, 'libelle', FILTER_DEFAULT);
            $libelle = strip_tags($libelle);
            $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);

            $errorMessage = $this->verifierInfosFraisHorsForfait($dateFrais, $libelle, $montant, $echelonUtilisateur);

            // Mise à jour de la base de données si aucune erreur
            if (empty($errorMessage) == true) {
                $fraisHorsForfaitManager = new FraisHorsForfaitManager();
                $fraisHorsForfaitManager->ajouteFraisHorsForfait($_SESSION['idUtil'], $this->mois, $libelle, $dateFrais, $montant);
                $dateFrais = '';
                $libelle = '';
                $montant = '';
            }
        }

        $lesFraisHorsForfait = $fraisHorsForfaitManager->getLesFraisHorsForfait($_SESSION['idUtil'], $this->mois);

        $this->render('fraisHorsForfait/gestionFraisHorsForfait', [
            'title' => 'Saisie frais hors forfait',
            'periode' => date("m/Y"),
            'lesFraisHorsForfait' => $lesFraisHorsForfait,
            'errorMessage' => $errorMessage,
            'dateFrais' => $dateFrais,
            'libelle' => $libelle,
            'montant' => $montant,
            'echelon' => $echelonUtilisateur
        ]);
    }

    private function verifierInfosFraisHorsForfait($dateFrais, $libelle, $montant, $plafondUtilisateur): string
    {
        $errors = '';

        if (empty($dateFrais) == true) {
            $errors .= "Vous devez renseigner la date<br>";
        } elseif (estDatevalide($dateFrais) == false) {
            $errors .= "Date invalide";
        } elseif (estDateDepassee($dateFrais) == true) {
            $errors .= "date d'enregistrement du frais dépassé depuis plus de 1 an<br>";
        } elseif ($_SESSION['date_embauche'] > $dateFrais) {
            $errors .= "La date doit être supérieure à votre date d'embauche.<br> ";
        }
        if (empty($libelle) == true) {
            $errors .= "Le libellé est obligatoire<br>";
        }

        if ($montant === false) { // comparaison en type ET en valeur !
            $errors .= "Le montant doit être renseigné et numérique<br>";
        } elseif ($montant <= 0) {
            $errors .= "Le montant doit être strictement supérieur à 0<br";
        }

        if ($montant > $plafondUtilisateur->plafond) {
            $errors .= "Le montant doit être inférieur ou égal à votre plafond (" . $plafondUtilisateur->plafond . "€)";
        }

        return $errors;
    }

    private function getEchelonUtilisateur(): mixed
    {
        $utilisateurManager = new UtilisateurManager();
        $echelonManager = new EchelonManager();

        $echelons = $echelonManager->getLesEchelons();
        $utilisateur = $utilisateurManager->getUtilisateurById($_SESSION['idUtil']);
        $dateEmbaucheUtilisateur = DateTime::createFromFormat('Y-m-d', $utilisateur->date_embauche);

        if ($dateEmbaucheUtilisateur < DateTime::createFromFormat('Y-m-d', '2001-01-01')) {
            return $echelons[0];
        } elseif ($dateEmbaucheUtilisateur < DateTime::createFromFormat('Y-m-d', '2010-12-31') && DateTime::createFromFormat('Y-m-d', '2001-01-01') < $dateEmbaucheUtilisateur) {
            return $echelons[1];
        } else {
            return $echelons[2];
        }

    }

    public function supprimerFraisHorsForfait($numFrais): void
    {
        $fraisHorsForfaitManager = new FraisHorsForfaitManager();

        $leFrais = $fraisHorsForfaitManager->getFraisHorsForfait($_SESSION['idUtil'], $this->mois, $numFrais);

        if ($leFrais == false) {
            http_response_code(404);
            throw new \Exception("Tentative de suppression d'un frais hors forfait qui n'existe plus");
        }
        if ($leFrais->id_visiteur != $_SESSION['idUtil']) {
            http_response_code(403);
            throw new \Exception("Tentative de suppression d'un frais hors forfait sans habilitation");
        }
        $fraisHorsForfaitManager->supprimeFraisHorsForfait($_SESSION['idUtil'], $this->mois, $numFrais);

        $lesFraisHorsForfait = $fraisHorsForfaitManager->getLesFraisHorsForfait($_SESSION['idUtil'], $this->mois);

        header('Location: saisirFraisHorsForfait');

    }
}

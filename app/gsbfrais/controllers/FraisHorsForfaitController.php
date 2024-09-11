<?php
namespace Gsbfrais\Controllers;

use Gsbfrais\models\{FicheFraisManager, FraisHorsForfaitManager};

class FraisHorsForfaitController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saisirFraisHorsForfait():void
    {
        $ficheFraisManager = new FicheFraisManager();
        $fraisHorsForfaitManager = new FraisHorsForfaitManager();

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

            $errorMessage = $this->verifierInfosFraisHorsForfait($dateFrais, $libelle, $montant);

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

        ]);
    }

    private function verifierInfosFraisHorsForfait($dateFrais, $libelle, $montant): string
    {
        $errors = '';
        if (empty($dateFrais) == true) {
            $errors .= "Vous devez renseigner la date<br>";
        } elseif (estDatevalide($dateFrais) == false) {
            $errors .= "Date invalide";
        } elseif (estDateDepassee($dateFrais) == true) {
            $errors .= "date d'enregistrement du frais dépassé depuis plus de 1 an<br>";
        }

        if (empty($libelle) == true) {
            $errors .= "Le libellé est obligatoire<br>";
        }

        if ($montant === false) { // comparaison en type ET en valeur !
            $errors .= "Le montant doit être renseigné et numérique<br>";
        }
        return $errors;
    }

    public function supprimerFraisHorsForfait($numFrais):void
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

        $this->render('fraisHorsForfait/gestionFraisHorsForfait', [
            'title' => 'Saisie frais hors forfait',
            'periode' => date("m/Y"),
            'lesFraisHorsForfait' => $lesFraisHorsForfait,
            'errorMessage' => ''
        ]);
    }
}

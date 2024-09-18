<?php

namespace Gsbfrais\Controllers;

use Gsbfrais\models\{FicheFraisManager, FraisForfaitManager};

class FraisForfaitController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saisirFraisForfait(): void
    {
        $ficheFraisManager = new FicheFraisManager();
        $fraisForfaitManager = new FraisForfaitManager();

        // Création de la fiche de frais du mois si elle n'existe pas encore
        if ($ficheFraisManager->estPremierFraisMois($_SESSION['idUtil'], $this->mois)) {
            $ficheFraisManager->ajouteFicheFrais($_SESSION['idUtil'], $this->mois);
        }

        $errorMessage = '';
        $codeCategorieSelectionnee = '';
        $quantite = '';

        if (count($_POST) > 0) {
            $codeCategorieSelectionnee = filter_input(INPUT_POST, 'codeCategorie', FILTER_DEFAULT);
            $codeCategorieSelectionnee = strip_tags($codeCategorieSelectionnee);
            $quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_FLOAT);
            

            $errorMessage = $this->verifierQteFraisForfait($codeCategorieSelectionnee, $quantite);

            // Mise à jour de la base de données si aucune erreur
            if (empty($errorMessage) == true) {
                $fraisForfaitManager = new FraisForfaitManager();
                $fraisForfaitManager->ajouteFraisForfait($_SESSION['idUtil'], $this->mois, $codeCategorieSelectionnee, $quantite);
                $codeCategorieSelectionnee = '';
                $quantite = '';
            }
        }

        $lesFraisForfait = $fraisForfaitManager->getLesFraisForfait($_SESSION['idUtil'], $this->mois);
        $lesCategories = $fraisForfaitManager->getLesCategoriesDisponiblesPourFicheFrais($_SESSION['idUtil'], $this->mois);

        $this->render('fraisForfait/gestionFraisForfait', [
            'title' => 'Saisie frais forfaitisés',
            'periode' => date("m/Y"),
            'lesFraisForfait' => $lesFraisForfait,
            'lesCategories' => $lesCategories,
            'errorMessage' => $errorMessage,
            'codeCategorieSelectionnee' => $codeCategorieSelectionnee,
            'quantite' => $quantite
        ]);
    }

    private function verifierQteFraisForfait($codeCategorie, $quantite): string
    {
        $errors = '';
        if (empty($codeCategorie) == true) {
            $errors .= "Vous devez renseigner le code catégorie<br>";
        }

        if ($quantite === false) { // comparaison en type ET en valeur !
            $errors .= "La quantité doit être renseignée et numérique<br>";
        }
        $mm = substr($this->mois, 4, 2);
        $aa = substr($this->mois, 0, 4);
        $nbJourMois = cal_days_in_month(CAL_GREGORIAN, $mm, $aa);
        if ($quantite > $nbJourMois) {
            $errors .= "La quantité doit être inférieure au nombre de jours du mois<br>";
        }
        
        if($codeCategorie == 'KM' &&  $_SESSION['plafondKm'] > $quantite  ){
            $errors .= "Le plafond kilometrique n'est pas respecter<br>";
        }

        
        
        return $errors;
    }

    public function supprimerFraisForfait($codeCategorie): void
    {
        $fraisForfaitManager = new fraisForfaitManager();

        $leFrais = $fraisForfaitManager->getFraisForfaitByKey($_SESSION['idUtil'], $this->mois, $codeCategorie);

        if ($leFrais == false) {
            http_response_code(404);
            throw new \Exception("Tentative de suppression d'un frais forfait qui n'existe plus");
        }
        if ($leFrais->id_visiteur != $_SESSION['idUtil']) {
            http_response_code(403);
            throw new \Exception("Tentative de suppression d'un frais forfait par un utilisateur non habilité");
        }
        $fraisForfaitManager->supprimeFraisForfait($_SESSION['idUtil'], $this->mois, $codeCategorie);
        $lesCategories = $fraisForfaitManager->getLesCategoriesDisponiblesPourFicheFrais($_SESSION['idUtil'], $this->mois);

        // Récupère la liste des frais forfaitisés mise à jour pour affichage 
        $lesFraisForfait = $fraisForfaitManager->getLesFraisForfait($_SESSION['idUtil'], $this->mois);

        $this->render('fraisForfait/gestionFraisForfait', [
            'title' => 'Saisie frais forfaitisés',
            'periode' => date("m/Y"),
            'lesFraisForfait' => $lesFraisForfait,
            'lesCategories' => $lesCategories,
            'codeCategorie' => '',
            'quantite' => ''
        ]);
    }
}

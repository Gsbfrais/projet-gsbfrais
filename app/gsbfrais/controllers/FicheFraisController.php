<?php
namespace Gsbfrais\Controllers;

use Gsbfrais\models\{FicheFraisManager, FraisForfaitManager, FraisHorsForfaitManager};

class FicheFraisController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function voirFicheFrais()
    {        
        $ficheFraisManager = new FicheFraisManager();
        $fraisForfaitManager = new FraisForfaitManager();
        $fraisHorsForfaitManager = new FraisHorsForfaitManager();

        $lesMois = $ficheFraisManager->getLesMoisDisponibles($_SESSION['idUtil']);
        if (count($lesMois) > 0) {
            $moisSelectionne = $lesMois[0]->mois; /* pré-sélection du mois le plus récent */
        } else {
            $moisSelectionne = $this->mois;
        }

        $lesFraisForfait = [];
        $lesFraisHorsForfait = [];
        $laFicheFrais = [];

        // Un mois a été sélectionné par l'utilisateur (le formulaire est posté)
        if (count($_POST) > 0) {
            $moisSelectionne = filter_input(INPUT_POST, 'lstMois', FILTER_VALIDATE_INT);
            if (empty($moisSelectionne) == true) {
                http_response_code(400);
                throw new \Exception("erreur d'accès à voirFicheFrais (FicheFraisController)");
            }
            $lesFraisForfait = $fraisForfaitManager->getLesFraisForfait($_SESSION['idUtil'], $moisSelectionne);
            $lesFraisHorsForfait = $fraisHorsForfaitManager->getLesFraisHorsForfait($_SESSION['idUtil'], $moisSelectionne);
            $laFicheFrais = $ficheFraisManager->getFicheFrais($_SESSION['idUtil'], $moisSelectionne);
        }

        $this->render('fichefrais/detailFicheFraisVM', [
            'title' => 'Mes fiches de frais',
            'periode' => date("m/Y"),
            'moisSelectionne' => $moisSelectionne,
            'lesMois' => $lesMois,
            'laFicheFrais' => $laFicheFrais,
            'lesFraisForfait' => $lesFraisForfait,
            'lesFraisHorsForfait' => $lesFraisHorsForfait
        ]);
    }

    public function voirFichesFraisNonRemboursees()
    {        
        $ficheFraisManager = new FicheFraisManager();

        $lesFichesFrais = $ficheFraisManager->getFichesFraisNonRemboursees($_SESSION['idUtil'], $this->mois);

        $this->render('fichefrais/listeFichesFraisNonRembourseesVM', [
            'title' => 'Mes fiches de frais non remboursées',
            'lesFichesFrais' => $lesFichesFrais,
        ]);
    }

    public function cloturerFichesFrais()
    {
        if ($_SESSION['profilUtil'] != 'comptable') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité cloturerFichesFrais (FicheFraisController) non autorisée');
        }

        $moisAcloturer = donneMoisPrecedent($this->mois);

        $infoMessage = '';
        if (count($_POST) > 0) {
            $ficheFraisManager = new FicheFraisManager();
            $lesFichesFrais = $ficheFraisManager->getLesFichesFraisACloturer($moisAcloturer);
            $nbFichesCloturees = 0;
            foreach($lesFichesFrais as $fiche) {
                $ficheFraisManager->clotureFicheFrais($fiche->id_visiteur, $fiche->mois);
                $nbFichesCloturees++;
            }
            $infoMessage = $nbFichesCloturees . " fiches de frais ont été clôturées";
            
        }
        
        $periode = substr($moisAcloturer, 4, 2) . "-" . substr($moisAcloturer, 0, 4);
        $this->render('fichefrais/clotureFichesFrais', [
            'title' => 'Clotûre fiches de frais',
            'periode' => $periode,
            'infoMessage' => $infoMessage
        ]);
    }

    public function voirFichesFraisEnAttenteValidation()
    {
        if ($_SESSION['profilUtil'] != 'comptable') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité voirFichesFraisEnAttenteValidation (FicheFraisController) non autorisée');
        }

        $ficheFraisManager = new FicheFraisManager();
        $lesMois = $ficheFraisManager->getLesMoisPourValidationFiches();
        $lesFichesFrais = [];
        $moisSelectionne = '';
        $periode = '';
        
        if (count($_POST) > 0){ 
            // On arrive de la vue ficheFrais/consulterEnAttenteValidation, après avoir sélectionné le mois 
            $moisSelectionne = filter_input(INPUT_POST, 'lstMois', FILTER_VALIDATE_INT);
        } elseif (isset($_GET['mois'])){ 
            // On arrive de la vue ficheFrais/valider après avoir validé une fiche ou cliqué sur le lien de retour
            $moisSelectionne = filter_input(INPUT_GET, 'mois', FILTER_VALIDATE_INT);
        }
        if ($moisSelectionne != '') {
            if (empty($moisSelectionne) == true) {
                http_response_code(400);
                throw new \Exception("erreur d'accès à voirFichesFraisEnAttenteValidation (FicheFraisController)");
            } else {
                $lesFichesFrais = $ficheFraisManager->getLesFichesFraisAValider($moisSelectionne);
                $periode = substr($moisSelectionne,4,2) . '-' . substr($moisSelectionne,0,4);
            }
        }
        
        $this->render('fichefrais/listeFichesFraisEnAttenteValidation', [
            'title' => 'Validation des fiches de frais',
            'errorMessage' => '',
            'moisSelectionne' => $moisSelectionne,
            'lesMois' => $lesMois,
            'periode' =>$periode,
            'lesFichesFrais' => $lesFichesFrais,
        ]); 
    }

    public function validerFicheFrais(int $idVisiteur, int $mois)
    {
        if ($_SESSION['profilUtil'] != 'comptable') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité validerFicheFrais (FicheFraisController) non autorisée');
        }
        
        $ficheFraisManager = new FicheFraisManager();
        $fraisForfaitManager = new FraisForfaitManager();
        $fraisHorsForfaitManager = new FraisHorsForfaitManager();
        
        $laFiche = $ficheFraisManager->getFicheFrais($idVisiteur, $mois);
        $lesFraisForfait = $fraisForfaitManager->getLesFraisForfait($idVisiteur, $mois);
        $lesFraisHorsForfait = $fraisHorsForfaitManager->getLesFraisHorsForfait($idVisiteur, $mois);

        $nbJustificatifs = '';
        $montant = '';
        $periode = substr($mois, 4, 2) . "/" . substr($mois, 0, 4);

        if (count($_POST) > 0) {
            if (isset($_POST['btnRetourListe'])) {
                header("Location: " . ROOT_URL . 'validerVoirLesFichesFrais&mois='.$mois);
            }
            /* Gestion des erreurs */
            $errorMessage = '';
            $montant = filter_input(INPUT_POST, 'montantValide', FILTER_VALIDATE_FLOAT);
            $nbJustificatifs = filter_input(INPUT_POST, 'nbJustificatifs', FILTER_VALIDATE_INT);
            if (empty($montant) == true) {
                $errorMessage .= "Le montant doit être renseigné et numérique<br>";
            }
            if ($nbJustificatifs === false or $nbJustificatifs < 0) {
                $errorMessage .= "Le nombre de justificatifs doit être un entier positif ou nul";
            }
            if (empty($errorMessage) == true)
            {
                $ficheFraisManager->validerFicheFrais($idVisiteur, $mois, $montant, $nbJustificatifs);
                header("Location: " . ROOT_URL . 'voirFichesFraisEnAttenteValidation&mois='.$mois);
                return;
            }
        }

        $this->render('fichefrais/validationFicheFrais', [
            'title' => 'Validation fiche de frais',
            'errorMessage' => '',
            'idVisiteur' => $idVisiteur,
            'mois' => $mois,
            'periode' => $periode,
            'laFiche' => $laFiche,
            'lesFraisForfait' => $lesFraisForfait,
            'lesFraisHorsForfait' => $lesFraisHorsForfait,
            'nbJustificatifs' => '',
            'montantValide' => '',
            'nbJustificatifs' => $nbJustificatifs,
            'montantValide' => $montant
        ]); 
    }

    function voirFichesFraisRegion()
    {
        if ($_SESSION['profilUtil'] != 'délégué régional') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité voirFichesFraisRegion (FicheFraisController) non autorisée');
        }

        $ficheFraisManager = new FicheFraisManager();
        $lesMois = $ficheFraisManager->getLesMoisDisponiblesRegion($_SESSION['idRegion']);
        $lesFichesFrais = [];
        $moisSelectionne = '';
        $periode = '';
        
        if (count($_POST) > 0){ 
            // On arrive de la vue ficheFrais/consulterEnAttenteValidation, après avoir sélectionné le mois 
            $moisSelectionne = filter_input(INPUT_POST, 'lstMois', FILTER_VALIDATE_INT);
        } elseif (isset($_GET['mois'])){ 
            // On arrive de la vue ficheFrais/valider après avoir validé une fiche ou cliqué sur le lien de retour
            $moisSelectionne = filter_input(INPUT_GET, 'mois', FILTER_VALIDATE_INT);
        }
        if ($moisSelectionne != '') {
            if (empty($moisSelectionne) == true) {
                http_response_code(400);
                throw new \Exception("erreur d'accès à voirFichesFraisRegion (FicheFraisController)");
            } else {
                $lesFichesFrais = $ficheFraisManager->getLesFichesFraisRegion($moisSelectionne, $_SESSION['idRegion']);
                $periode = substr($moisSelectionne,4,2) . '-' . substr($moisSelectionne,0,4);
            }
        }
        
        $this->render('fichefrais/listeFichesFraisRegion', [
            'title' => 'Validation des fiches de frais',
            'errorMessage' => '',
            'moisSelectionne' => $moisSelectionne,
            'lesMois' => $lesMois,
            'periode' =>$periode,
            'lesFichesFrais' => $lesFichesFrais,
        ]); 
    }

    public function voirDetailFicheFrais(int $idVisiteur, int $mois)
    {
        $ficheFraisManager = new FicheFraisManager();
        $fraisForfaitManager = new FraisForfaitManager();
        $fraisHorsForfaitManager = new FraisHorsForfaitManager();
        
        $laFiche = $ficheFraisManager->getFicheFrais($idVisiteur, $mois);
        $lesFraisForfait = $fraisForfaitManager->getLesFraisForfait($idVisiteur, $mois);
        $lesFraisHorsForfait = $fraisHorsForfaitManager->getLesFraisHorsForfait($idVisiteur, $mois);

        $periode = substr($mois, 4, 2) . "/" . substr($mois, 0, 4);

        $this->render('fichefrais/detailFicheFrais', [
            'title' => 'Détail fiche de frais',
            'idVisiteur' => $idVisiteur,
            'mois' => $mois,
            'periode' => $periode,
            'laFiche' => $laFiche,
            'lesFraisForfait' => $lesFraisForfait,
            'lesFraisHorsForfait' => $lesFraisHorsForfait,
        ]); 
    }
}

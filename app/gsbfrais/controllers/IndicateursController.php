<?php
namespace Gsbfrais\Controllers;

use Gsbfrais\models\{FicheFraisManager, FraisForfaitManager, FraisHorsForfaitManager};

class IndicateursController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function voirIndicateursVM():void
    {
       

            $ficheFraisManager = new FicheFraisManager();
            $fraisForfaitManager = new FraisForfaitManager();
            $fraisHorsForfaitManager = new FraisHorsForfaitManager();

            $lesMois = $ficheFraisManager->getLesMoisDisponibles($_SESSION['idUtil']);
            if (count($lesMois) > 0) {
                $moisSelectionne = $lesMois[0]->mois; 
            } else {
                $moisSelectionne = $this->mois;
            }

            $lesFraisForfait = [];
            $lesFraisHorsForfait = [];
            $laFicheFrais = [];

            // Un mois a été sélectionné par l'utilisateur 
            if (count($_POST) > 0) {
                $moisSelectionne = filter_input(INPUT_POST, 'lstMois', FILTER_VALIDATE_INT);
                if (empty($moisSelectionne) == true) {
                    http_response_code(400);
                    throw new \Exception("erreur d'accès ");
                }
                $lesFraisForfait = $fraisForfaitManager->getLesFraisForfait($_SESSION['idUtil'], $moisSelectionne);
                $lesFraisHorsForfait = $fraisHorsForfaitManager->getLesFraisHorsForfait($_SESSION['idUtil'], $moisSelectionne);
                $laFicheFrais = $ficheFraisManager->getFicheFrais($_SESSION['idUtil'], $moisSelectionne);
            }
            //  Calcul du total des frais forfaitisés et hors forfait pour l'année en cours
            $anneeEnCours = date("Y");
            $totalFraisForfait = 0;
            $totalFraisHorsForfait = 0;

            // Récupérer tous les mois de l'année en cours
            $moisAnneeEnCours = $ficheFraisManager->getLesMoisDisponibles($_SESSION['idUtil'], $anneeEnCours);

            // Calcul des frais forfaitisés
            foreach ($moisAnneeEnCours as $mois) {
                $fraisForfaitMois = $fraisForfaitManager->getLesFraisForfait($_SESSION['idUtil'], $mois->mois);
                foreach ($fraisForfaitMois as $frais) {
                    $totalFraisForfait += $frais->montant;
                }
                
                // Calcul des frais hors forfait
                $fraisHorsForfaitMois = $fraisHorsForfaitManager->getLesFraisHorsForfait($_SESSION['idUtil'], $mois->mois);
                foreach ($fraisHorsForfaitMois as $frais) {
                      
                    if ( == 'VA' ||  == 'RB') {
                        $totalFraisHorsForfait += $frais->montant;
                    }
                }
            }

            $totalFraisAnnuel = $totalFraisForfait + $totalFraisHorsForfait;

            // Indicateur : si l'année est clôturée 
            $anneeCloturee = (date("Y") > $anneeEnCours);

            $this->render('fichefrais/detailFicheFraisVM', [
                'title' => 'Mes fiches de frais',
                'periode' => date("m/Y"),
                'moisSelectionne' => $moisSelectionne,
                'lesMois' => $lesMois,
                'laFicheFrais' => $laFicheFrais,
                'lesFraisForfait' => $lesFraisForfait,
                'lesFraisHorsForfait' => $lesFraisHorsForfait,
                'totalFraisAnnuel' => $totalFraisAnnuel,
                'anneeCloturee' => $anneeCloturee
            ]);
            

    
        
    }

        
}   
       

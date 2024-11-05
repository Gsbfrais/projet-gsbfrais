<?php

namespace Gsbfrais\Controllers;

use Gsbfrais\models\{CategorieManager, FicheFraisManager, FraisForfaitManager, FraisHorsForfaitManager, IndicateursManager};

class IndicateursController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function voirIndicateursVM(): void
    {

       
    
        if ($_SESSION['profilUtil'] != 'visiteur médical' ) {
            http_response_code(403);
            throw new \Exception('Fonctionnalité saisirFraisForfait (FraisForfaitController) non autorisée');
        }
        $anneeCourante = substr($this-> mois,0,4);
        $debut = $anneeCourante.'01';
        $fin = $anneeCourante.'12';
        $indicateursManager = new IndicateursManager(); 
        $montantFraisVAouRB = $indicateursManager->getMontantTotalFraisVAouRB($debut,$fin,$_SESSION['idUtil']);
        
        $lesFraisForfaitises = new IndicateursManager();
        $montantFraisCRouCL = $indicateursManager ->getMontantTotalFraisCRouCL($debut,$fin,$_SESSION['idUtil']);

        $this->render('indicateurs/detailIndicateurs', [
            'title' => 'Montant total des frais',
            'montantFraisVAouRB' => $montantFraisVAouRB, 
            'montantFraisCRouCL' => $montantFraisCRouCL,
        ]);
      
    }
    
    


  
    
}

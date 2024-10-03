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

        $indicateursManager = new IndicateursManager(); 
        $lesFraisHorsForfait = $indicateursManager->getMontantTotalLesFraisHorsForfait($_SESSION['idUtil']);
        
        $lesFraisForfaities = new IndicateursManager();
        $lesFraisForfaities = $indicateursManager ->getMontantTotalFraisForfaities($_SESSION['idUtil']);

        $this->render('indicateurs/detailIndicateurs', [
            'title' => 'Montant total des frais',
            'lesFraisHorsForfait' => $lesFraisHorsForfait, 
            'lesFraisForfaities' => $lesFraisForfaities,
        ]);
    }
    
    


  
    
}

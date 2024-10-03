<?php
namespace Gsbfrais\models;

class IndicateursManager extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
 /**
     * Retourne tous les frais hors forfait d'un visiteur donné pour un mois donné
     * 
     * @param int $idVisiteur int Identifiant du visiteur médical
     * @param int $mois int mois sous la forme aaaamm
     * 
     * @return array tableau d'objets contenant les frais hors forfait répondant aux critères
     */
    public function  getMontantTotalLesFraisHorsForfait()
    {
        $sql = "SELECT SUM(montant) as montant_total FROM fraishorsforfait
         INNER JOIN fichefrais ON fraishorsforfait.id_visiteur = fichefrais.id_visiteur 
         INNER JOIN profil ON fraishorsforfait.id_visiteur = profil.id 
         WHERE (code_statut = 'VA' OR code_statut = 'RB') AND fraishorsforfait.mois LIKE '2024%' AND profil.id = '2'";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute();
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getMontantTotalLesFraisHorsForfait (IndicateursManager)');
        }
        return $stmt->fetch();
    }

    public function getMontantTotalFraisForfaities(){
        $sql = "SELECT SUM(montant) as montant_total FROM fraisforfait
        INNER JOIN fichefrais ON fraisforfait.id_visiteur = fichefrais.id_visiteur 
        INNER JOIN fraishorsforfait ON fraisforfait.id_visiteur = fraishorsforfait.id_visiteur
        INNER JOIN profil ON fraisforfait.id_visiteur = profil.id 
        WHERE (code_statut = 'VA' OR code_statut = 'RB') AND fraishorsforfait.mois LIKE '2024%' AND profil.id = '2'";
       $stmt = $this->db->prepare($sql);
       $ret = $stmt->execute();
       if ($ret == false) {
        http_response_code(500);
        throw new \Exception('Problème requête getMontantTotalLesFraisHorsForfait (IndicateursManager)');
    }
    return $stmt->fetch();
    }
}
    

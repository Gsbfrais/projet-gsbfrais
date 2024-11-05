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
    public function  getMontantTotalFraisVAouRB($date_debut, $date_fin,$idVisiteur):mixed
    {
        $sql = "SELECT SUM(montant_valide) as montant_total 
        FROM fichefrais
         WHERE (code_statut = 'VA' OR code_statut = 'RB') 
         AND mois Between :date_debut AND :date_fin
        AND id_visiteur = :id_visiteur";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute([
            ":date_debut" => $date_debut,
            ":date_fin" => $date_fin,
            ":id_visiteur"=>$idVisiteur
        ]
        );
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getMontantTotalLesFraisHorsForfait (IndicateursManager)');
        }
        $data= $stmt->fetch();
        return $data->montant_total;
    }

    public function getMontantTotalFraisCRouCL($date_debut, $date_fin,$idVisiteur):mixed
    {
        $sql = "SELECT SUM(quantite*prix_unitaire) as montant_total 
        FROM fraisforfait
        JOIN fichefrais ON fraisforfait.id_visiteur = fichefrais.id_visiteur 
        JOIN categoriefraisforfait ON code_categorie = code
        WHERE (code_statut = 'CL' OR code_statut = 'CR'  ) 
        AND fraisforfait.mois Between :date_debut AND :date_fin
        AND fraisforfait.id_visiteur = :id_visiteur"
        ;
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute([
            ":date_debut" => $date_debut,
            ":date_fin" => $date_fin,
            ":id_visiteur"=>$idVisiteur
        ]);
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getMontantTotalLesFraisHorsForfait (IndicateursManager)');
        }
        $data= $stmt->fetch();
        return $data->montant_total;
    }
}

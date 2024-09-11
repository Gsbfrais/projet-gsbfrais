<?php
namespace Gsbfrais\models;

class FraisHorsForfaitManager extends Model
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
    public function getLesFraisHorsForfait(int $idVisiteur, int $mois):array
    {
        $sql = "select num, id_visiteur, libelle, DATE_FORMAT(date, '%d/%m/%Y') as date, montant 
        from fraishorsforfait 
        where fraishorsforfait.id_visiteur = :id_visiteur and fraishorsforfait.mois = :mois";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_visiteur' => $idVisiteur,
            ':mois' => $mois
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getLesFraisHorsForfait (FraisHorsForfaitManager)');
        }
        return $stmt->fetchAll();
    }
    
    /**
     * Retourne le frais hors forfait correspondant aux critères passés en paramètre
     * 
     * @param int $idVisiteur id du visiteur
     * @param int $mois mois du frais hors forfait
     * @param int $num numéro d'odre du frais hors forfait pour le visiteur et le mois considérés
     * 
     * @return mixed le frais hors forfait sous la forme d'un objet ou false si n'existe pas
     */
    public function getFraisHorsForfait(int $idVisiteur, int $mois, int $num):mixed 
    {
        $sql = "select * from fraishorsforfait where id_visiteur = :id_visiteur and mois = :mois and num = :num";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_visiteur' => $idVisiteur,
            ':mois' => $mois,
            ':num' => $num
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getFraisHorsForfait (FraisHorsForfaitManager)');
        }
        return $stmt->fetch();
    }

    /**
     * Ajoute un nouveau frais hors forfait pour un visiteur et un mois donné
     * à partir des informations fournies en paramètre
     *
     * @param int $idVisiteur identifiant du visiteur médical
     * @param int $mois Mois sous la forme aaaamm
     * @param string $libelle le libelle du frais
     * @param string $date la date du frais au format aaaa-mm-jj
     * @param float $montant le montant
     * 
     * @return void
     */
    public function ajouteFraisHorsForfait(int $idVisiteur, int $mois, string $libelle, string $date, float $montant):void
    {
        $sql = "insert into fraishorsforfait (id_visiteur, mois, libelle, date, montant)
                values(:id_visiteur, :mois, :libelle, '$date', :montant)";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            'id_visiteur' => $idVisiteur,
            'mois' => $mois,
            'libelle' => $libelle,
            'montant' => $montant
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête ajouteFraisHorsForfait (FraisHorsForfaitManager)');
        }
    }

    /**
     * Supprime un frais hors forfait
     *
     * @param int $idVisiteur id du visiteur concerné
     * @param int $mois concerné
     * @param int $num numéro relatif du frais pour le visiteur et le mois considérés
     * 
     * @return void
     */
    public function supprimeFraisHorsForfait(int $idVisiteur, int $mois, int $num):void
    {
        $sql = "delete from fraishorsforfait 
                where id_visiteur =:id_visiteur 
                and mois = :mois 
                and num = :num";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_visiteur' => $idVisiteur,
            ':mois' => $mois,
            ':num' => $num
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête supprimeFraisHorsForfait (FraisHorsForfaitManager)');
        }
    }
}
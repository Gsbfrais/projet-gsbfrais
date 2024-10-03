<?php
namespace Gsbfrais\models;

class FraisForfaitManager extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Retourne tous les frais forfaitisés d'un visiteur donné pour un mois donné
     * @param int $idVisiteur int identifiant du visiteur médical
     * @param int $mois int mois sous la forme aaaamm
     * 
     * @return array tableau d'objets contenant les frais forfaitisés répondant aux critères
     */
    public function getLesFraisForfait(int $idVisiteur, int $mois):array
    {
        $sql = "select code_categorie, categoriefraisforfait.libelle, fraisforfait.quantite 
                from fraisforfait 
                join categoriefraisforfait on categoriefraisforfait.code = fraisforfait.code_categorie
                where fraisforfait.id_visiteur =:id_visiteur and fraisforfait.mois=:mois
                order by fraisforfait.code_categorie";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_visiteur' => $idVisiteur,
            ':mois' => $mois
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getLesFraisForfait (FraisForfaitManager)');
        }
        return $stmt->fetchAll();
    }
   



    /**
     * Retourne tous les frais forfaitisés des visiteurs d'une région pour un mois donnés
     * @param int $mois int mois sous la forme aaaamm
     * @param int $idRegion int identifiant de la région
     * 
     * @return array tableau d'objets contenant les frais forfaitisés répondant aux critères
     */
    public function getLesFraisForfaitRegion(int $mois, int $idRegion):array
    {
        $sql = "select code_categorie, categoriefraisforfait.libelle, fraisforfait.quantite 
                from fraisforfait 
                join categoriefraisforfait on categoriefraisforfait.code = fraisforfait.code_categorie
                join utilisateur on utilisateur.id = fraisforfait.id_visiteur
                where utilisateur.id_region =:id_region and fraisforfait.mois=:mois
                order by fraisforfait.code_categorie";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_region' => $idRegion,
            ':mois' => $mois
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getLesFraisForfaitRegion (FraisForfaitManager)');
        }
        return $stmt->fetchAll();
    }

    /**
     * Retourne le frais forfaitisé d'un visiteur, d'un mois et d'une categorie données
     * @param int $idVisiteur int identifiant du visiteur médical
     * @param int $mois int mois sous la forme aaaamm
     * @param int $codeCategorie int mois sous la forme aaaamm
     * 
     * @return mixed le frais forfaitisé sous la forme d'un objet ou false si n'existe pas
     */
    public function getFraisForfaitByKey(int $idVisiteur, int $mois, string $codeCategorie):mixed
    {
        $sql = "select id_visiteur, code_categorie, categoriefraisforfait.libelle, fraisforfait.quantite 
                from fraisforfait 
                join categoriefraisforfait on categoriefraisforfait.code = fraisforfait.code_categorie
                where fraisforfait.id_visiteur =:id_visiteur and fraisforfait.mois=:mois and code_categorie = :code_categorie";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_visiteur' => $idVisiteur,
            ':mois' => $mois,
            ':code_categorie' => $codeCategorie,
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getFraisForfaitByKey (FraisForfaitManager)');
        }
        return $stmt->fetch();
    }

    /**
     * Ajoute un frais forfaitisé
     * 
     * @param int $idVisiteur identifiant du visiteur médical concerné
     * @param int $mois int sous la forme aaaamm période concernée
     * @param int $codeCategorie code catégori du frais forfaitisé
     * @param int $quantite quantite de frais forfaitisé
     * 
     * @return void
     */
    public function ajouteFraisForfait(int $idVisiteur, int $mois, string $codeCategorie, int $quantite) {
        $sql = "insert into fraisforfait(id_visiteur, mois, code_categorie, quantite) 
        values(:id_visiteur, :mois, :code_categorie, :quantite)";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_visiteur' => $idVisiteur,
            ':mois' => $mois,
            ':code_categorie' => $codeCategorie,
            ':quantite' => $quantite
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête ajouteFraisForfait (FraisForfaitManager)');
        }
    }

    /**
     * Retourne la liste des catégories de frais forfaitisés non encore utilisées
     * pour un visiteur et un mois donnés 
     * 
     * @param int $idVisiteur identifiant du visiteur médical concerné
     * @param int $mois int sous la forme aaaamm période concernée
     * *
     * @return array tableau d'objets contenant les types de frais forfait
     */
    public function getLesCategoriesDisponiblesPourFicheFrais(int $idVisiteur, int $mois):array
    {
        $sql = "select code, libelle 
                from categoriefraisforfait
                where code not in (
                    select code_categorie
                    from fraisforfait  
                    where fraisforfait.code_categorie = code 
                    and fraisforfait.id_visiteur =:id_visiteur 
                    and fraisforfait.mois=:mois
                )";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute([
            ':id_visiteur' => $idVisiteur,
            ':mois' => $mois,
        ]);
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getLesCategoriesDisponibles (FraisForfaitManager)');
        }
        return $stmt->fetchAll();
    }

    /**
     * Supprime un frais forfait
     *
     * @param int $idVisiteur id du visiteur concerné
     * @param int $mois concerné
     *@param int $quantite quantite de frais forfaitisé
     * 
     * @return void
     */
    public function supprimeFraisForfait(int $idVisiteur, int $mois, string $codeCategorie):void
    {
        $sql = "delete from fraisforfait 
                where id_visiteur =:id_visiteur 
                and mois = :mois 
                and code_categorie = :code_categorie";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_visiteur' => $idVisiteur,
            ':mois' => $mois,
            ':code_categorie' => $codeCategorie
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête supprimeFraisForfait (FraisHorsForfaitManager)');
        }
    }
    
    /**
     * Modifie un frais forfaitisé
     * 
     * @param int $idVisiteur identifiant du visiteur médical concerné
     * @param int $mois int sous la forme aaaamm période concernée
     * @param int $codeTypeFrais code du type de frais
     * @param int $quantite nouvelle quantite
     * 
     * @return void
     */
    public function modifieFraisForfait(int $idVisiteur, int $mois, string $codeTypeFrais, int $quantite):void
    {
        $sql = "update fraisforfait set fraisforfait.quantite = $quantite
        where fraisforfait.id_visiteur = :id_visiteur 
        and fraisforfait.mois = :mois
        and fraisforfait.code_categorie = '$codeTypeFrais'";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_visiteur' => $idVisiteur,
            ':mois' => $mois
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête modifieFraisForfait (FraisForfaitManager)');
        }
    }
    
}

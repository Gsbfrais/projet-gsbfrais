<?php
namespace Gsbfrais\models;

class CategorieManager extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Retourne la liste des types de frais forfaitisés
     * 
     * @return array tableau d'objets contenant les types de frais forfait
     */
    public function getLesCategoriesFraisForfait():array
    {
        $sql = "select code, libelle 
                from categoriefraisforfait 
                order by code";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute();
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getLesCategoriesFraisForfait (CategorieManager)');
        }
        return $stmt->fetchAll();
    }
}

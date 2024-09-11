<?php
namespace Gsbfrais\models;

abstract class Model
{
    protected $db;

    /**
     * Crée une instance de connexion à la base de données
     */
    public function __construct() {
        $this->db = MonPDO::getInstance()->getConnexion();
    }
   
}

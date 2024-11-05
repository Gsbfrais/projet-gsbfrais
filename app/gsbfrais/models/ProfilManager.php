<?php

namespace Gsbfrais\models;

class ProfilManager extends Model
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Retoune la liste des profils
     * @return mixed
     * @throws \Exception
     */
    public function getProfils(): mixed
    {
        if ($_SESSION['profilUtil'] != 'administrateur') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité getProfils (ProfilManager) non autorisée');
        }

        $profil = false;
        $sql = "select * from profil";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute();
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getProfils (ProfilManager)');
        }
        $profils = $stmt->fetchAll();
        return $profils;
    }
}
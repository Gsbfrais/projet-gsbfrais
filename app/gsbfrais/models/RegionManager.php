<?php

namespace Gsbfrais\models;

class RegionManager extends Model
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
    public function getRegions(): mixed
    {
        if ($_SESSION['profilUtil'] != 'administrateur') {
            http_response_code(403);
            throw new \Exception('Fonctionnalité getRegions (RegionManager) non autorisée');
        }

        $regions = false;
        $sql = "select * from region";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute();
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getRegions (RegionManager)');
        }
        $regions = $stmt->fetchAll();
        return $regions;
    }
}
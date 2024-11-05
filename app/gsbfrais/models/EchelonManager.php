<?php

namespace Gsbfrais\models;

class EchelonManager extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getLesEchelons(): array
    {
        $sql = "SELECT * FROM echelon";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute();
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getLesEchelons (EchelonManager)');
        }
        return $stmt->fetchAll();
    }


    public function getEchelon(int $idEchelon): mixed
    {
        $sql = "SELECT * FROM echelon WHERE id = :id_echelon";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_echelon' => idEchelon,
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getEchelon (EchelonManager)');
        }
        return $stmt->fetch();
    }
}
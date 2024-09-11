<?php
namespace Gsbfrais\models;

/**
 * Classe qui établit une connexion à la base de données et limite le nombre d’instances à une seule.
 * Denign pattern singleton
 */
final class MonPDO
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,        // mode de gestion des erreurs
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,     // mode de récupération des données
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'    // encodage
        ];

        try {
            $this->pdo = new \PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PWD, $options);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
        } catch (\PDOException $e) {
            http_response_code(500);
            throw new \Exception( "Problème d'accès à la base de données");
        }
    }

    /**
     * Crée une unique instance PDO
     */
    final public static function getInstance()
    {
        if (self::$instance instanceof self == false) {
            self::$instance = new MonPDO();
        }
        return self::$instance;
    }

    public function getConnexion() {
		return $this->pdo;
	}
}

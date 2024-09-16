<?php
namespace Gsbfrais\models;

class UtilisateurManager extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Retourne les informations d'un utilisateur
     * 
     * @param string $login login de l'utilisateur pour lequel on souhaite les informations
     * 
     * @return mixed les informations du visiteur ou false si l'utilisateur n'existe pas
     */
    public function getUtilisateur(string $login): mixed
    {
        $utilisateur = false;

        $sql = "select utilisateur.id, utilisateur.nom, utilisateur.prenom, utilisateur.login, utilisateur.mot_passe, 
                profil.nom as nom_profil, utilisateur.id_region,utilisateur.date_embauche
                from utilisateur
                join profil on profil.id = id_profil
                where login=:login";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':login' => $login,
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getUtilisateur (UtilisateurManager)');
        }
        $utilisateur =  $stmt->fetch();
        return $utilisateur;
    }
}
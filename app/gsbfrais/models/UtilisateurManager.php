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
                    profil.nom as nom_profil, utilisateur.id_region,utilisateur.date_embauche, plafondetp 
                from utilisateur 
                join profil on profil.id = id_profil 
                join niveauexpertise on niveauexpertise.id = utilisateur.id_niveauexpertise
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
     /**
     * Retourne les informations d'un utilisateur par son identifiant
     * 
     * @param int $idUtil L'identifiant de l'utilisateur
     * @return mixed Renvoie un objet utilisateur ou false s'il n'existe pas
     */
    public function getUtilisateurById(int $idUtil): mixed
    {
        $sql = "SELECT utilisateur.id, utilisateur.nom, utilisateur.prenom, utilisateur.login, utilisateur.mot_passe, 
                       utilisateur.id_region, utilisateur.date_embauche, utilisateur.id_niveauexpertise
                FROM utilisateur
                WHERE utilisateur.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $idUtil]);
        return $stmt->fetch(); // Renvoie un objet utilisateur ou false
    }

    /**
     * Met à jour le mot de passe de l'utilisateur
     * 
     * @param int $idUtil L'identifiant de l'utilisateur
     * @param string $hashedPassword Le nouveau mot de passe haché
     * @return void
     */
    public function updatePassword(int $idUtil, string $hashedPassword): void
    {
        $sql = "UPDATE utilisateur SET mot_passe = :mot_passe WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':mot_passe' => $hashedPassword,
            ':id' => $idUtil
        ]);
    }
}
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
        $utilisateur = $stmt->fetch();
        return $utilisateur;
    }

    /**
     * Retoune la liste des utilisateurs
     * @return mixed
     * @throws \Exception
     */
    public function getUtilisateurs(): mixed
    {
        $utilisateurs = false;
        $sql = "select * from utilisateur";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute();
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getUtilisateurs (UtilisateurManager)');
        }
        $utilisateurs = $stmt->fetchAll();
        return $utilisateurs;
    }


    /**
     * Supprimer un utilisateur
     * @param int $idUtilisateur
     * @return void
     * @throws \Exception
     */
    public function supprimerUtilisateur(int $idUtilisateur): void
    {
        $sql = "delete from utilisateur where id=:id_utilisateur";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_utilisateur' => $idUtilisateur,
        ));
        if ($ret == false) {
            $errorInfo = $stmt->errorInfo();
            http_response_code(500);
            throw new \Exception('Problème requête supprimerUtilisateur (UtilisateurManager)' . implode(' ', $errorInfo));
        }
    }


    /**
     * @param int $idUtilisateur
     * @param string $nom
     * @param string $prenom
     * @param string $login
     * @param string $date_embauche
     * @param string $date_depart
     * @param int $id_region
     * @param int $id_profil
     * @return void
     * @throws \Exception
     */
    public function modifierUtilisateur(int $idUtilisateur, string $nom, string $prenom, string $login, string $date_embauche, string $date_depart, int $id_region, int $id_profil): void
    {
        $sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, login = :login, date_embauche = :date_embauche, date_depart = :date_depart, id_region = :id_region, id_profil = :id_profil WHERE id = :id_utilisateur";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':login' => $login,
            ':date_embauche' => $date_embauche,
            ':date_depart' => $date_depart,
            ':id_region' => $id_region,
            ':id_profil' => $id_profil,
            ':id_utilisateur' => $idUtilisateur,
        ));
        if ($ret == false) {
            $errorInfo = $stmt->errorInfo();
            http_response_code(500);
            throw new \Exception('Problème requête modifierUtilisateur (UtilisateurManager)' . implode(' ', $errorInfo));
        }
    }

    /**
     * Retourne les informations d'un utilisateur
     *
     * @param string $login login de l'utilisateur pour lequel on souhaite les informations
     *
     * @return mixed les informations du visiteur ou false si l'utilisateur n'existe pas
     */
    public function getUtilisateurById(string $id_utilisateur): mixed
    {
        $utilisateur = false;

        $sql = "select * from utilisateur where id=:id_utilisateur";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':id_utilisateur' => $id_utilisateur,
        ));
        if ($ret == false) {
            http_response_code(500);
            throw new \Exception('Problème requête getUtilisateurById (UtilisateurManager)');
        }
        $utilisateur = $stmt->fetch();
        return $utilisateur;
    }

    /**
     * @param string $nom
     * @param string $prenom
     * @param string $login
     * @param string $date_embauche
     * @param string $date_depart
     * @param int $id_region
     * @param int $id_profil
     * @return void
     * @throws \Exception
     */
    public function ajouterUtilisateur(string $nom, string $prenom, string $login, string $date_embauche, ?string $date_depart, int $id_region, int $id_profil): void
    {
        $DEFAULT_PASSWORD = '$argon2i$v=19$m=65536,t=4,p=1$ZU5aMzdBL1pKdFhHbmF3UQ$dFT0y9yrt0tFJx0A5JbJbJlRa8ESSw06L4Cq3vGGEWI';
        $sql = "INSERT INTO utilisateur VALUES (null, :nom, :prenom, :login, :password, :date_embauche, :date_depart, :id_region, :id_profil)";
        $stmt = $this->db->prepare($sql);
        $ret = $stmt->execute(array(
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':login' => $login,
            ':password' => $DEFAULT_PASSWORD,
            ':date_embauche' => $date_embauche,
            ':date_depart' => $date_depart,
            ':id_region' => $id_region,
            ':id_profil' => $id_profil,
        ));
        if ($ret == false) {
            $errorInfo = $stmt->errorInfo();
            http_response_code(500);
            throw new \Exception('Problème requête ajouterUtilisateur (UtilisateurManager)' . implode(' ', $errorInfo));
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
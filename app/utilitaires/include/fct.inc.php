<?php

function getLesVisiteurs($pdo)
{
    $req       = "select utilisateur.* from utilisateur join profil on id_profil = profil.id and profil.nom = 'visiteur médical'";
    $res       = $pdo->query($req);
    $lesLignes = $res->fetchAll();
    return $lesLignes;
}

function getLesFichesFrais($pdo)
{
    $req       = "select * from ficheFrais";
    $res       = $pdo->query($req);
    $lesLignes = $res->fetchAll();
    return $lesLignes;
}

function getLesCategoriesFraisForfait($pdo)
{
    $req       = "select code as code, libelle from categoriefraisforfait order by code";
    $res       = $pdo->query($req);
    $lesLignes = $res->fetchAll();
    return $lesLignes;
}

function getDernierMois($pdo, $idVisiteur)
{
    $req     = "select max(mois) as dernierMois from fichefrais where id_visiteur = '$idVisiteur'";
    $res     = $pdo->query($req);
    $laLigne = $res->fetch();
    return $laLigne['dernierMois'];
}

function getMoisSuivant($mois)
{
    $numAnnee = substr($mois, 0, 4);
    $numMois  = substr($mois, 4, 2);
    if ($numMois == "12") {
        $numMois = "01";
        $numAnnee++;
    } else {
        $numMois++;
    }
    if (strlen($numMois) == 1)
        $numMois = "0" . $numMois;
    return $numAnnee . $numMois;
}

function getMoisPrecedent($mois)
{
    $numAnnee = substr($mois, 0, 4);
    $numMois  = substr($mois, 4, 2);
    if ($numMois == "01") {
        $numMois = "12";
        $numAnnee--;
    } else {
        $numMois--;
    }
    if (strlen($numMois) == 1)
        $numMois = "0" . $numMois;
    return $numAnnee . $numMois;
}

function suppressionFichesFrais($pdo) {
    $req = "delete from fraishorsforfait; delete from fraisforfait; delete from fichefrais;";
    $pdo->exec($req);
}

function creationFichesFrais($pdo, $moisDebut)
{
    $lesVisiteurs = getLesVisiteurs($pdo);
    $moisActuel   = getMois(date("d/m/Y"));
    //$moisFin      = getMoisPrecedent($moisActuel);
    $moisFin = $moisActuel;
    $cpt          = 0;
    foreach ($lesVisiteurs as $unVisiteur) {
        $moisCourant = $moisFin;
        $idVisiteur  = $unVisiteur['id'];
        $n           = 1;
        while ($moisCourant >= $moisDebut) {
            if ($n == 1) {
                $codeStatus      = "CR";
                $moisModif = $moisCourant;
            }
            if ($n == 2) {
                $codeStatus      = "CR";
                $moisModif = getMoisSuivant($moisCourant);
            }
            if ($n == 3) {
                $codeStatus      = "VA";
                $moisModif = getMoisSuivant(getMoisSuivant($moisCourant));
            }
            if ($n == 4) {
                $codeStatus      = "RB";
                $moisModif = getMoisSuivant(getMoisSuivant(getMoisSuivant($moisCourant)));
            }
            $numAnnee        = substr($moisModif, 0, 4);
            $numMois         = substr($moisModif, 4, 2);
            $dateModif       = $numAnnee . "-" . $numMois . "-" . rand(1, 8);
            $nbJustificatifs = rand(0, 12);
            $req             = "insert into fichefrais(id_visiteur,mois,nb_justificatifs,montant_valide,date_modif,code_statut)
            values ('$idVisiteur','$moisCourant',$nbJustificatifs,0,'$dateModif','$codeStatus');";
            $creer = random_int(0,1);
            //if ($moisCourant != $moisActuel ||  $idVisiteur % 3 != 0) {
            if ($moisCourant != $moisActuel ||  $creer == 1 || $idVisiteur == 1) {
                $pdo->exec($req);
                $cpt++;
            }
            $moisCourant     = getMoisPrecedent($moisCourant);
            $n++;
        }
    }
    return $cpt;
}

function creationFraisForfait($pdo)
{
    $lesFichesFrais    = getLesFichesFrais($pdo);
    $lesCategoriesFraisForfait = getLesCategoriesFraisForfait($pdo);
    $cpt = 0;
    foreach ($lesFichesFrais as $uneFicheFrais) {
        $idVisiteur = $uneFicheFrais['id_visiteur'];
        $mois       = $uneFicheFrais['mois'];
        foreach ($lesCategoriesFraisForfait as $categorie) {
            $creer = random_int(0, 1);
            if ($creer == 1) {
                $codeCategorie = $categorie['code'];
                if ($codeCategorie == "KM") {
                    $quantite = rand(300, 1000);
                } else {
                    $quantite = rand(2, 8);
                }
                $req = "insert into fraisforfait(id_visiteur,mois,code_categorie,quantite)
                values('$idVisiteur','$mois','$codeCategorie',$quantite);";
                $cpt++;
                $pdo->exec($req);
            }
        }
    }
    return $cpt;
}

function getDesFraisHorsForfait()
{
    $tab = array(
        1  => array(
            "lib" => "Repas avec praticien",
            "min" => 30,
            "max" => 50
        ),
        2  => array(
            "lib" => "Achat de matériel de papèterie",
            "min" => 10,
            "max" => 50
        ),
        3     => array(
            "lib" => "Taxi",
            "min" => 20,
            "max" => 80
        ),
        4  => array(
            "lib" => "Achat d'espace publicitaire",
            "min" => 20,
            "max" => 150
        ),
        5  => array(
            "lib" => "Location salle conférence",
            "min" => 120,
            "max" => 650
        ),
        6  => array(
            "lib" => "Voyage SNCF",
            "min" => 30,
            "max" => 150
        ),
        7  => array(
            "lib" => "Traiteur, alimentation, boisson",
            "min" => 25,
            "max" => 450
        ),
        8  => array(
            "lib" => "Rémunération intervenant/spécialiste",
            "min" => 250,
            "max" => 1200
        ),
        9  => array(
            "lib" => "Location équipement vidéo/sonore",
            "min" => 100,
            "max" => 850
        ),
        10 => array(
            "lib" => "Location véhicule",
            "min" => 25,
            "max" => 450
        ),
        11 => array(
            "lib" => "Frais vestimentaire/représentation",
            "min" => 25,
            "max" => 450
        )
    );
    return $tab;
}

function updateMdpVisiteur($pdo, $mdp)
{
    $hash = password_hash($mdp, PASSWORD_ARGON2I);
    $req = "update utilisateur set mot_passe ='$hash'";
    $nb = $pdo->exec($req);
    return $nb;
}

function creationFraisHorsForfait($pdo)
{
    $desFrais       = getDesFraisHorsForfait();
    $lesFichesFrais = getLesFichesFrais($pdo);
    $cpt = 0;
    foreach ($lesFichesFrais as $uneFicheFrais) {
        $idVisiteur = $uneFicheFrais['id_visiteur'];
        $mois       = $uneFicheFrais['mois'];
        $nbFrais    = rand(0, 5);
        for ($i = 0; $i <= $nbFrais; $i++) {
            $hasardNumfrais = rand(1, count($desFrais));
            $frais          = $desFrais[$hasardNumfrais];
            $lib            = addslashes($frais['lib']);
            $min            = $frais['min'];
            $max            = $frais['max'];
            $hasardMontant  = rand($min, $max);
            $numAnnee       = substr($mois, 0, 4);
            $numMois        = substr($mois, 4, 2);
            $hasardJour     = rand(1, 28);
            if (strlen($hasardJour) == 1) {
                $hasardJour = "0" . $hasardJour;
            }
            $hasardMois = $numAnnee . "-" . $numMois . "-" . $hasardJour;
            $req = "insert into fraishorsforfait(id_visiteur,mois,libelle,date,montant) values('$idVisiteur','$mois','$lib','$hasardMois',$hasardMontant);";
            $cpt++;
            try {
                $pdo->exec($req);
            } catch (Exception $e) {
                echo "erreur requête : " . $req;
            }
        }
    }
    return $cpt;
}

function getMois($date)
{
    @list($jour, $mois, $annee) = explode('/', $date);
    if (strlen($mois) == 1) {
        $mois = "0" . $mois;
    }
    return $annee . $mois;
}

function majFicheFrais($pdo)
{
    $cpt = 0;
    $lesFichesFrais = getLesFichesFrais($pdo);
    foreach ($lesFichesFrais as $uneFicheFrais) {
        $idVisiteur              = $uneFicheFrais['id_visiteur'];
        $mois                    = $uneFicheFrais['mois'];
        $dernierMois             = getDernierMois($pdo, $idVisiteur);
        $req                     = "select sum(montant) as cumul 
                                    from FraisHorsForfait 
                                    where FraisHorsForfait.id_visiteur = '$idVisiteur'
                                    and FraisHorsForfait.mois = '$mois'";
        $res                     = $pdo->query($req);
        $ligne                   = $res->fetch();
        $cumulMontantHorsForfait = $ligne['cumul'];

        $req                     = "select sum(FraisForfait.quantite * categoriefraisforfait.prix_unitaire) as cumul 
                                    from FraisForfait, categoriefraisforfait 
                                    where FraisForfait.code_categorie = categoriefraisforfait.code 
                                    and FraisForfait.id_visiteur = '$idVisiteur' 
                                    and FraisForfait.mois = '$mois'";
        $res                     = $pdo->query($req);
        $ligne                   = $res->fetch();
        $cumulMontantForfait     = $ligne['cumul'];

        $montantEngage           = $cumulMontantHorsForfait + $cumulMontantForfait;
        $codeStatut                    = $uneFicheFrais['code_statut'];
        if ($codeStatut == "CR" or $codeStatut == "CL")
            $montantValide = 0;
        else
            $montantValide = $montantEngage * rand(80, 100) / 100;
        if ($codeStatut == "CR") {
            $req = "update FicheFrais set montant_valide =$montantValide, nb_justificatifs = 0  
            where id_visiteur = '$idVisiteur' and mois='$mois'";
        }
        else {
            $req = "update FicheFrais set montant_valide =$montantValide 
            where id_visiteur = '$idVisiteur' and mois='$mois'";
        }

        $cpt++;
        $pdo->exec($req);
    }
    return $cpt;
}

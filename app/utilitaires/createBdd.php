<?php
echo "Programme de création (après suppression éventuelle) de la base de données gsbfrais
cette mise à jour peut prendre plusieurs minutes...<br/><br/>\n";
flush();

//============== PARAMETRES A MODIFIER ==============
/* Paramètres de connexion à la BDD */
$SERVEUR   = 'mysql:host=database:3306';
$MDPROOT    = 'sio2025@rostand';
$BDD       = 'gsbfrais';
$USER      = 'USR_APPLI_GSBFRAIS';
$MDP       = '@p05plGF!!ytgr47';


try {
    //*************************************************************************** */
    set_time_limit(0);
    echo "Création de la base de données gsbfrais, après suppression éventuelle<br/><br/>\n";
    flush();

    // Connexion à MySQL sans spécifier de base de données
    $pdo = new PDO($SERVEUR, 'root', $MDPROOT );
    //$pdo = new PDO('mysql:host=localhost', 'root', $MDPROOT );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Suppression de la base de données si elle existe
    $pdo->exec("DROP DATABASE IF EXISTS " . $BDD);

    // Création de la base de données
    $pdo->exec("CREATE DATABASE " . $BDD);

    //*************************************************************************** */
    set_time_limit(2);
    echo "Création des tables<br/><br/>\n";
    flush();
    // Sélection de la base de données
    $pdo->exec("USE " . $BDD);

    // Création des tables
    $createTables = "
        CREATE TABLE `profil` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `nom` varchar(40) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        CREATE TABLE `region` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `nom` varchar(40) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        CREATE TABLE `statutfichefrais` (
            `code` char(2) NOT NULL PRIMARY KEY,
            `libelle` varchar(30) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        CREATE TABLE `categoriefraisforfait` (
            `code` char(3) NOT NULL PRIMARY KEY,
            `libelle` char(20) DEFAULT NULL,
            `prix_unitaire` decimal(5,2) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        CREATE TABLE `utilisateur` (
            `id` int NOT NULL AUTO_INCREMENT,
            `nom` char(30) DEFAULT NULL,
            `prenom` char(30) DEFAULT NULL,
            `login` char(20) DEFAULT NULL UNIQUE,
            `mot_passe` varchar(250) DEFAULT NULL,
            `date_embauche` date DEFAULT NULL,
            `date_depart` date DEFAULT NULL,
            `id_region` int NOT NULL,
            `id_profil` int NOT NULL,
            PRIMARY KEY(`id`),
            FOREIGN KEY(`id_region`) REFERENCES `region`(`id`),
            FOREIGN KEY(`id_profil`) REFERENCES `profil`(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE `fichefrais` (
            `id_visiteur` int NOT NULL,
            `mois` char(6) NOT NULL,
            `nb_justificatifs` int DEFAULT NULL,
            `montant_valide` decimal(10,2) DEFAULT NULL,
            `date_modif` date DEFAULT NULL,
            `code_statut` char(2) DEFAULT 'CR',
            PRIMARY KEY(`id_visiteur`, `mois`),
            FOREIGN KEY(`id_visiteur`) REFERENCES `utilisateur`(`id`),
            FOREIGN KEY(`code_statut`) REFERENCES `statutfichefrais`(`code`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        CREATE TABLE `fraisforfait` (
            `id_visiteur` int NOT NULL,
            `mois` char(6) NOT NULL,
            `code_categorie` char(3) NOT NULL,
            `quantite` int DEFAULT NULL,
            PRIMARY KEY(`id_visiteur`, `mois`, `code_categorie`),
            FOREIGN KEY(`id_visiteur`, `mois`) REFERENCES `fichefrais`(`id_visiteur`, `mois`),
            FOREIGN KEY(`code_categorie`) REFERENCES `categoriefraisforfait`(`code`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        CREATE TABLE `fraishorsforfait` (
            `id_visiteur` int NOT NULL,
            `mois` char(6) NOT NULL,
            `num` int NOT NULL,
            `libelle` varchar(100) DEFAULT NULL,
            `date` date DEFAULT NULL,
            `montant` decimal(10,2) DEFAULT NULL,
            PRIMARY KEY(`id_visiteur`, `mois`, `num`),
            FOREIGN KEY(`id_visiteur`, `mois`) REFERENCES `fichefrais`(`id_visiteur`, `mois`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ";

    $pdo->exec($createTables);

    //*************************************************************************** */
    set_time_limit(2);
    echo "Insertion des données dans les tables statiques<br/><br/>\n";
    flush();
    $insertData = "
        INSERT INTO `statutfichefrais` (`code`, `libelle`) VALUES
        ('CL', 'Saisie clôturée'),
        ('CR', 'Fiche créée, saisie en cours'),
        ('RB', 'Remboursée'),
        ('VA', 'Validée et mise en paiement');

        INSERT INTO `categoriefraisforfait` (`code`, `libelle`, `prix_unitaire`) VALUES
        ('ETP', 'Forfait Etape', '110.00'),
        ('KM', 'Frais Kilométrique', '0.62'),
        ('NUI', 'Nuitée Hôtel', '80.00'),
        ('REP', 'Repas Restaurant', '25.00');

        INSERT INTO `region` (`id`, `nom`) VALUES
        (1, 'Auvergne-Rhône-Alpes'),
        (2, 'Bourgogne-Franche-Comté'),
        (3, 'Bretagne'),
        (4, 'Centre-Val de Loire'),
        (5, 'Corse'),
        (6, 'Grand Est'),
        (7, 'Hauts-de-France'),
        (8, 'Ile-de-France'),
        (9, 'Normandie'),
        (10, 'Nouvelle-Aquitaine'),
        (11, 'Occitanie'),
        (12, 'Pays de la Loire'),
        (13, 'Provence-Alpes-Côte d''Azur'),
        (14, 'Guadeloupe'),
        (15, 'Guyane'),
        (16, 'Martinique'),
        (17, 'La Réunion'),
        (18, 'Mayotte');

        INSERT INTO `profil` (`id`, `nom`) VALUES
        (1, 'administrateur'),
        (2, 'visiteur médical'),
        (3, 'délégué régional'),
        (4, 'comptable');

        INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `login`, `mot_passe`, `date_embauche`, `date_depart`, `id_region`, `id_profil`) VALUES
        (1, 'Durieux', 'Justine', 'jdurieux', 'password', '2024-09-11', NULL, 10, 2),
        (2, 'Bioret', 'Luc', 'lbioret', 'password', '2010-09-01', NULL, 11, 2),
        (3, 'Frémont', 'Fernande', 'ffremont', 'password', '2011-10-01', '2020-09-01', 13, 2),
        (4, 'Clepkens', 'Christophe', 'cclepkens', 'password', '2011-10-27', NULL, 8, 3),
        (5, 'Andre', 'David', 'dandre', 'password', '2011-11-01', NULL, 11, 2),
        (6, 'Raquin', 'Mélanie', 'mraquin', 'password', '2011-11-23', '2017-05-27', 13, 2),
        (7, 'Duncombe', 'Claude', 'cduncombe', 'password', '2012-01-03', NULL, 10, 3),
        (8, 'Prévost', 'Samuel', 'sprevost', 'password', '2012-02-15', NULL, 8, 4),
        (9, 'Durant', 'Sabine', 'sdurant', 'password', '2012-05-01', NULL, 11, 2),
        (10, 'Bonnot', 'Paul', 'pbonnot', 'password', '2012-08-01', NULL, 10, 2),
        (11, 'Desnost', 'Pierre', 'pdesnost', 'password', '2014-02-05', NULL, 10, 2),
        (12, 'Finck', 'Jacques', 'jfinck', 'password', '2014-11-10', NULL, 13, 2),
        (13, 'Rabaud', 'Marilou', 'mrabaud', 'password', '2014-11-10', NULL, 12, 4),
        (14, 'Cottin', 'Vincent', 'vcottin', 'password', '2014-11-18', NULL, 8, 2),
        (15, 'Durant', 'Pierre', 'pdurant', 'password', '2015-03-01', NULL, 13, 3),
        (16, 'Desmarquest', 'Nathalie', 'ndesmarquest', 'password', '2015-11-12', NULL, 12, 2),
        (17, 'Lapointe', 'Marie', 'mlapointe', 'password', '2015-11-12', NULL, 8, 2),
        (18, 'Villechalane', 'Louis', 'lvillachane', 'password', '2015-12-21', NULL, 11, 2),
        (19, 'Marionnaud', 'Salomé', 'smarionnaud', 'password', '2016-02-08', NULL, 13, 1),
        (20, 'Debelle', 'Michel', 'mdebelle', 'password', '2016-11-23', '2017-12-01', 8, 2),
        (21, 'Debelle', 'Jeanne', 'jdebelle', 'password', '2016-12-01', NULL, 12, 3),
        (22, 'Loubert', 'Armand', 'aloubert', 'password', '2016-12-01', NULL, 8, 2),
        (23, 'Chaize', 'Henri', 'hchaize', 'password', '2017-09-23', NULL, 8, 2),
        (24, 'Cacheux', 'Bernard', 'bcacheux', 'password', '2023-11-12', NULL, 8, 2),
        (25, 'Bentot', 'Pascal', 'pbentot', 'password', '2018-07-20', NULL, 11, 3),
        (26, 'Bunisset', 'Denise', 'dbunisset', 'password', '2018-12-05', NULL, 8, 2),
        (27, 'De', 'Eric', 'ede', 'password', '2018-12-14', NULL, 8, 2),
        (28, 'Bunisset', 'Francis', 'fbunisset', 'password', '2019-08-28', NULL, 8, 2),
        (29, 'Daburon', 'François', 'fdaburon', 'password', '2020-02-11', NULL, 8, 2),
        (30, 'Bedos', 'Christian', 'cbedos', 'password', '2020-03-01', NULL, 11, 2),
        (31, 'Adebroise', 'Michel', 'madebroise', 'password', '2021-10-15', NULL, 12, 2),
        (32, 'Jourdain', 'Florent', 'fjourdain', 'password', '2022-01-15', NULL, 10, 4),
        (33, 'Rialhe', 'Romane', 'rrialhe', 'password', '2022-01-26', NULL, 11, 4),
        (34, 'Belle', 'Bastien', 'bbelle', 'password', '2022-02-10', NULL, 13, 4);
    ";
    $pdo->exec($insertData);

    //*************************************************************************** */
    echo "Création du trigger pour la numérotation de fiches de frais HF <br/><br/>\n";
    flush();
    $createTrigger="
        CREATE TRIGGER trig_fraishorsforfait_num  
        BEFORE INSERT ON fraishorsforfait  
        FOR EACH ROW
        BEGIN
            DECLARE generate_num INTEGER;
            IF (NEW.num IS NULL OR NEW.num = 0) THEN
                SET generate_num =  
                (
                SELECT COALESCE(MAX(num), 0) + 1
                FROM fraishorsforfait
                WHERE id_visiteur = NEW.id_visiteur AND mois = NEW.mois
                );
                SET NEW.num = generate_num;
            END IF;
        END
    ";
    $pdo->exec($createTrigger);

    //*************************************************************************** */
    set_time_limit(2);
    echo "Définition des privilèges pour l'utilisateur " . $USER . "<br/><br/>\n";
    flush();
    $user1 = "'" . $USER. "'@'localhost'";
    $user2 = "'" . $USER. "'@'%'";

    $pdo->exec("DROP USER IF EXISTS " . $user1);
    $pdo->exec("DROP USER IF EXISTS " . $user2);
    $pdo->exec("GRANT SELECT, INSERT, UPDATE, DELETE ON gsbfrais.* TO " . $user1 . " IDENTIFIED BY '" . $MDP ."'");
    $pdo->exec("GRANT SELECT, INSERT, UPDATE, DELETE ON gsbfrais.* TO " . $user2 . " IDENTIFIED BY '" . $MDP ."'");
    $pdo->exec("FLUSH PRIVILEGES");

    echo "Base de données gsbfrais créée avec succès avec les tables et les privilèges définis.";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

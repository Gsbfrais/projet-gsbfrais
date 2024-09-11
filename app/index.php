<?php include_once("./gsbfrais/config/config.php") ?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LAMP STACK</title>
        <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
        <link rel="stylesheet" href="/assets/css/bulma.min.css">
    </head>
    <body>
        <section class="hero is-medium is-info is-bold">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title">
                        Serveur LAMP dockerisé
                    </h1>
                    <h2 class="subtitle">
                        BTS SIO Chantilly 
                    </h2>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="columns">
                    <div class="column">
                        <h3 class="title is-3">Environment</h3>
                        <hr>
                        <div class="content">
                            <ul>
                                <li><?= apache_get_version(); ?></li>
                                <li>PHP <?= phpversion(); ?></li>
                                <li>
                                    <?php                                  
                                    try{
                                        $database = 'mysql:host=database:'.DB_PORT;
                                        $pdo = new PDO($database, DB_USER, DB_PWD);
                                        printf("%s", $pdo->query('select version()')->fetchColumn());  
                                    } catch(PDOException $e) {
                                        echo "Error: Impossible de se connecter à MySQL. Error:\n $e";
                                    }
                                    $pdo = null;


                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="column">
                        <h3 class="title is-3">Accès rapides</h3>
                        <hr>
                        <div class="content">
                            <ul>
                                <li><a href="gsbfrais/index.php">Gsbfrais</a></li>
                                <li><a href="utilitaires/createBdd.php">Création de la BDD gsbfrais</a></li>
                                <li><a href="utilitaires/genData.php">Génération des données dans la BDD</a></li>
                                <li><a href="utilitaires/phpinfo.php">phpinfo()</a></li>
                                <li><a href="http://localhost:<? print 8080; ?>/index.php">phpMyAdmin</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="footer">
            <div class="content has-text-centered">
                <p>
                    <strong>Projet Gsbfrais sous Docker</strong><br>
                    AP de deuxième année BTS SIO.
                </p>
            </div>
        </footer>
    </body>
</html>

<?php
// chemin du répertoire de l'application
$rootPath = dirname(__DIR__, 1);
// détermine si l'application est installée en tant que virtualhost ou directement
// dans le répertoire racine du serveur web (htdocs, www, ...)
$virtualhost = ($rootPath == $_SERVER['DOCUMENT_ROOT']) ? true : false;
// Détermine l'url d'accès à la page d'accueil du site (index.php à la racine du projet)
if  ($virtualhost) :
    if (empty($_SERVER['HTTPS']) == false) {
        $rootUrl = 'https://' . $_SERVER['HTTP_HOST']. '/';
    } else {
        $rootUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/';
    }
else:
    if (empty($_SERVER['HTTPS']) == false) {
        $rootUrl = 'https://' . $_SERVER['HTTP_HOST'] . DIRECTORY_SEPARATOR .'gsbfrais/';
    } else {
        $rootUrl = 'http://' . $_SERVER['HTTP_HOST'] . DIRECTORY_SEPARATOR .'gsbfrais/';
    } 
endif;
$logFile = $_SERVER['DOCUMENT_ROOT'] . "/logs/gsbfrais.log";


define('STATUT_ERREUR_HTTP', [
    '400' => ["Bad request", "Requête erronée"],
    '401' => ["Unauthorized", "urorisation requise. Vous devez vous identifier."],
    '403' => ["Forbidden", "Vous n'êtes pas autorisé à accéder à cette ressource"],
    '404' => ["Not found", "Cette ressource n'existe pas"],
    '500' => ["Internal Server Error", "Un problème est survenu. Veuillez réessayer ultérieurement."]
]);

define('ROOT_URL', $rootUrl);                     // url racine de l'application
define('ROOT_PATH', dirname(__DIR__, 1) . '/');   // dossier racine du projet
define('LOG_FILE', $logFile);                     // fichier journal des logs
define('DB_HOST', 'database');                    // adresse du serveur MySQL - ATTENTION, ici nom du service dans docker-compose.yml
define('DB_PORT', '3306');                        // port du serveur MySQL
define('DB_NAME', 'gsbfrais');                    // nom de la base de données
define('DB_USER', 'USR_APPLI_GSBFRAIS');          // nom du compte utilisé par l'application pour se connecter à la base
define('DB_PWD', '@p05plGF!!ytgr47');             // mot de passe du compte
define('MOD_DEV', true);                          // mode développement 

unset($rootPath, $rootUrl, $virtualhost, $logFile);
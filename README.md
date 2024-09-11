# Présentation

Ce container contient une suite LAMP
* apache2
* php8.3
* mysql8
* phpmyadmin
* L'application web `gsbfrais` et quelques utilitaires pour la mise en place des données

# Prérequis

`docker` et `docker-compose` doivent être installés.

Il suffit d'installer Docker Desktop (https://www.docker.com/products/docker-desktop/)

# Configuration du projet

Dans le fichier `.env`, sont définis :
- Le nom du projet (COMPOSE_PROJECT_NAME)
- Le mot de passe root pour MySQL (MYSQL_ROOT_PASSWORD)
- Le nom de la base de données de l'application (MYSQL_DATABASE)
- Le nom du user et son mot de passe utilisés pour l'accès à la base de données (MYSQL_USER et MYSQL_PASSWORD)

Si vous devez utilisez plusieurs serveurs web en parallèle, vous devrez changer les ports utilisés. Par défaut :
- le serveur web écoute sur les ports 80 en http et 443 en https
- le serveur mysql écoute sur le port 3306
- phpmyadmin est accessible sur les ports 8080 en http et 8443 en https

# Installation

Exécuter la commande `docker compose up --wait`.

La pile LAMP stack est prête. Vous pouvez y accéder via `http://localhost:80`.

# Mise en oeuvre de SSL (HTTPS)

Le support pour `https` est en place mais non activé par défaut car il faut générer un certificat auto-signé pour la machine hôte 

Pour activer `https` vous devez, après avoir arrêter vos conteneurs :

1. Utilisez openssl pour créer un certificat SSL pour `localhost`:
   - Suivre les consignes données dans le fichier `Creation du certificat.md` dans le répertoire `/genCertificat`
   - Déplacez le certificat `cert.pem` et la clé privée `cert-key.pem` dans votre configuration Docker en les plaçant dans le répertoire `config/ssl`.
   
2. Décommentez le vhost `443` dans `config/vhosts/000-default.conf`

3. Redémarrez les conteneurs 

Désormais, vous pouvez vous connecter à l'application en HTTPS avec l'url `https://localhost`

# Divers

## phpMyAdmin

phpMyAdmin est configuré pour s'exécuter sur le port 8080. Les informations de connexion à la base de données sont définies dans le fichier `.env`.

Par défaut : 
http://localhost:8080/  
username: root  
password: sio2025@rostand

## Utilisation de Xdebug

Xdebug est installé par défaut.

Pour l'utiliser dans VS Code, il faut installer l'extension "PHP Debug" puis aller dans le menu Debug et créer le fichier `.vscode/launch.json`. 

**IMPORTANT:** le paramètre `pathMappings` dépend de la manière dont vous avez ouvert VS Code. 

Si vous créez le fichier `.vscode/launch.json` à la racine du projet docker, vous devez indiquez : 

```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type": "php",
      "request": "launch",
      "port": 9003, 
      "pathMappings": {
        "/var/www/html": "${workspaceFolder}/app"
      }
    }
  ]
}
```

Si vous créez le fichier `.vscode/launch.json` dans le dossier de l'application `./app` 

```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type": "php",
      "request": "launch",
      "port": 9003, 
      "pathMappings": {
        "/var/www/html": "${workspaceFolder}" 
      }
    }
  ]
}
```

## Modification du fichier php.ini

Il est possible de personnaliser le fichier `php.ini` pour répondre à vos besoins.

Les modifications sont à effectuer dans le fichier `./config/php/php.ini`

## Modules Apache

Par défaut, les modules suivants sont activés.

- rewrite
- headers

Pour ajouter d'autres modules, il suffit de modifier le fichier `./Dockerfile`
> Il faut alors reconstruire l'image docker en exécutant la commande `docker compose build` puis en redémarrant les containers.

## Connexion au server web

Vous pouvez vous connecter au serveur web à l'aide de la commande `docker compose exec`.

Utilisez alors la commande ci-dessous pour vous connecter au container via ssh.

```shell
docker compose exec www bash
```

## Autres éléments installés

composer

nodejs

## Extensions PHP

Les extensions suivantes sont installées : 

- mysqli
- pdo_sqlite
- pdo_mysql
- mbstring
- zip
- intl
- mcrypt
- curl
- json
- iconv
- xml
- xmlrpc
- gd
- xdebug3


> Si vous souhaitez installer d'autres extensions, modifiez le fichier `./Dockerfile`.
> 
> Il faut alors reconstruire l'image docker en exécutant la commande `docker compose build` puis en redémarrant les containers.


# OPSources



## 1. Dépendances et prérequis (exemple avec Apache)

Sur le système doit être installé (à adapter selon la distribution):
* Apache
* Curl
* npm (https://www.rosehosting.com/blog/how-to-install-nodejs-bower-and-gulp-on-a-centos-7-vps/)
* bower (https://www.rosehosting.com/blog/how-to-install-nodejs-bower-and-gulp-on-a-centos-7-vps/)
* extension ldap php
    * yum install php-ldap
    * vi /etc/php.ini
    * add extension=ldap.so
    * service httpd restart
* plexcel
    * Pour le PlexcelBundle :  http://www.ioplex.com/plexcel.html
* PHP GD
    * yum install gd gd-devel php-gd
    * service httpd restart
* PHP Ldap
* Less
    * npm install -g less
    
    
Ne pas oublier d'augmenter dans le php.ini :

max_execution_time et set_time_limit

## 2. Déploiement 

Commande de déploiement en étant connecté sur la preprod _(première installation)_ :

    git clone ...
    
Pour les mises à jour du code donc après la 1ère installation faire :

    git pull

## 3. Gestion des bibliothèques Javascript et CSS _(première installation)_

L'ensemble des bibliothèques externes (sauf exceptions futures) est géré à
l'aide de bower.

L'installation de bower est réalisable simplement à l'aide de la commande :

    npm install bower -g

Les bibliothèques sont ensuite installables en se plaçant à la racine du
projet et en lançant la commande :

    bower install
    
## 4. Composer ( /!\ .lock )

```sh
composer install
```

Si une erreur est retournée concernant le composer.lock il faut faire :

```sh
composer update
```

puis refaire :

```sh
composer install
```

## 5. Gestion des droits pour rendre app.php accessible (prod)

Définition des permissions par ACL

```sh
 rm -rf app/cache/*
 rm -rf app/logs/*

 HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
 sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
 sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
```

Pour plus d'informations ou en cas d'échec, consulter la rubrique "Définir les permissions" de la documentation officielle de Symfony :

http://symfony.com/fr/doc/current/book/installation.html

## 6. Génération des PDF et wkhtmltopdf _(première installation)_

Installer wkhtmltopdf et des polices (en root) :

```sh
yum install -y xorg-x11-fonts-75dpi && yum install -y xorg-x11-fonts-Type1 && wget http://downloads.sourceforge.net/project/wkhtmltopdf/0.12.2.1/wkhtmltox-0.12.2.1_linux-centos7-amd64.rpm && rpm -Uvh wkhtmltox-0.12.2.1_linux-centos7-amd64.rpm
```

Installer xvbf :

```sh
yum install xorg-x11-server-Xvfb
```

## 7. Installation des assets (ressources JS/CSS des bundles)

Autorisation en terme de droits sur le dossier web et création d'un dossier pour stocker les medias.

```sh
sudo mkdir web/uploads && sudo mkdir web/uploads/media && sudo chmod -R 777 web
```
Installation des assets : 
```sh
php app/console assetic:dump
```
puis

```sh
php app/console assets:install
```

## 8. Doctrine Migration : modification du schéma de BdD _(première installation)_

```sh
php app/console doctrine:migrations:migrate
```
## 9. Lancer les fixtures (création des types d'entretiens) _(première installation)_

```sh
php app/console doctrine:fixtures:load --append
```
## 10. Ajout de l'ancien template sur SonataAdmin et mapping aves les fiches existantes  _(première installation)_

### 1. Se connecter sur SonataAdmin

 >http://OPSources.fr/admin/dashboard

### 2. Aller à l'adresse suivante : 
   > http://OPSources.fr/app_dev.php/admin/generator/opussheettemplate/create
   
### 3. Créer l'ancien "Modèle de fiche" 

Ajouter un modèle de fiche.
Déterminer le type à “Entretien annuel” et lier le fichier "app/config/BaseFormMeet/old_meet2.yml”. Retenir l’id qui aura été généré, en toute logique : 1.

### 4.  Se connecter à la base de données :
```sh
psql -h bddop -U opuser
```
### 5. Exécuter les commandes suivantes :
La valeur déterminée est bien sûr l'id du modèle de fiche que vous venez de créer.
```sh
UPDATE op_sheet SET template_id = 1;
```
```sh
UPDATE op_campaign SET template_id = 1;
```

# Arianespace



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

## 2. Déploiement

Commande de déploiement en étant connecté sur la preprod :

    git clone http://ae-e-scm01.ad.arianespace.fr/arianespace/opus2.git

## 3. Gestion des bibliothèques Javascript et CSS

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

## 5. Gestion des droits pour rendre app.php accessible

http://symfony.com/fr/doc/current/book/installation.html

## 6. Génération des PDF et wkhtmltopdf

https://jaimegris.wordpress.com/2015/03/04/how-to-install-wkhtmltopdf-in-centos-7-0/

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

## 8. Doctrine Migration : modification du schéma de BdD

```sh
php app/console doctrine:migrations:migrate
```
## 9. Lancer les fixtures (création des types d'entretiens)

```sh
php app/console doctrine:fixtures:load --append
```
## 10. Ajout de l'ancien template sur SonataAdmin et mapping aves les fiches existantes

### 1. Se connecter sur SonataAdmin

 >http://opus33.ad.arianespace.fr/admin/dashboard

### 2. Aller à l'adresse suivante : 
   > http://opus33.ad.arianespace.fr/app_dev.php/admin/generator/opussheettemplate/create
   
### 3. Créer l'ancien "Modèle de fiche" 

Ajouter un modèle de fiche.
Déterminer le type à “Entretien annuel” et lier le fichier "app/config/BaseFormMeet/old_meet2.yml”. Retenir l’id qui aura été généré, en toute logique : 1.

### 4.  Se connecter à la base de données :
```sh
psql -h pg21 -U opus32
```
### 5. Exécuter les commandes suivantes :
La valeur déterminée est bien sûr l'id du modèle de fiche que vous venez de créer.
```sh
UPDATE opus_sheet SET template_id = 1;
```
```sh
UPDATE opus_campaign SET template_id = 1;
```
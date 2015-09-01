Procédure Arianespace
=====================
1. Doctrine Migration
---------------------
```sh
php app/console doctrine:migrations:migrate
```
2. Lancer les fixtures
----------------------
```sh
php app/console doctrine:fixtures:load --append
```
3. Ajout de l'ancien template sur SonataAdmin
---------------------------------------------
1. Se connecter à SonataAdmin
2. Aller à l'adresse suivante : 
   > http://opus33.ad.arianespace.fr/app_dev.php/admin/generator/opussheettemplate/create
3. Créer le "Modèle de fiche" et lier le fichier "old_meet2.yml"
4. Mettre à jour la base de données
En ligne de commande: 
1.  Se connecter à la base de données :
```sh
psql -h pg21 -U opus32
```
2.  exécuter les commandes suivantes :
```sh
UPDATE opus_sheet SET template_id = 1;
```

```sh
UPDATE opus_campaign SET template_id = 1;
```
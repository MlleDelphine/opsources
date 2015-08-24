Arianespace
=========================


Dependances
-----------

Sur le système doit être installé :
    - npm
    - bower
    - extension ldap php
    - plexcel

Déploiement
-----------

Commande de déploiement en etant connecté sur la preprod :

    git pull


Gestion des librairies javascript et css
----------------------------------------

L'ensemble des bibliothèques externes (sauf exceptions futures) est géré à
l'aide de bower.

L'installation de bower est réalisable simplement à l'aide de la commande :

    npm install bower -g

Les bibliothèques sont ensuite installable en ce placant dans la racine du
projet et en lancant la commande :

    bower install
    
Procédure de déploiement
----------------------------------------

Se référer au fichier : deploy.md
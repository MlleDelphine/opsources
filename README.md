Arianespace
=========================


Dépendances (exemple avec Apache)
-----------

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

Déploiement
-----------

Commande de déploiement en étant connecté sur la preprod :

    git init && git clone http://ae-e-scm01.ad.arianespace.fr/arianespace/opus2.git


Gestion des bibliothèques Javascript et CSS
----------------------------------------

L'ensemble des bibliothèques externes (sauf exceptions futures) est géré à
l'aide de bower.

L'installation de bower est réalisable simplement à l'aide de la commande :

    npm install bower -g

Les bibliothèques sont ensuite installables en se plaçant à la racine du
projet et en lançant la commande :

    bower install

Procédure de déploiement
----------------------------------------

Se référer au fichier : deploy.md
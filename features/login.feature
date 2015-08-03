# language: fr
Fonctionnalité: Pouvoir se connecter
    En tant qu'utilisateur je dois pouvoir me connecter 
    correctement le formulaire de login, me déconnecter, 
    demander un nouveau mot de passe

    Scénario: Redirection vers login
      Etant donné je suis sur "/"      
      Alors je suis sur "/login" 

#    Scénario: Redirection vers login
#      Etant donné je suis sur "/"      
#      Alors je suis sur "/login"
#
#    Scénario: Connexion
#      Etant donné je suis sur "/login"
#      Quand je remplis "_username" avec "super_admin"
#      Et je remplis "_password" avec "321"
#      Et je presse "Connexion"
#      Alors devrais être sur "/reservation/"
#      Et je devrais voir "Déconnexion"
#      Et je devrais voir "Bienvenue, super_admin"
#
#    Scénario: DéConnexion
#      Étant donné que je suis identifié en tant que "super_admin"
#      Et je suis sur "/"
#      Alors je devrais voir "Déconnexion"
#      Quand je vais sur "/logout"
#      Alors je devrais être sur "/login"
#      Et mon utilisateur s'appelle "tartenpion"
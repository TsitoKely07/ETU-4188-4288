# Tâches du projet

## Répartition des tâches

### Herizo (côté client)

 Créer l'écran de connexion client.
 Implémenter la validation du numéro de téléphone et la création automatique du compte client.
 Construire les actions client : dépôt, retrait, transfert.
 Enregistrer l'historique des opérations dans `historique_operation`.
 Créer la page dashboard client avec solde et historique.
 Vérifier le CSS client et l'harmoniser avec le côté opérateur.

### Moi (côté opérateur)
 Construire le dashboard opérateur.
 Afficher les gains par type d'opération.
 Afficher la situation des comptes clients.
 Gérer les préfixes valables pour les numéros.
 Gérer les barèmes de frais par tranche d'opération.
 S'assurer que les requêtes SQL utilisent le bon schéma et les bonnes tables SQLite.

# BASE DE DONNEES:SQLite3
## Creation des tables:
-prefixe(Pour choisir le format des numeros)
-compte_client
-type_operation(depot,retrait,transfert)
-bareme_frais
-historique_operation


# BACK-END:
### Moi (côté opérateur):
#### Controllers
Creation Controller OperateurController:Pour manipuler les gains,les numeros des clients,gestion des prefixes,ajouter prefixe et Gestion des baremes de frais par tranches
Creation de AuthController pour le login operateur et pour securiser les donnees et redirection vers view/operator/login 


#### Models
Creation de PrefixeModel.php pour parler a la base de donnees


### Herizo:
### Herizo (côté client):
#### Views
creation du page login.php :pour l'iscription 
creation du page dashboard.php pour afficher les card :depot ,retrait ,transfert et affiche les historiques
#### Controllers
creation des controller : pour manipiler le base pour le cote client ,chemin , le dashboard, l historique les calculs de retrait,transfert, ...   
#### root

# FRONT-END:



## NOTES:
Pour entrer dans l'espace operateur
# IDENTIFIANT: operateur
# MOT DE PASSE : operateur123

## Cote client 
modification du dashboard.php
    -case a coche sur MultiTranfert
    -modification de operationController
    -Calcul de comission 






## VERSION 2
### Moi (côté opérateur):
1. Authentification
Controller : app/Controllers/operator/AuthController.php
login() — affiche la page de connexion
loginProcess() — vérifie les identifiants (username/password via .env)
logout() — déconnexion
View : app/Views/operator/login.php — formulaire de connexion
2. Dashboard / Layout principal
View : app/Views/operator/layout.php
Topbar avec le nom d'utilisateur connecté + bouton de déconnexion
Sidebar responsive avec 6 liens de navigation :
Situation des gains
Comptes clients
Préfixes valables
Barèmes des frais
Commissions inter-opérateurs
Décompte opérateur
Design moderne (Sora + Inter fonts), responsive mobile


# BASE DE DONNEES:
5. Base de données (via Migrations)
Toutes les tables côté opérateur sont maintenant gérées par des migrations :

operateur (id, nom, code)
prefixe (code, id_operateur + FK)
bareme_frais (type, tranche, frais, id_operateur, type_frais)
commission_interoperateur (source, destination, pourcentage)
decompte_operateur (opérateur, mois, montants, statut)




Herizo a pris la gestion du côté client.
Moi j'ai pris la gestion du côté opérateur.
Il faut maintenir la même structure de tables entre les vues client et opérateur.

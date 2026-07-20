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






Herizo a pris la gestion du côté client.
Moi j'ai pris la gestion du côté opérateur.
Il faut maintenir la même structure de tables entre les vues client et opérateur.

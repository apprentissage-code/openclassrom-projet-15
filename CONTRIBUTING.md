# CONTRIBUTING.md

# Guide de contribution

Merci de vouloir contribuer au projet.

Ce document définit les règles et bonnes pratiques à suivre pour contribuer efficacement au développement du projet.

---

# Sommaire

- Signaler un problème
- Proposer une fonctionnalité
- Workflow Git
- Standards de développement
- Contribution au code
- Contribution aux tests
- Contribution à la documentation
- Revue de code
- Sécurité

---

# Signaler un problème

Avant de créer une issue, merci de vérifier que le problème n’a pas déjà été signalé.

Lors de la création d’une issue, fournissez les informations suivantes :

- description claire du problème
- étapes pour reproduire
- comportement attendu
- comportement observé
- captures d’écran si nécessaire
- environnement utilisé :
  - système d’exploitation
  - version PHP
  - navigateur
  - version Symfony.

## Exemple

```md
### Description
Une erreur apparaît lors de la création d’un utilisateur.

### Étapes pour reproduire
1. Aller sur /register
2. Remplir le formulaire
3. Cliquer sur "Valider"

### Résultat attendu
L’utilisateur doit être créé.

### Résultat obtenu
Erreur 500 affichée.

# Proposer une fonctionnalité

Les propositions d’amélioration sont les bienvenues.

Merci d’indiquer :

le besoin fonctionnel
le problème résolu
le comportement attendu
les impacts éventuels sur l’existant.

Si possible, ajoutez :

des maquettes
des exemples d’utilisation
des captures d’écran.

# Workflow Git
## Création d’une branche

Toujours créer une branche dédiée :
```bash
git checkout -b feature/nom-feature
```

## Convention des commits

Utiliser des messages de commit explicites.

Format recommandé :
type: description

### Exemples
- feat: ajout du système de réservation
- fix: correction du bug de connexion
- docs: mise à jour du README
- test: ajout des tests utilisateurs
- refactor: simplification du service email

# Contribution au code
## Standards PHP

Le projet suit :
- PSR-12
- les conventions Symfony
- une architecture claire et maintenable.
- Bonnes pratiques
privilégier des méthodes courtes
- éviter la duplication de code
- utiliser l’injection de dépendances
- respecter le typage PHP
- éviter la logique métier dans les contrôleurs
- privilégier les services Symfony.

## Structure du projet
- src/            -> Code source Symfony
- templates/      -> Templates Twig
- public/         -> Fichiers publics
- config/         -> Configuration Symfony
- migrations/     -> Migrations Doctrine
- tests/          -> Tests automatisés

# Contribution aux tests

Toute nouvelle fonctionnalité doit être testée.

## Types de tests
- tests unitaires
- tests fonctionnels
- tests d’intégration si nécessaire.

## Lancer les tests
```bash
php bin/phpunit
```

## Bonnes pratiques
- couvrir les cas principaux
- couvrir les cas d’erreur
- garder les tests lisibles
- éviter les dépendances entre tests.

# Contribution à la documentation

La documentation est importante pour la maintenance du projet.

Merci de :
- garder les explications claires
- mettre à jour le README si nécessaire
- documenter les nouvelles fonctionnalités
- documenter les commandes importantes.

# Pull Requests
Avant de soumettre une Pull Request

Vérifiez que :
- les tests passent
- aucun fichier sensible n’est commit
- le code respecte les standards
- la branche est à jour.

## Description de la Pull Request

Merci d’indiquer :
- le contexte
- les modifications effectuées
- les impacts éventuels
- les captures d’écran si interface graphique.

# Revue de code

Chaque Pull Request peut être relue avant validation.

Les points vérifiés :

- qualité du code
- lisibilité
- respect des conventions
- couverture des tests
- performances
- sécurité.

Des modifications peuvent être demandées.

# Sécurité

Ne jamais committer :
- .env.local
- mots de passe
- clés API
- tokens
- données sensibles
- dumps de production non anonymisés.

# Gestion des fichiers volumineux

Le fichier backup.zip contient actuellement :
- un dump SQL anonymisé
- les fichiers uploadés.

Le fichier dépassant 1 Go, une solution alternative de stockage pourra être envisagée :
- stockage cloud
- serveur dédié
- Git LFS
- archive externe


# Questions

Pour toute question concernant le projet ou les contributions, merci de contacter les mainteneurs du projet.

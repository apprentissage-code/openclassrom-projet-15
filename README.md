<img src="public/images/logo.png" alt="InaZaoui" width="200" />

# Ina Zaoui

## Pré-requis
* PHP >= 8.1
* Composer
* Extension PHP Xdebug
* Symfony (binaire)

## Installation

### Composer
Dans un premier temps, installer les dépendances :
```bash
composer install
```

### Docker (optionnel)
Si vous souhaitez utiliser Docker Compose, il vous suffit de lancer la commande suivante :
```bash
docker compose up -d
```

## Configuration

### Base de données
Actuellement, le fichier `.env` est configuré pour la base de données PostgreSQL mise en place dans `docker-compose.yml`.
Cependant, vous pouvez créer un fichier `.env.local` si nécessaire pour configurer l'accès à la base de données.
Exemple :
```dotenv
DATABASE_URL=postgresql://root:Password123!@host:3306/ina_zaoui
```
### Backup
Vous trouverez dans le fichier `backup.zip` un dump SQL anonymisé de la base de données et toutes les images qui se trouvaient dans le dossier `public/uploads`.

### PHP (optionnel)
Vous pouvez surcharger la configuration PHP en créant un fichier `php.local.ini`.

De même pour la version de PHP que vous pouvez spécifier dans un fichier `.php-version`.

## Usage

### Base de données

#### Supprimer la base de données
```bash
symfony console doctrine:database:drop --force --if-exists
```

#### Créer la base de données
```bash
symfony console doctrine:database:create
```

#### Exécuter les migrations
```bash
symfony console doctrine:migrations:migrate -n
```

#### Charger les fixtures
```bash
symfony console doctrine:fixtures:load -n --purge-with-truncate
```

*Note : Vous pouvez exécuter ces commandes avec l'option `--env=test` pour les exécuter dans l'environnement de test.*

### Tests
```bash
symfony php bin/phpunit
```

*Note : Penser à charger les fixtures avant chaque éxécution des tests.*

### Serveur web
```bash
symfony serve
```

### Connexion à un compte test admin
Vous pouvez vous connecter à un compte admin via ses identifiants :
- mail : `ina@zaoui.com`
- mot de passe : `password`

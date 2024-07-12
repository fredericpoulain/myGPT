# MyGPTProject

## Description

MyGPTProject est une application web interactive développée avec Symfony, Docker, et React. Elle permet aux utilisateurs d'interagir avec ChatGPT et DALL-E 3, de sélectionner le modèle AI souhaité, de générer des images, et de gérer leurs discussions enregistrées.

## Fonctionnalités Principales

- **Interaction avec ChatGPT** : Interface web pour interagir avec ChatGPT.
- **Mise en forme du code** : Utilisation de HighlightJS pour la mise en forme des extraits de code.
- **Sélection du modèle AI** : Possibilité de choisir parmi plusieurs modèles OpenAI (GPT-4, GPT-4 Turbo, GPT-3.5 Turbo, DALL-E 3).
- **Génération d'images avec DALL-E 3** : Création d'images à partir de descriptions textuelles.
- **Enregistrement des discussions** : Sauvegarde des discussions en session et en base de données.
- **Authentification utilisateur** : Système de connexion pour persister les discussions.
- **Affichage et gestion de l'historique** : Consultation et suppression des discussions passées.

## Configuration et Lancement de l'Application

### Prérequis

- Docker
- Docker Compose


### Instructions de Lancement

1. **Cloner le dépôt :**

    ```bash
    git clone git@github.com:fredericpoulain/myGPT.git folderName
    cd folderName
    ```

2. **Configurer et démarrer les conteneurs Docker :**

    ```bash
    docker-compose up -d
    ```

3. **Accéder au conteneur de l'application Symfony :**

    ```bash
    docker exec -ti myGptProject bash
    cd project
    ```

4. **Installer les dépendances Composer et Node.js :**

   À l'intérieur du conteneur :

    ```bash
    composer install
    npm install
    npm run build
    ```

5. **Modification du fichier env.local :**

**Modifier ou créer le fichier .env.local avec ces informations**

`DATABASE_URL="mysql://root:@containerMySQLmyGpt:3306/myGptDB?serverVersion=8.3.0&charset=utf8mb4"`

`MAILER_DSN="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"`

`OPENAI_API_KEY='sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'`

6. **Accéder à l'application :**

   Ouvrez votre navigateur et allez à l'adresse [http://127.0.0.1:8000](http://127.0.0.1:8000)

7. **Accéder à phpMyAdmin :**

   Ouvrez votre navigateur et allez à l'adresse [http://127.0.0.1:8080](http://127.0.0.1:8080)


## Auteur

- **Frédéric Poulain**
- [Mon profil Github](https://github.com/fredericpoulain)




Projet Laravel + Nuxt avec Docker
Ce projet combine un backend Laravel et un frontend Nuxt, le tout orchestré avec Docker pour simplifier la gestion et le déploiement. Voici les étapes pour lancer le projet.

Prérequis
Docker et Docker Compose installés
Node.js et Yarn installés
Étapes pour lancer le projet
1. Cloner le dépôt
Clonez le dépôt sur votre machine locale.

git clone https://github.com/votre-utilisateur/votre-projet.git
cd votre-projet
2. Configuration de l'API
Allez dans le répertoire api et copiez le fichier .env.example en .env.

cd api
cp .env.example .env
3. Lancer les conteneurs Docker
Revenez à la racine du projet et lancez les conteneurs Docker avec Docker Compose.

cd ..
docker-compose up -d
4. Configuration du frontend
Allez dans le répertoire web, installez les dépendances avec Yarn et démarrez le serveur de développement.

cd web
yarn
yarn dev
Accès au projet
Le backend Laravel est accessible via http://localhost:8000.
Le frontend Nuxt est accessible via http://localhost:3001.
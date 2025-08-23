#!/bin/bash

# Mettre à jour les paquets
apt-get update

# Installer PHP et les extensions nécessaires
apt-get install -y php php-common php-cli php-json php-mbstring php-zip

# Installer Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt-get install -y nodejs

# Installer les dépendances PHP (si vous avez composer)
if [ -f "composer.json" ]; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    composer install --no-dev --optimize-autoloader
fi

# Installer les dépendances Node.js
npm install

# Compiler TypeScript (si nécessaire)
if [ -f "tsconfig.json" ]; then
    npm run build
fi

# Donner les permissions d'écriture pour db.json
chmod 666 db.json || true
touch db.json
chmod 666 db.json

# Créer un fichier .env pour Render
echo "PORT=$PORT" > .env
echo "NODE_ENV=production" >> .env
FROM php:8.2-apache

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Installer les extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application
COPY . /var/www/html

# Définir le répertoire de travail
WORKDIR /var/www/html

# Donner les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod 666 db.json || true \
    && touch db.json \
    && chmod 666 db.json

# Installer les dépendances PHP si composer.json existe
RUN if [ -f "composer.json" ]; then composer install --no-dev --optimize-autoloader; fi

# Installer les dépendances Node.js et compiler TypeScript
# Continuer même si il y a des erreurs TypeScript (--noEmitOnError false)
RUN if [ -f "package.json" ]; then npm install && npx tsc --noEmitOnError false || echo "TypeScript compilation completed with warnings"; fi

# Configurer Apache
RUN a2enmod rewrite
COPY .htaccess /var/www/html/.htaccess

# Exposer le port
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
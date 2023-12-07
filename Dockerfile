FROM php:8.2-fpm 

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev


#installer logiciel composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#installer pdo_mysql mbstring et zib
RUN docker-php-ext-install pdo_mysql mbstring zip

#définir répertoire de trvail, toutes commande après sera exécuté dans ce répertoire
WORKDIR /www

#copy composer.json dans /www
COPY composer.json .

#lance la commande composer install
RUN composer install --no-scripts

# Utiliser une image Node.js pour exécuter npm run dev
FROM node:14

# Définir le répertoire de travail dans le conteneur
WORKDIR /usr/src/app

# Installer les dépendances
RUN npm install

# Copier le reste des fichiers du projet dans le conteneur
COPY . .

# Exécuter la commande npm run build
CMD npm run build

FROM php:8.2-fpm 


#définir répertoire de trvail, toutes commande après sera exécuté dans ce répertoire
WORKDIR /www

#copie tout mon projet laravel dans /www de l'image docker
COPY . .

EXPOSE 8069

#host 0.0.0.0 = le serveur laravel est accessible en dehors du conteneur et depuis Windows également
CMD php artisan migrate:fresh --seed && php artisan serve --host=0.0.0.0 --port=8069


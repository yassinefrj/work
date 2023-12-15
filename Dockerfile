FROM php:8.2-fpm 

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nodejs \
    npm


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

#auto deploy
COPY .env.production.example .env

#copie tout mon projet laravel dans /www de l'image docker
COPY . .
RUN php artisan key:generate
RUN php artisan storage:link
RUN npm install 
RUN npm run build
RUN touch database/database.sqlite
RUN touch database/test.sqlite

#host 0.0.0.0 = le serveur laravel est accessible en dehors du conteneur et depuis Windows également
CMD php artisan migrate:fresh --seed && php artisan serve --host=0.0.0.0 --port=80


FROM php:8.3.7-fpm-alpine

RUN apk add --no-cache linux-headers bash git sudo openssh libxml2-dev oniguruma-dev autoconf gcc g++ make npm freetype-dev libjpeg-turbo-dev libpng-dev libzip-dev ssmtp icu-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install mbstring xml pcntl gd zip sockets pdo pdo_mysql bcmath soap intl
RUN docker-php-ext-enable mbstring xml gd zip pcntl sockets bcmath pdo pdo_mysql soap

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --ignore-platform-req=ext-http --no-scripts --no-interaction --prefer-dist
COPY .env .env
RUN chmod -R 775 /var/www/html
RUN chmod -R 777 /var/www/html/storage

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000

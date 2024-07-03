FROM php:8.0-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip pdo_mysql

RUN a2enmod rewrite

COPY ./backend /var/www/html

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

RUN composer require laravel/passport

RUN php artisan key:generate

RUN php artisan migrate --force

RUN php artisan passport:install

COPY ./backend/docker/apache2.conf /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]

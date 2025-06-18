FROM php:8.2-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev libonig-dev \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
&& docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install pdo pdo_pgsql zip mbstring gd \
&& pecl install xdebug \
&& docker-php-ext-enable xdebug \
&& apt-get clean \
&& rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY php.ini /usr/local/etc/php/php.ini
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /app
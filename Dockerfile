FROM php:7.4-fpm

ENV LANG="ja_JP.UTF-8" \
    HOME="/var/www/html/app"

# install composer
RUN cd /usr/bin && \
    curl -s http://getcomposer.org/installer | php && \
    ln -s /usr/bin/composer.phar /usr/bin/composer

RUN apt-get update && \
    apt-get install -y git zip unzip vim

RUN apt-get update && \
    apt-get install -y libpq-dev zlib1g-dev libzip-dev libfreetype6-dev libpng-dev libjpeg62-turbo-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install zip gd pdo_mysql pdo_pgsql

WORKDIR $HOME

# CMD [ 'php', 'artisan', 'serve', '--host', '0,0,0,0', '--port', '9000' ]

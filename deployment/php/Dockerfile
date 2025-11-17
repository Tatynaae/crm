FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libonig-dev curl && \
    docker-php-ext-install pdo_mysql zip

# Xdebug (optional)
ARG ENABLE_XDEBUG=false
RUN if [ "$ENABLE_XDEBUG" = "true" ] ; then \
    pecl install xdebug && docker-php-ext-enable xdebug ; \
    fi

WORKDIR /var/www/app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

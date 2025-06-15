FROM php:8.4-rc-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    libzip-dev \
    libxml2-dev \
     && docker-php-ext-install \
            pdo \
            pdo_mysql \
            zip \
            opcache \
            dom \
            simplexml

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html

USER www-data

COPY --chown=www-data:www-data . .

RUN composer install --no-interaction --no-dev --optimize-autoloader

CMD ["php", "bin/console", "list"]

FROM php:8.2.20-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends git libzip-dev zip
RUN docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD mkdir -p /app/storage \
  && touch -f /app/storage/timberhub.db3 \
  && chmod -R 0777 /app/storage/ \
  && chmod -R 0777 /app/storage/timberhub.db3 \
  && composer install \
  && composer dump-autoload -o \
  && php-fpm

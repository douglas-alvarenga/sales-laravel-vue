FROM node:lts AS vue-build

WORKDIR /app
COPY package.json package-lock.json vite.config.js /app/

RUN npm ci --legacy-peer-deps

COPY ./resources /app/resources

ENV CI=true
ENV PORT=3000
ARG VITE_API_URL
ENV VITE_API_URL ${VITE_API_URL:-http://localhost:8000/api}

RUN npm run build

FROM composer:2.8.1 AS build

COPY composer.json composer.lock artisan /app/

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apk del pcre-dev ${PHPIZE_DEPS} \
  && rm -rf /tmp/pear


# Em produção adicionar --no-dev, não coloquei para permitir executar `db:seed`
RUN composer install --prefer-dist --no-interaction --no-autoloader --ignore-platform-req=ext-sockets --ignore-platform-req=ext-gd
# RUN composer install --prefer-dist --no-interaction --no-dev --no-autoloader --ignore-platform-req=ext-sockets --ignore-platform-req=ext-gd

COPY . /app/

RUN composer dump-autoload -o

FROM php:8.2-apache-buster AS base

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev zip
RUN pecl install redis apcu && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install pdo pdo_mysql sockets pcntl zip bcmath \
    && docker-php-ext-enable opcache redis apcu

FROM base AS production

COPY docker/start.sh /usr/local/bin/start

COPY --from=build /app /var/www/html
COPY --from=vue-build /app/public/build /var/www/html/public/build
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN touch /var/www/html/storage/logs/laravel.log && \
    chmod 777 -R /var/www/html/storage/ && \
    chown -R www-data:www-data /var/www/ && \
    chmod +x /usr/local/bin/start && \
    a2enmod rewrite

CMD ["/usr/local/bin/start"]

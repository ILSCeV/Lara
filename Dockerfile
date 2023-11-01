FROM node:21-alpine@sha256:df76a9449df49785f89d517764012e3396b063ba3e746e8d88f36e9f332b1864 as node
COPY ./ /Lara
RUN cd /Lara && npm install && npm run prod

FROM docker.io/bitnami/git:latest@sha256:8b7d5df2ce869b200abc8ae34bedf4e9566e652c0d32ac81058ed87d8c2c75ce as gitstage
COPY --from=node /Lara /Lara
RUN cd /Lara && sh git-create-revisioninfo-hook.sh

FROM php:8.2.12-fpm@sha256:08275f36d2c2a4b1e12b29b87dfb8c04e2c42000aef2b16764714bdba99acd79
COPY --from=composer@sha256:fb3c5a283f2dc08e08841048498e8a82c3864648c84255b5ad7243b38d33a8db /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN apt-get update && apt-get install -y libmagickwand-dev libzip-dev libldap2-dev  --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install ldap
RUN printf "\n" | pecl install imagick
RUN docker-php-ext-enable imagick
RUN useradd -u 1001 -U serve
COPY --from=gitstage /Lara /Lara
RUN chown -R serve:serve /Lara
USER serve
RUN cd /Lara && composer install
WORKDIR /Lara
CMD ["/bin/bash", "-c", "set -e;sleep 5;php artisan migrate --force ; php artisan serve --host 0.0.0.0"]

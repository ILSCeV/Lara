FROM node:20-alpine@sha256:32427bc0620132b2d9e79e405a1b27944d992501a20417a7f407427cc4c2b672 as node
COPY ./ /Lara
RUN cd /Lara && npm install && npm run prod

FROM docker.io/bitnami/git:latest@sha256:8f92cadd2c82668333628d6feb3e0a4d71738fab317cbebd44e18f57845f62cf as gitstage
COPY --from=node /Lara /Lara
RUN cd /Lara && sh git-create-revisioninfo-hook.sh

FROM php:8.2.13-fpm@sha256:cee95954ea2f96bbd46d28ab4a4a2b01c64c81efa7a18a470d50fd4d0f97aa42
COPY --from=composer@sha256:67f1bec07666f688791bff2c13b34b9c35042cc4c1e42fbb5bd4dbe4ae70f0fb /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN apt-get update && apt-get install -y libmagickwand-dev libzip-dev libldap2-dev openssh-client --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install ldap
RUN printf "\n" | pecl install imagick
RUN docker-php-ext-enable imagick
RUN useradd -u 1001 -U serve && mkdir -p /home/serve && chown -R serve:serve /home/serve
COPY --from=gitstage /Lara /Lara
RUN chown -R serve:serve /Lara
USER serve
RUN cd /Lara && composer install
WORKDIR /Lara
CMD ["/bin/bash", "-c", "set -e;sleep 5;php artisan migrate --force ; php artisan serve --host 0.0.0.0"]

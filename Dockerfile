FROM node:20-alpine@sha256:df01469346db2bf1cfc1f7261aeab86b2960efa840fe2bd46d83ff339f463665 as node
COPY ./ /Lara
RUN cd /Lara && npm install && npm run prod

FROM docker.io/bitnami/git:latest@sha256:acdb90444ab88d32fd24ff868cc076793fa3322e93146c45b1bca987ce7b3c9d as gitstage
COPY --from=node /Lara /Lara
RUN cd /Lara && sh git-create-revisioninfo-hook.sh

FROM php:8.3.8-fpm@sha256:20a65a3f3919a048a6dedba1200d37706659ac5b23630142cb44e4548cc73250
COPY --from=composer@sha256:2df6a8c0e8cac0438b2492f104ed53c85816937c77beb72f6a50867d0af1e2e1 /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN apt-get update && apt-get install -y libmagickwand-dev libzip-dev libldap2-dev openssh-client telnet wget imagemagick libmagickcore-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install ldap

# renovate: datasource=github-tags depName=Imagick/imagick versioning=semver-coerced extractVersion=(?<version>.*)$
ARG IMAGICK_PECL_VERSION=3.7.0

RUN curl -L -o /tmp/imagick.tar.gz https://github.com/Imagick/imagick/archive/refs/tags/${IMAGICK_PECL_VERSION}.tar.gz \
    && tar --strip-components=1 -xf /tmp/imagick.tar.gz \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini \
    && rm -rf /tmp/* \
RUN docker-php-ext-enable imagick
RUN useradd -u 1001 -U serve && mkdir -p /home/serve && chown -R serve:serve /home/serve
COPY --from=gitstage /Lara /Lara
RUN chown -R serve:serve /Lara
USER serve
RUN cd /Lara && composer update
WORKDIR /Lara
CMD ["/bin/bash", "-c", "set -e;sleep 5;php artisan migrate --force ; php artisan serve --host 0.0.0.0"]

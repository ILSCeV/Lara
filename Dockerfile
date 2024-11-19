FROM node:20-alpine@sha256:b1e0880c3af955867bc2f1944b49d20187beb7afa3f30173e15a97149ab7f5f1 as node
COPY ./ /Lara
RUN cd /Lara && npm install && npm run prod

FROM docker.io/bitnami/git:latest@sha256:7e2639e339f07facd5c3160e0d41aa23e7dcbc383c5954c66357130aa83a1708 as gitstage
COPY --from=node /Lara /Lara
RUN cd /Lara && sh git-create-revisioninfo-hook.sh

FROM php:8.3.11-fpm@sha256:cf6ebfc2ca6f69886fb9c13e1dc9f189d441eb0569f2e492c315d2009d694850
COPY --from=composer@sha256:57000529b4609b66beeba3ebdd0ebb68b28be262c30669dfccb31003febb245a /usr/bin/composer /usr/bin/composer
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

FROM node:20-alpine@sha256:053c1d99e608fe9fa0db6821edd84276277c0a663cd181f4a3e59ee20f5f07ea as node
COPY ./ /Lara
RUN cd /Lara && npm install && npm run prod

FROM docker.io/bitnami/git:latest@sha256:a5cad9bf5eb09c0cf155c1b9928797a1ccc97a9ed5bcda8295c7463acf048cd1 as gitstage
COPY --from=node /Lara /Lara
RUN cd /Lara && sh git-create-revisioninfo-hook.sh

FROM php:8.3.17-fpm@sha256:c9a1d936a581c688901cd6f4480be920c0a2f475a90ad353445e69859f2906d1
COPY --from=composer@sha256:d702aa6a31321b7c2f7e4258334ec965c2813859a2db3617b8a9f746b44e42c2 /usr/bin/composer /usr/bin/composer
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

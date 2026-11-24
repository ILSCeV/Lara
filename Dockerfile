FROM node:20-alpine@sha256:fb4cd12c85ee03686f6af5362a0b0d56d50c58a04632e6c0fb8363f609372293 as node
COPY ./ /Lara
RUN cd /Lara && npm install && npm run prod

FROM docker.io/bitnami/git:latest@sha256:704fe89fb37feb90edc0838afd5d8cd43301f338bf1eed1eaf3a8fe9f0b288d1 as gitstage
COPY --from=node /Lara /Lara
RUN cd /Lara && sh git-create-revisioninfo-hook.sh

FROM php:8.5.0-fpm@sha256:baa4dd163e5c9e95c4ba31773173b408c69c824f6a21da56af274a47e7ddbee6
COPY --from=composer@sha256:50ea4d33fdcde1adb0f045547295faa1548b33872e5f789dae5b02ce2fb5de93 /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN apt-get update && apt-get install -y libmagickwand-dev libzip-dev libldap2-dev openssh-client telnet wget imagemagick libmagickcore-dev nginx-light gettext-base --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install ldap

# renovate: datasource=github-tags depName=Imagick/imagick versioning=semver-coerced extractVersion=(?<version>.*)$
ARG IMAGICK_PECL_VERSION=3.8.1

RUN curl -L -o /tmp/imagick.tar.gz https://github.com/Imagick/imagick/archive/refs/tags/${IMAGICK_PECL_VERSION}.tar.gz \
    && tar --strip-components=1 -xf /tmp/imagick.tar.gz \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini \
    && rm -rf /tmp/* \
RUN docker-php-ext-enable imagick

# php-fpm logs to stderr and inherits Docker env vars. The master runs as the
# non-root serve user, so the www-data user/group directives are dropped.
RUN sed -i '/^user = /d; /^group = /d; s/^;clear_env = no/clear_env = no/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's|^;error_log = log/php-fpm.log|error_log = /proc/self/fd/2|' /usr/local/etc/php-fpm.conf

RUN useradd -u 1001 -U serve && mkdir -p /home/serve/etc /home/serve/log/nginx /home/serve/run /home/serve/tmp/nginx && chown -R serve:serve /home/serve
COPY --from=gitstage /Lara /Lara
RUN chown -R serve:serve /Lara
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/nginx.conf.template /usr/local/etc/nginx/lara.conf.template
RUN chmod 0755 /usr/local/bin/entrypoint.sh && chown serve:serve /usr/local/etc/nginx/lara.conf.template
USER serve
RUN cd /Lara && composer update
WORKDIR /Lara
EXPOSE 8000
CMD ["/usr/local/bin/entrypoint.sh"]

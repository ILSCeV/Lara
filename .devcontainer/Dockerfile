FROM mcr.microsoft.com/devcontainers/php:1-8-bookworm@sha256:1a5dbd38ed7933ef577487e6ba5d05b902959ea1888d627b141877665dfa3a13
COPY --from=composer@sha256:1f88162f6d30fe394990edd298f181f66e8a4ba36d3e102213c2d4a8eb8654a5 /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN apt-get update && apt-get install -y libmagickwand-dev libzip-dev libldap2-dev openssh-client git --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install ldap
RUN printf "\n" | pecl install imagick
RUN docker-php-ext-enable imagick
COPY logs.conf /usr/local/etc/php-fpm.d/logs.conf


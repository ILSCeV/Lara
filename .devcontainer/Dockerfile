FROM mcr.microsoft.com/devcontainers/php:1-8-bookworm@sha256:a2b778ac4116c0b037caef0e35d1c703bb86924972859befc91a67e04a11ed67
COPY --from=composer@sha256:67f1bec07666f688791bff2c13b34b9c35042cc4c1e42fbb5bd4dbe4ae70f0fb /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN apt-get update && apt-get install -y libmagickwand-dev libzip-dev libldap2-dev openssh-client git --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install ldap
RUN printf "\n" | pecl install imagick
RUN docker-php-ext-enable imagick
COPY logs.conf /usr/local/etc/php-fpm.d/logs.conf


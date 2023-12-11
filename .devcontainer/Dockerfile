FROM mcr.microsoft.com/devcontainers/php:1-8-bookworm@sha256:1102ca7f19b8f148185406ad264c4a464fa7e15bb85a95c83d6205db35bb0772
COPY --from=composer@sha256:a9f955c05e7253c9364f0c7ac8aebd2b64c2df38e171a27015e8cfe391746dc8 /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN apt-get update && apt-get install -y libmagickwand-dev libzip-dev libldap2-dev openssh-client git --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install ldap
RUN printf "\n" | pecl install imagick
RUN docker-php-ext-enable imagick
COPY logs.conf /usr/local/etc/php-fpm.d/logs.conf


# Image for the standalone end-to-end test server (public/). It needs PHP 7.2.5+
# (Symfony 5.4) with the mbstring extension the library requires; openssl ships
# with the base image. The app code and its vendor dir are mounted at runtime,
# so this image only provides the PHP runtime, the extensions and composer.
FROM php:7.2-cli

ENV COMPOSER_ALLOW_SUPERUSER=1

# php:7.2-cli is based on Debian buster, which is EOL — its apt mirrors moved to
# archive.debian.org, so repoint sources there (and skip the expired-release check).
RUN sed -i -e 's|http://deb.debian.org/debian|http://archive.debian.org/debian|g' \
           -e '/buster-updates/d' \
           -e '/security.debian.org/d' /etc/apt/sources.list \
    && apt-get -o Acquire::Check-Valid-Until=false update \
    && apt-get install -y --no-install-recommends git unzip libonig-dev \
    && docker-php-ext-install mbstring \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app/public

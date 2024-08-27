FROM php:8.2-fpm-alpine AS build

# Build dependencies and PHP extensions
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    openssl-dev \
    libaio \
    zlib-dev \
    oniguruma-dev \
    unixodbc-dev \
    freetds-dev \
    postgresql-dev \
    autoconf \
    build-base \
    linux-headers \
    pkgconfig \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets pdo_pgsql \
    && mkdir -p /usr/src/php/ext/pdo_dblib \
    && curl -L http://pecl.php.net/get/PDO_DBLIB-1.0.tgz | tar xvz -C /usr/src/php/ext/pdo_dblib --strip 1 \
    && docker-php-ext-install pdo_dblib \
    && pecl install -o -f redis \
    && docker-php-ext-enable redis \
    && apk del autoconf build-base linux-headers pkgconfig

# Copy only necessary files to reduce image size
FROM php:8.2-fpm-alpine

COPY --from=build /usr/local /usr/local
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Rruntime dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    openssl-dev \
    libaio \
    zlib-dev \
    oniguruma-dev \
    unixodbc-dev \
    freetds \
    postgresql-dev \
    nodejs \
    npm \
    && composer global require --no-cache "laravel/installer" \
    && export PATH=~/.composer/vendor/bin:$PATH \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www

COPY ../package.json ./
RUN npm install

USER $user

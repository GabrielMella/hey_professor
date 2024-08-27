FROM php:8.1.0-fpm

COPY 90-xdebug.ini "${PHP_INI_DIR}/conf.d"
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
	libssl-dev \
	libaio1 \
	wget \
	libldap-dev \
    zlib1g-dev \
	procps \
	python3 \
	python3-pip \
	supervisor \
	vim

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs


# Install PHP extensions
RUN docker-php-ext-configure ldap
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets

RUN apt-get update; \
    apt-get install -y libpq5 libpq-dev; \
    docker-php-ext-install pdo pdo_pgsql; \
    apt-get autoremove --purge -y libpq-dev; \
    apt-get clean ; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install extensions MongoDB
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb


# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer global require "laravel/installer" \
	&& export PATH=~/.composer/vendor/bin:$PATH

# Install redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

# Set working directory
WORKDIR /var/www

USER $user

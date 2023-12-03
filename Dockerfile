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

# Install PHP extensions
RUN docker-php-ext-configure ldap
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets ldap

RUN apt-get update; \
    apt-get install -y libpq5 libpq-dev; \
    docker-php-ext-install pdo pdo_pgsql; \
    apt-get autoremove --purge -y libpq-dev; \
    apt-get clean ; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install Oracle instantclient and oci8 extension
RUN mkdir /opt/oracle \
    && curl 'https://download.oracle.com/otn_software/linux/instantclient/19600/instantclient-basic-linux.x64-19.6.0.0.0dbru.zip' --output /opt/oracle/instantclient-basic-linux.zip \
    && curl 'https://download.oracle.com/otn_software/linux/instantclient/19600/instantclient-sdk-linux.x64-19.6.0.0.0dbru.zip' --output /opt/oracle/instantclient-sdk-linux.zip \
    && unzip '/opt/oracle/instantclient-basic-linux.zip' -d /opt/oracle \
    && unzip '/opt/oracle/instantclient-sdk-linux.zip' -d /opt/oracle \
    && rm /opt/oracle/instantclient-*.zip \
    && mv /opt/oracle/instantclient_* /opt/oracle/instantclient \
    && docker-php-ext-configure oci8 --with-oci8=instantclient,/opt/oracle/instantclient \
    && docker-php-ext-install oci8 \
	&& docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient \
    && docker-php-ext-install pdo_oci \
    && echo /opt/oracle/instantclient/ > /etc/ld.so.conf.d/oracle-insantclient.conf \
    && ldconfig

ENV LD_LIBRARY_PATH  /opt/oracle/instantclient:${LD_LIBRARY_PATH}

# Install Oracle extensions
#RUN echo 'instantclient,/opt/oracle/instantclient' | pecl install oci8-2.2.0 \
#      && docker-php-ext-enable \
#               oci8 \
#      && docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient \
#       && docker-php-ext-install pdo_oci

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

FROM php:8.2-fpm

WORKDIR "/home/phplrt/phplrt.org"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# ------------------------------------------------------------------------------
# Install Dependencies
# ------------------------------------------------------------------------------

RUN apt-get update \
    && pecl channel-update pecl.php.net \
    && apt-get -y --no-install-recommends install \
      libzip-dev \
      libicu-dev \
      libpq-dev \
      unzip \
      git \
      curl

# ------------------------------------------------------------------------------
# Install PHP
# ------------------------------------------------------------------------------

# 1. Composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

# 2. APCU
RUN pecl install apcu && \
    docker-php-ext-enable apcu

# 3. Intl
RUN docker-php-ext-install intl && \
    docker-php-ext-enable intl

# 5. Zip
RUN docker-php-ext-install zip && \
    docker-php-ext-enable zip

# 6. Igbinary
RUN pecl install igbinary && \
    docker-php-ext-enable igbinary

# 7. Opcache
RUN docker-php-ext-install opcache

# 8. Sockets
RUN docker-php-ext-install sockets

# 9. PDO PostgreSQL
RUN docker-php-ext-install pdo_pgsql && \
    docker-php-ext-enable pdo_pgsql

# 10. Xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# ------------------------------------------------------------------------------
# Cleanup Dependencies
# ------------------------------------------------------------------------------

RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && ln -sf /usr/share/zoneinfo/Etc/UTC /etc/localtime

RUN useradd -u 1000 -m phplrt
USER phplrt

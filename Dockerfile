FROM php:8.2-apache

# System dependencies
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        unzip \
        git \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        intl \
        exif \
        bcmath \
        opcache

# Apache
RUN a2enmod rewrite headers

# Change default port 80 to 8080 for frontend; add 8090 for backend
RUN sed -i 's/Listen 80$/Listen 8080/' /etc/apache2/ports.conf \
    && echo "Listen 8090" >> /etc/apache2/ports.conf

# Virtual hosts
COPY docker/apache/frontend.conf /etc/apache2/sites-available/frontend.conf
COPY docker/apache/backend.conf  /etc/apache2/sites-available/backend.conf

RUN a2dissite 000-default.conf \
    && a2ensite frontend.conf \
    && a2ensite backend.conf

# PHP ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Working directory
WORKDIR /var/www/html/goldmember

EXPOSE 8080 8090



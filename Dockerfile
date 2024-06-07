FROM php:7.3-apache
# Update and install necessary packages
RUN apt-get update && apt-get upgrade -y

# Install required extensions
RUN apt-get install -y libzip-dev unzip
RUN docker-php-ext-install zip mysqli json && docker-php-ext-enable zip mysqli json

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# run composer install
COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist

COPY . .
RUN composer dump-autoload

# Cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache modules if needed (optional)
# RUN a2enmod rewrite

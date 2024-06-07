FROM php:7.3-apache

# Update and install necessary packages
RUN apt-get update && apt-get upgrade -y

# Install required extensions
RUN apt-get install -y libzip-dev unzip \
    && docker-php-ext-install zip mysqli json \
    && docker-php-ext-enable zip mysqli json

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Run Composer install
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist \
    && composer dump-autoload

# Cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

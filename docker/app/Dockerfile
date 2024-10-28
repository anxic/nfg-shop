# Use PHP 8.3 FPM as the base image
FROM php:8.3-fpm

# Install necessary system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Install Symfony CLI to create a new project
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Expose the default PHP-FPM port
EXPOSE 9000

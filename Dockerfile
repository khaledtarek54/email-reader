# Base image with PHP 8.3 and FPM
FROM php:8.3-fpm

LABEL maintainer="Khaled tarek"

# Set the working directory
WORKDIR /var/www/html

# Install production dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    nginx \
    && docker-php-ext-install pdo_mysql gd mbstring exif pcntl bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install application dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy the Nginx configuration file
COPY ./nginx/default.conf /etc/nginx/sites-available/default

# Expose port 80 for Nginx
EXPOSE 80

# Start PHP-FPM and Nginx
CMD service nginx start && php-fpm

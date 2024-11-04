# Use the official PHP-FPM Alpine image as the base image
FROM php:8.3-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apk --no-cache add \
    git \
    curl \
    curl-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    icu-dev \
    mysql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd xml zip curl intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents and set permissions
COPY . /var/www
RUN chown -R www-data:www-data \
    /var/www/storage \
    /var/www/bootstrap/cache
RUN chmod -R ug+rwx \
    /var/www/storage \
    /var/www/bootstrap/cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# Default command
CMD ["php-fpm"]
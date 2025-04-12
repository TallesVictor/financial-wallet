# Uses the official PHP image with required extensions
FROM php:8.2-fpm

# Installs system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install and enable Redis
RUN pecl install redis && docker-php-ext-enable redis

# Define the working directory
WORKDIR /var/www

# Copy Laravel files to the container
COPY . .

# Grant write access to the storage and bootstrap/cache directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exposes port 9000 for communication with Nginx
EXPOSE 9000

CMD ["php-fpm"]

# ----- 1️⃣ Base PHP image -----------------------------------------------------
FROM php:8.2-fpm

# ----- 2️⃣ System packages ----------------------------------------------------
RUN apt-get update && apt-get install -y \
    git curl zip unzip nano mariadb-client \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev libzip-dev \
 && docker-php-ext-install pdo pdo_mysql mbstring zip gd exif pcntl xml \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# ----- 3️⃣ Install Composer ---------------------------------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ----- 4️⃣ Set workdir --------------------------------------------------------
WORKDIR /var/www

# ----- 5️⃣ Copy composer files & install deps --------------------------------
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ----- 6️⃣ Copy the rest of the app ------------------------------------------
COPY . .

# ----- 7️⃣ Set file permissions (storage, cache, logs) ------------------------
RUN chown -R www-data:www-data /var/www \
 && chmod -R 755 storage bootstrap/cache

# ----- 8️⃣ Expose + start Laravel on port 8080 --------------------------------
EXPOSE 8080
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
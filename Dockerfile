# Stage 1: Install PHP dependencies (Composer)
FROM composer:latest AS composer-stage
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --ignore-platform-reqs --no-interaction --no-scripts --prefer-dist

# Stage 2: Build front-end assets (Vue + Vite)
FROM node:20-alpine AS frontend-stage
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
# Copy vendor folder from composer stage so Vite can resolve Ziggy
COPY --from=composer-stage /app/vendor ./vendor
RUN npm run build

# Stage 3: Production PHP and Web Server (Nginx)
FROM richarvey/nginx-php-fpm:latest
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy composer dependencies from Stage 1
COPY --from=composer-stage /app/vendor ./vendor

# Copy built frontend assets from Stage 2
COPY --from=frontend-stage /app/public/build ./public/build

# Set up permissions for storage and bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Environment configurations for the image
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV SKIP_COMPOSER 1

# Expose HTTP port
EXPOSE 80

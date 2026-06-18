# Stage 1: Build front-end assets (Vue + Vite)
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Production PHP and Web Server (Nginx)
FROM richarvey/nginx-php-fpm:latest
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy built frontend assets
COPY --from=frontend /app/public/build ./public/build

# Install PHP production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

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

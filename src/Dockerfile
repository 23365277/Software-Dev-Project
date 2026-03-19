FROM php:8.2-apache

# Install mysqli and pdo_mysql extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your PHP app
COPY . /var/www/html/

# Optional: set proper permissions
RUN chown -R www-data:www-data /var/www/html

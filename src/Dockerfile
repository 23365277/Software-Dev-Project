FROM php:8.2-apache

# Install mysqli and pdo_mysql extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your PHP app
COPY . /var/www/html/

# Optional: set proper permissions
RUN chown -R www-data:www-data /var/www/html

RUN echo "upload_max_filesize=20M" >> /usr/local/etc/php/conf.d/uploads.ini

RUN echo "post_max_size=25M" >> /usr/local/etc/php/conf.d/uploads.ini

RUN sed -i 's/User ${APACHE_RUN_USER}/User root/' /etc/apache2/apache2.conf \
 && sed -i 's/Group ${APACHE_RUN_GROUP}/Group root/' /etc/apache2/apache2.conf

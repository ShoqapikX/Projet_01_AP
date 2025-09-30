FROM php:8.2-apache

# Install MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy source code to Apache document root
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Update Apache configuration
RUN echo "<Directory /var/www/html>\n    AllowOverride All\n    Require all granted\n</Directory>" >> /etc/apache2/apache2.conf
RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf

EXPOSE 80
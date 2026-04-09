# Use official PHP with Apache
FROM php:8.2-apache

# Install mysqli extension (required for MySQL)
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite (good practice)
RUN a2enmod rewrite

# Copy project files to Apache directory
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 8001
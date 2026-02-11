# Use PHP 8.3 with FPM
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies for PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions
RUN docker-php-ext-install \
    pgsql \
    pdo \
    pdo_pgsql

# Set PHP configuration
RUN echo "file_uploads = On" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini

# Copy application files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Expose port 9000 for FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

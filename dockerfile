# Use an official PHP image as the base image
FROM php:7.2-fpm

# Set environment variables for the WordPress installation
ENV WORDPRESS_VERSION 6.1.1
ENV WORDPRESS_SHA1 3ba98d7a66c7908715f5dd9e2610af13a33c95e1

# Install required dependencies
RUN apt-get update && apt-get install -y \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libzip-dev \
  && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
  && docker-php-ext-install -j$(nproc) gd mysqli zip \
  && pecl install redis \
  && docker-php-ext-enable redis

# Download and extract the WordPress files
RUN set -ex; \
  curl -o wordpress.tar.gz -fSL "https://wordpress.org/wordpress-${WORDPRESS_VERSION}.tar.gz"; \
  echo "$WORDPRESS_SHA1 *wordpress.tar.gz" | sha1sum -c -; \
  tar -xzf wordpress.tar.gz -C /usr/src/; \
  rm wordpress.tar.gz; \
  chown -R www-data:www-data /usr/src/wordpress

# Copy the WordPress configuration file
COPY wp-config.php /usr/src/wordpress/wp-config.php

# Set the working directory to the WordPress files
WORKDIR /usr/src/wordpress

# Start the PHP-FPM service
CMD ["php-fpm"]

FROM php:8.1.0-fpm

ARG USER_ID=1000
ARG GROUP_ID=1000

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update
RUN apt-get install -y msmtp

# Install extensions
RUN install-php-extensions xdebug gd pdo_mysql mbstring zip soap sockets redis

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

USER "${USER_ID}:${GROUP_ID}"

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
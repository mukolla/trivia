FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libzip-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    zlib1g-dev \
    libonig-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY --chown=www:www . /var/www

# Copy docker-php-entrypoint script
COPY ./docker-php-entrypoint /usr/local/bin/

# Set execute permission on docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

RUN chown www:www /usr/local/bin/docker-php-entrypoint

# Copy existing application directory permissions
RUN chown www:www /var/www

RUN mkdir -p /var/share/html/public
RUN rm -rf /var/share/html/public/*
RUN cp -r /var/www/public/* /var/share/html/public
RUN chown www:www /var/share/html/public

RUN composer install

USER www

ENTRYPOINT ["docker-php-entrypoint"]

EXPOSE 9000
CMD ["php-fpm"]

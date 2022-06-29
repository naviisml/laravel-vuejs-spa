FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
	libzip-dev \
	libz-dev \
	libzip-dev \
	libpq-dev \
	libjpeg-dev \
	libpng-dev \
	libfreetype6-dev \
	libssl-dev \
	openssh-server \
	git \
	libxml2-dev \
	libreadline-dev \
	libgmp-dev \
	unzip

# Install nodejs
RUN apt-get install -y nodejs npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install soap exif pcntl zip pdo_mysql pdo_pgsql bcmath intl gmp

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents to the working directory
COPY . /var/www

# Assign permissions of the working directory to the www-data user
RUN chown -R www-data:www-data \
        /var/www/storage \
        /var/www/bootstrap/cache

# Set working directory
WORKDIR /var/www

# Install npm dependencies
RUN npm install

# Run npm script
RUN npm run prod

# Install composer packages
RUN composer install

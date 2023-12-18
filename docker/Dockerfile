FROM php:8.1.1-apache

RUN apt-get update --fix-missing
RUN apt-get install -y libzip-dev zip cron libpng-dev libwebp-dev libjpeg-dev libfreetype6-dev libicu-dev
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
RUN docker-php-ext-configure intl
RUN docker-php-ext-install mysqli pdo pdo_mysql zip intl
RUN docker-php-ext-install gd

# Copy crontab
COPY ./docker/crontab /etc/cron.d/crontab
RUN chmod 0644 /etc/cron.d/crontab

# Apply cron job
RUN crontab /etc/cron.d/crontab
RUN touch /var/log/cron.log

# Create required directories
RUN mkdir -p /var/www/html/log /var/www/html/var/log /var/www/html/var/storage/geography/composed /var/www/html/var/storage/statistic/generated
RUN chmod -R 777 /var/www/html/log /var/www/html/var/log /var/www/html/var/storage/geography/composed /var/www/html/var/storage/statistic/generated

COPY  ${project_root}/docker/conf/000-default.conf /etc/apache2/sites-enabled/
RUN a2enmod rewrite
RUN a2enmod headers

CMD cron && /usr/sbin/apache2ctl -D FOREGROUND

# COPY composer.phar
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Composer install
COPY composer.json ./
RUN composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

# Install composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer


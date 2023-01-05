#FROM php:8.0-apache
#
#ARG project_root=.
#
## Install all required tools first:
## - wget for installation of other tools
## - gnupg2 and g++ for gd extension
## - GIT for computing diffs and for npm to download packages
## - unzip to ommit composer zip packages corruption
#RUN apt-get update && apt-get install -y wget gnupg2 unzip git apt-utils --no-install-recommends && apt-get clean
#
## Install Node JS
#RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -
#RUN apt-get update && apt-get install -y nodejs && apt-get clean
#
## Install composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
#
#RUN apt-get update -y && apt-get install -y \
#	libzip-dev \
#	libicu-dev \
#	libonig-dev \
#	libxml2-dev \
#	libfreetype6-dev \
#	libjpeg62-turbo-dev \
#	libwebp-dev \
#	libc-client-dev \
#	libkrb5-dev \
#	libpq-dev \
#	mc \
#	nano \
#	openssh-client && \
#	apt-get clean
#
#RUN docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg
#RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl
#
#RUN docker-php-ext-install \
#    bcmath \
#    exif \
#    fileinfo \
#    gd \
#    imap \
#    intl \
#    mbstring \
#    opcache \
#    mysql \
#    pdo \
#    pdo_mysql \
#    sockets \
#    xml \
#    zip
#
#RUN docker-php-ext-install pdo_mysql
#
## Install XDebug
#RUN yes | pecl install xdebug \
#    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.start_with_request=no" >> /usr/local/etc/php/conf.d/xdebug.ini
#
#
## Copy php.ini
#COPY  ${project_root}/docker/conf/php.ini /usr/local/etc/php/
#COPY  ${project_root}/docker/conf/000-default.conf /etc/apache2/sites-enabled/
#
## Enable mod_rewrite & mod_headers
#RUN a2enmod rewrite
#RUN a2enmod headers

FROM php:8.1.1-apache

RUN apt-get update
RUN apt-get install -y libzip-dev zip cron libpng-dev libwebp-dev libjpeg-dev libfreetype6-dev
RUN docker-php-ext-install mysqli pdo pdo_mysql zip
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
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

# Doctrine migrations
#RUN php bin/console doctrine:migrations:migrate --no-interaction
#
#RUN php bin/console doctrine:fixtures:load --no-interaction
#
#RUN php bin/console app:zotero:load --no-interaction

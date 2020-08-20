FROM php:7.4-fpm

# Install modules
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
        libzip-dev \
        wget \
        unzip \
        git \
            --no-install-recommends

RUN docker-php-ext-install zip intl pdo_mysql exif \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

RUN pecl install -o -f xdebug \
    && rm -rf /tmp/pear

## Add the wait script to the image https://github.com/ufoscout/docker-compose-wait/releases/download/2.7.3/wait
COPY ./wait /usr/bin/wait
RUN chmod +x /usr/bin/wait

ENV COMPOSER_MEMORY_LIMIT -1
COPY ./install-composer.sh /

RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && sh /install-composer.sh \
    && rm /install-composer.sh

WORKDIR /var/www

CMD composer install ; /usr/bin/wait && php bin/console doctrine:migrations:migrate -n ; php-fpm

EXPOSE 9000

FROM php:8.2-fpm AS runtime

ARG USER_UID=33
ARG USER_GID=33

RUN apt-get update --fix-missing \
    && apt-get upgrade -y \
    && apt-get install -y unzip curl libicu-dev libpng-dev libzip-dev \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/*

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

ADD .docker/services/bin /usr/local/bin/

# extensions
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions @composer-2 xdebug mongodb

# working directory ownership
RUN chown -R www-data:www-data /var/www

# sync www-data uid/gid with host user
RUN /usr/local/bin/sync-user-id.sh www-data "$USER_UID" "$USER_GID"

ADD .docker/services/php/config/php-fpm-override.ini /usr/local/etc/php-fpm.d/zzz-docker.conf
ADD .docker/services/php/config/php-common-override.ini "$PHP_INI_DIR"/conf.d/

RUN install -o www-data -g www-data -d /var/www/.composer
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
ENV PATH="$PATH":/var/www/html/vendor/bin

USER www-data

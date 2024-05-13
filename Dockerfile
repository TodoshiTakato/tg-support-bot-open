FROM php:8.3.7-fpm-alpine3.18

ARG USER=1000
ARG UID=1000

ADD --chmod=0755 'https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions' '/usr/local/bin/'
RUN install-php-extensions pdo_mysql
RUN install-php-extensions @composer-2.7.6
RUN apk add --update --no-cache curl git
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN adduser --disabled-password --ingroup www-data --home /home/tg-support-bot-open --uid $UID  $USER
RUN addgroup $USER root
RUN mkdir -p /home/tg-support-bot-open/.composer
WORKDIR /home/tg-support-bot-open
COPY . .
RUN chown -R $USER:$USER /home/tg-support-bot-open

USER $USER
RUN composer install
CMD php artisan serve --host=0.0.0.0 --port=80

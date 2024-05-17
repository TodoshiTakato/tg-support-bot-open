FROM php:8.3.7-fpm-alpine3.18

ARG USER=1000
ARG UID=1000

ADD --chmod=0755 'https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions' '/usr/local/bin/'
RUN install-php-extensions pdo_mysql
RUN install-php-extensions @composer-2.7.6
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Configure PHP-FPM
COPY config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY config/php.ini /etc/php7/conf.d/custom.ini

- ./:/home/paymart-bot/www
- ./docker/php-fpm/php.ini:/usr/local/etc/php/php.ini
- ./docker/php-fpm/www-bot.conf:/usr/local/etc/php-fpm.d/www2.conf


# Install packages
RUN apk add --update --no-cache curl git nginx

RUN adduser -D -g 'www' www
RUN mkdir /www
RUN chown -R www:www /var/lib/nginx
RUN chown -R www:www /www

#backup of original nginx.conf
RUN mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.orig

# Remove default server definition
RUN rm /etc/nginx/conf.d/default.conf
RUN rm /usr/local/etc/nginx/nginx.conf

# Configure nginx
COPY ./docker/nginx/bot.conf /etc/nginx/nginx.conf


# Expose the port nginx is reachable on
EXPOSE 8080

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping



RUN adduser --disabled-password --ingroup www-data --home /home/tg-support-bot-open --uid $UID  $USER
RUN addgroup $USER root
RUN mkdir -p /home/tg-support-bot-open/.composer
WORKDIR /home/tg-support-bot-open
COPY . .
RUN chown -R $USER:$USER /home/tg-support-bot-open

USER $USER
#RUN composer install
#CMD php artisan serve --host=0.0.0.0 --port=80

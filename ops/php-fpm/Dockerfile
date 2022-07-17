FROM php:8.1-fpm

ARG UID

# Create user and some useful stuff
RUN adduser -u ${UID} --disabled-password --gecos "" appuser
RUN mkdir /home/appuser/.ssh
RUN chown -R appuser:appuser /home/appuser/
RUN echo "StrictHostKeyChecking no" >> /home/appuser/.ssh/config
RUN echo "export COLUMNS=300" >> /home/appuser/.bashrc
RUN echo "alias sf=/appdata/www/bin/console" >> /home/appuser/.bashrc

RUN apt-get update && docker-php-ext-install mysqli pdo pdo_mysql sockets bcmath

RUN apt-get update \
    && apt-get install -y \
            librabbitmq-dev \
            libssh-dev \
            libmagickwand-dev --no-install-recommends \
    && docker-php-ext-install bcmath sockets \
    && pecl install -o -f redis amqp imagick\
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis amqp imagick \
    && apt -y install curl git unzip \
    && curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer
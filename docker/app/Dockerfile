FROM php:8-fpm
# PHP で必要なライブラリをインストール
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update \
    && apt-get install -y libonig-dev libzip-dev unzip \
    && docker-php-ext-install mbstring zip bcmath \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug
RUN rm -fr /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 環境設定
RUN echo "umask u=rxw,g=rx,o=" >> /etc/bash.bashrc
RUN touch /var/log/xdebug.log && chown nobody. /var/log/xdebug.log
WORKDIR /var/www/html
USER nobody
ENV PATH $PATH:/var/www/html
COPY --chown=nobody:nogroup docker/app/php.ini /usr/local/etc/php/php.ini
COPY --chown=nobody:nogroup docker/app/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# composer パッケージのインストール
COPY --chown=nobody:nogroup src/composer.json .
RUN composer install

# app のインストール
COPY --chown=nobody:nogroup src .

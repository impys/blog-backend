FROM php:7.4.7-apache

WORKDIR /srv

RUN docker-php-ext-install pdo_mysql \
    && apt-get update \
    && apt-get -y install vim \
    && a2enmod rewrite

FROM composer as builder

WORKDIR /srv

COPY ./ /srv

ARG SSH_PRIVATE_KEY

ARG TOKEN_FOR_COMPOSER

RUN mkdir /root/.ssh/ \
    && echo ${SSH_PRIVATE_KEY} > /root/.ssh/id_rsa \
    && chmod 600 /root/.ssh/id_rsa \
    && touch /root/.ssh/known_hosts \
    && ssh-keyscan github.com >> /root/.ssh/known_hosts \
    && composer config -g github-oauth.github.com ${TOKEN_FOR_COMPOSER} \
    && composer install --ignore-platform-reqs

FROM php:7.4.4-apache

WORKDIR /srv

RUN docker-php-ext-install pdo_mysql

RUN chown -R www-data:www-data /srv/storage

COPY --from=builder /srv /srv

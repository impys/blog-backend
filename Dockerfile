FROM composer as builder

WORKDIR /app

COPY ./ /app

ARG SSH_PRIVATE_KEY

ARG TOKEN_FOR_COMPOSER

RUN mkdir /root/.ssh/ \
    && echo ${SSH_PRIVATE_KEY} > /root/.ssh/id_rsa \
    && chmod 600 /root/.ssh/id_rsa \
    && touch /root/.ssh/known_hosts \
    && ssh-keyscan github.com >> /root/.ssh/known_hosts \
    && composer config -g github-oauth.github.com ${TOKEN_FOR_COMPOSER} \
    && composer install --ignore-platform-reqs

FROM php:7.4-fpm-alpine

RUN docker-php-ext-install pdo_mysql

WORKDIR /app

COPY --from=builder /app /app

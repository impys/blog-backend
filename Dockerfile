FROM composer as builder

WORKDIR /app

COPY ./ /app

ARG GITHUB_TOKEN

RUN composer config -g github-oauth.github.com ${GITHUB_TOKEN} \
    && composer install --ignore-platform-reqs

FROM php:7.4-fpm-alpine

WORKDIR /app

COPY --from=0 ./ /app

CMD ["php-fpm"]

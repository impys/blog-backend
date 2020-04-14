FROM composer as builder

WORKDIR /app

COPY ./ /app

RUN composer install --ignore-platform-reqs

FROM php:7.4-fpm-alpine

WORKDIR /app

COPY --from=0 ./ /app

CMD ["php-fpm"]

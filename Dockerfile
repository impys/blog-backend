FROM php:7.4-fpm-alpine

WORKDIR /app

COPY ./ /app

RUN composer install --ignore-platform-reqs

CMD ["php-fpm"]

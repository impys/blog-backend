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
    && composer install --ignore-platform-reqs --no-dev

FROM php:7.4.4-apache

WORKDIR /srv

COPY --from=builder /srv /srv

RUN docker-php-ext-install pdo_mysql \
    && chown -R www-data:www-data /srv/storage \
    && cp ./run.sh /usr/local/bin/run \
    && chmod u+x /usr/local/bin/run \
    && apt-get update \
    && apt-get -y install vim \
    && a2enmod rewrite

CMD ["/usr/local/bin/run"]

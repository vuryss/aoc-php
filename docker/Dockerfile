FROM php:8.4-cli-alpine3.20

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    @composer \
    bcmath \
    ds \
    gmp \
    opcache \
    xdebug

COPY --link docker/php.ini /usr/local/etc/php/conf.d/z-php.ini

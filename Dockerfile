FROM php:8.3-cli-alpine3.20

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    @composer \
    bcmath \
    ds \
    gmp \
    opcache \
    xdebug

RUN echo 'xdebug.client_host = host.docker.internal' >> /usr/local/etc/php/conf.d/z-xdebug.ini && \
    echo 'memory_limit = 16G' >> /usr/local/etc/php/conf.d/z-memory-limit.ini

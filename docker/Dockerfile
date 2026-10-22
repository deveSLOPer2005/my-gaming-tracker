FROM php:8.2-fpm

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/install-php-extensions

RUN chmod +x /usr/bin/install-php-extensions

RUN install-php-extensions sqlsrv pdo_sqlsrv

WORKDIR /var/www

FROM php:8.1-fpm-alpine

LABEL name="phplib-naming-convention" \
      vendor="Intellex" \
	  version="1.0.0"

# Install bash
RUN apk add --no-cache --update bash

# Enable xdebug
RUN apk add --no-cache autoconf g++ make linux-headers
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

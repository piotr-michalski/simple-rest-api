FROM php:8.4.6-fpm AS php_base
RUN groupadd -g 1000 app && useradd -u 1000 -g app -m app
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zlib1g-dev \
    libxml2-dev \
    git \
    curl \
    unzip \
    libzip-dev \
    librabbitmq-dev \
    && docker-php-ext-install opcache pdo pdo_mysql zip \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.8.5
RUN mkdir -p /var/www/html/app

COPY ./php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
RUN echo "date.timezone = Europe/Warsaw" > /usr/local/etc/php/conf.d/timezone.ini
RUN chown -R app:app /usr/local/etc /usr/local/var/log/
COPY . /var/www/html/app
RUN chown -R app:app /var/www/html/app
RUN mkdir -p -m 700 /var/run/php && chown -R app:app /var/run/php

WORKDIR /var/www/html/app

FROM php_base AS php_prod
USER app
CMD ["php-fpm"]

FROM php_base AS php_dev
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

USER app
CMD ["php-fpm"]

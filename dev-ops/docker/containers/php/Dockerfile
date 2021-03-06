FROM php:7.0-fpm-alpine as builder

RUN apk add --update \
    libcurl \
    curl \
    curl-dev \
    icu-dev \
    git \
    zip \
    unzip \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libmcrypt \
    libmcrypt-dev \
    $PHPIZE_DEPS

RUN apk add --no-cache --repository http://dl-3.alpinelinux.org/alpine/edge/testing gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) \
    gd zip curl intl pdo pdo_mysql iconv mcrypt mbstring opcache

RUN pecl install -o -f apcu && docker-php-ext-enable apcu
RUN pecl install -o -f xdebug && docker-php-ext-enable xdebug
RUN pecl install -o -f redis && docker-php-ext-enable redis
RUN rm -rf /tmp/pear

RUN mkdir /tmp/ioncube && \
    mkdir -p /usr/local/ioncube && \
    cd /tmp/ioncube && \
    curl -SL http://downloads3.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz -o /tmp/ioncube/ioncube_loaders_lin_x86-64.tar.gz && \
    tar xvf /tmp/ioncube/ioncube_loaders_lin_x86-64.tar.gz && \
    cd `php -i | grep extension_dir | cut -d' ' -f 5` && \
    cp /tmp/ioncube/ioncube/ioncube_loader_lin_7.0.so /usr/local/ioncube/ioncube_loader_lin_7.0.so && \
    echo zend_extension=/usr/local/ioncube/ioncube_loader_lin_7.0.so > /usr/local/etc/php/conf.d/00-ioncube.ini && \
    rm -rf /tmp/ioncube/

COPY php.ini /usr/local/etc/php/
COPY zzz-shopware.conf /usr/local/etc/php-fpm.d/
COPY shopware.ini /usr/local/etc/php/conf.d/shopware.ini

WORKDIR /var/www/app

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin/ --filename=composer
RUN php -r "unlink('composer-setup.php');"

#ARG SW_VERSION=latest
#RUN if [ "${SW_VERSION}" != "latest" ] ; then git checkout "${SW_VERSION}" ; fi

RUN wget -O /test_images.zip http://releases.s3.shopware.com.s3-eu-west-1.amazonaws.com/test_images.zip

COPY config.php /

COPY entrypoint wait-for /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint /usr/local/bin/wait-for

EXPOSE 9000
CMD ["entrypoint"]
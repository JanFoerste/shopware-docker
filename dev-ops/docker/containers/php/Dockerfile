FROM php:7.0-fpm-alpine

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

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) \
    gd zip curl intl pdo pdo_mysql iconv mcrypt mbstring opcache

RUN pecl install -o -f apcu && docker-php-ext-enable apcu
RUN pecl install -o -f xdebug && docker-php-ext-enable xdebug
RUN pecl install redis && docker-php-ext-enable redis
RUN rm -rf /tmp/pear

RUN mkdir /tmp/ioncube && \
    mkdir -p /usr/local/ioncube && \
    cd /tmp/ioncube && \
    curl -SL http://downloads3.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz -o /tmp/ioncube/ioncube_loaders_lin_x86-64.tar.gz && \
    tar xvf /tmp/ioncube/ioncube_loaders_lin_x86-64.tar.gz && \
    cd `php -i | grep extension_dir | cut -d' ' -f 5` && \
    cp /tmp/ioncube/ioncube/ioncube_loader_lin_7.0.so /usr/local/ioncube/ioncube_loader_lin_7.0.so && \
    echo zend_extension=/usr/local/ioncube/ioncube_loader_lin_7.1.so > /usr/local/etc/php/conf.d/00-ioncube.ini && \
    rm -rf /tmp/ioncube/

ADD shopware.ini /usr/local/etc/php/conf.d/shopware.ini

WORKDIR /var/www/app

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin/ --filename=composer
RUN php -r "unlink('composer-setup.php');"

ARG SW_VERSION=latest
RUN git clone https://github.com/shopware/shopware.git shopware
RUN if [ "${SW_VERSION}" != "latest" ] ; then git checkout "${SW_VERSION}" ; fi

WORKDIR /var/www/app/shopware

ARG DB_USERNAME=app
ARG DB_PASSWORD=app
COPY config.php .

RUN composer install --no-interaction --optimize-autoloader --no-suggest
RUN php bin/console sw:cache:clear
RUN php bin/console sw:database:setup --steps=drop,create,import,setupShop --shopUrl=https://localhost:8080
RUN php bin/console sw:database:setup --steps=importDemodata,setupShop --shopUrl=https://localhost:8080
RUN php bin/console sw:snippets:to:db --include-plugins
RUN php bin/console sw:theme:initialize
RUN php bin/console sw:admin:create --name="Demo user" --email=demo@example.com --username=demo --password=demo --locale=de_DE
RUN echo 1 > recovery/install/data/install.lock
RUN php bin/console sw:firstrunwizard:disable

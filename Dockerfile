FROM php:7.2.5-fpm

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev libcurl4-gnutls-dev \
        openssl libssl-dev \
    && docker-php-ext-install -j$(nproc) opcache iconv mysqli pdo_mysql bcmath \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) bcmath \
    && sed -i 's/pm.max_children = [0-9]\{1,\}/pm.max_children = 2000/g' /usr/local/etc/php-fpm.d/www.conf \
    && curl -fsSL http://pecl.php.net/get/redis-4.0.2.tgz -o redis.tgz \
    && mkdir -p redis \
    && tar -xf redis.tgz -C redis --strip-components=1 \
    && rm redis.tgz \
    && ( \
            cd redis \
            && phpize \
            && ./configure --with-php-config=/usr/local/bin/php-config \
            && make -j$(nproc) \
            && make install \
        ) \
    && docker-php-ext-enable redis \
    && rm -r redis


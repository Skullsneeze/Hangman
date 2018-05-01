FROM php:7.1-fpm
ARG user
ENV USER ${user}
ENV TERM xterm-256color
RUN apt-get update -y \
    && mkdir /opt/go && export GOPATH=/opt/go \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y aptitude curl git-core libbz2-dev libfreetype6-dev libjpeg62-turbo-dev libpng12-dev libicu-dev \
                       libxslt-dev libmcrypt-dev libpq-dev golang-go --no-install-recommends \
    && rm -rf /var/lib/apt/lists/* \
    && curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/3.0.0.tar.gz \
        && tar -zxf /tmp/redis.tar.gz \
        && rm -r /tmp/redis.tar.gz \
        && mkdir -p /usr/src/php/ext \
        && mv "phpredis-3.0.0" /usr/src/php/ext/redis \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install bcmath bz2 gd mbstring intl xsl zip mcrypt pdo_mysql pdo_pgsql redis soap ftp \
    && yes | pecl install xdebug \
    && docker-php-ext-enable gd \
    && docker-php-ext-enable opcache \
    && go get github.com/mailhog/mhsendmail \
    && echo "memory_limit=1024M" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && useradd -ms /bin/bash "$USER" \
    && usermod -a -G www-data "$USER"

COPY entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
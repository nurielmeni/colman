FROM joomla:3.10.2-php7.3-apache
LABEL maintainer Nuriel Meni

RUN apt-get update && apt-get install -y libxml2 libxml2-dev

# Install PHP Soap Extention
RUN docker-php-ext-install soap

# MySql
#RUN docker-php-ext-install mysqli

# Rewrite Engin
RUN a2enmod rewrite
RUN service apache2 restart

# Install Xdebug
RUN pecl install "xdebug" \
    && docker-php-ext-enable xdebug

ENV XDEBUG_PORT 9003
ENV XDEBUG_IDEKEY docker

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.log=/var/www/html/tmp/xdebug_log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=${XDEBUG_PORT}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=${XDEBUG_IDEKEY}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

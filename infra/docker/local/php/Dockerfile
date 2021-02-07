FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

RUN apt-get update && apt-get install -y vim curl git unzip wget

# php 7.4 setup
RUN apt-get update && apt-get install -y php-cli php-mysql php-xml php-curl php-mbstring php-intl php-imagick php-amqp

ADD etc/php/7.4/cli/conf.d/ /etc/php/7.4/cli/conf.d/
RUN mkdir /run/php

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

RUN curl -s https://api.github.com/repos/fabpot/local-php-security-checker/releases/latest \
    | grep browser_download_url \
    | grep linux_386 \
    | cut -d '"' -f 4 \
    | wget -qi -
RUN mv local-php-security-checker_1.0.0_linux_386 /usr/bin/local-php-security-checker
RUN chmod +x /usr/bin/local-php-security-checker

# php-fpm 7.4 setup
RUN apt-get update && apt-get install -y php-fpm

ADD etc/php/7.4/fpm/conf.d/ /etc/php/7.4/fpm/conf.d/
ADD etc/php/7.4/fpm/pool.d/www.conf /etc/php/7.4/fpm/pool.d/

CMD ["php-fpm7.4", "-F"]

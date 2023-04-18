#FROM debian:stretch
FROM php:7.4-cli-buster

RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y wget git make

RUN apt -y install lsb-release apt-transport-https ca-certificates
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list

RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y libonig-dev libcurl4-openssl-dev pkg-config libzip-dev
RUN apt-get clean

RUN docker-php-ext-install pdo pdo_mysql mbstring curl zip bcmath

# Install Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash

WORKDIR /code

COPY . .

RUN php composer.phar install

CMD ["make", "start"]
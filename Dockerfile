FROM php:8.3-apache
COPY app/ /var/www/html

ARG DEBIAN_FRONTEND=noninteractive

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf 

# Install useful tools and install important libaries
RUN apt-get -y update && \
    apt-get -y --no-install-recommends install nano wget \
dialog \
locales \
libsqlite3-dev \
libsqlite3-0 && \
    apt-get -y --no-install-recommends install default-mysql-client \
zlib1g-dev \
libzip-dev \
libicu-dev && \
    apt-get -y --no-install-recommends install --fix-missing apt-utils \
build-essential \
git \
curl \
libonig-dev && \ 
    apt-get install -y iputils-ping && \
    apt-get -y --no-install-recommends install --fix-missing libcurl4 \
libcurl4-openssl-dev \
zip \
openssl

# Install xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && mkdir /var/log/xdebug

# Install imagick
RUN apt-get update  \
    && apt-get -y --no-install-recommends install --fix-missing libmagickwand-dev \
    && rm -rf /var/lib/apt/lists/*
RUN cd /usr/local/src && git clone https://github.com/Imagick/imagick \
    && cd ./imagick && phpize && ./configure && make && make install
RUN docker-php-ext-enable imagick 
  
# Other PHP8 Extensions
RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install bcmath \ 
    && docker-php-ext-install mysqli 
RUN docker-php-ext-install zip \
    && docker-php-ext-install mbstring \ 
    && docker-php-ext-install gettext \ 
    && docker-php-ext-install calendar \ 
    && docker-php-ext-install exif \
    && docker-php-ext-install gd
RUN docker-php-ext-install -j$(nproc) intl

RUN  echo "en_US.UTF-8 UTF-8" > /etc/locale.gen  \
  &&  echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
  &&  locale-gen 
  
RUN  curl -sS https://getcomposer.org/installer | php -- \
  &&  mv composer.phar /usr/local/bin/composer 
  
RUN curl -fsSL https://deb.nodesource.com/setup_current.x | bash - && \
  apt-get install -y nodejs
   
# Install Freetype 
RUN apt-get -y update \
    && apt-get --no-install-recommends install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && rm -rf /var/lib/apt/lists/*  \
    && docker-php-ext-configure intl

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg

# Insure an SSL directory exists
RUN mkdir -p /etc/apache2/ssl

# Enable apache modules
RUN a2enmod rewrite headers
RUN a2enmod ssl 

# Cleanup
RUN rm -rf /usr/src/*

WORKDIR /var/www/html

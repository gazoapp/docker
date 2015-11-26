FROM php:5.6-apache

# Install dependencies
RUN apt-get update -y
RUN apt-get install -y curl git

# Install composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/bin/composer

# Install app
RUN rm -rf /var/www/html/*
ADD . /var/www/html
RUN  cd /var/www/html && /usr/bin/composer install

# Configure apache
RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]

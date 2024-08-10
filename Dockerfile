
FROM php:7.4-apache


RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY . /var/www/html/


EXPOSE 80
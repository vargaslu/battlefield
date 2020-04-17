FROM php:7.2-apache

RUN a2enmod rewrite

COPY api /var/www/html/api
COPY src /var/www/html/src
COPY .htaccess /var/www/html


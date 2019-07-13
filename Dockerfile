FROM php:7.1.5-apache
RUN apt-get update && apt-get install -yq git && rm -rf /var/lib/apt/lists/*
ENV APP_HOME /var/www/html
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
RUN sed -i -e "s/html/html\/public/g" /etc/apache2/sites-enabled/000-default.conf
RUN a2enmod rewrite
COPY . $APP_HOME
RUN chown -R www-data:www-data $APP_HOME
ENTRYPOINT []
CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-enabled/000-default.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground

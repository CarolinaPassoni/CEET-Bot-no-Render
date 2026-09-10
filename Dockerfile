FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev libonig-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo_pgsql mbstring curl \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . /var/www/html
COPY docker/render-start.sh /usr/local/bin/render-start

RUN chmod +x /usr/local/bin/render-start \
    && chown -R www-data:www-data /var/www/html \
    && printf 'ServerName localhost\n' > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

EXPOSE 10000
CMD ["render-start"]

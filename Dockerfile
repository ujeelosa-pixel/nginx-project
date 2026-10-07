FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends nginx gettext-base \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install mysqli pdo_mysql

COPY nginx.conf.template /etc/nginx/nginx.conf.template
COPY start.sh /start.sh
# กัน error จากการขึ้นบรรทัดใหม่แบบ Windows (CRLF)
RUN sed -i 's/\r$//' /start.sh /etc/nginx/nginx.conf.template \
    && chmod +x /start.sh

COPY index.php /var/www/html/index.php
WORKDIR /var/www/html

CMD ["/start.sh"]

#!/bin/sh
export PORT="${PORT:-8080}"
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf
php-fpm -D
exec nginx -g 'daemon off;'

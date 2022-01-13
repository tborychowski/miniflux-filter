#!/usr/bin/env bash
sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-enabled/*
chown -R www-data:www-data store/
cron
rm store/filters.log 2> /dev/null
touch store/filters.log
chmod 777 store/filters.log
apache2-foreground & tail -f /var/log/cron.log

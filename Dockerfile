FROM php:8.1-apache

RUN apt-get update && apt-get install -y cron
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

# apache log to files not stdout/err
RUN rm /var/log/apache2/access.log && touch /var/log/apache2/access.log
RUN rm /var/log/apache2/error.log && touch /var/log/apache2/error.log
RUN rm /var/log/apache2/other_vhosts_access.log && touch /var/log/apache2/other_vhosts_access.log

# create virtual host
COPY docker-files/000-default.conf /etc/apache2/sites-available/000-default.conf

# add cron job
COPY docker-files/cron-job /etc/cron.d/
RUN chmod 0644 /etc/cron.d/cron-job
RUN crontab /etc/cron.d/cron-job
RUN touch /var/log/cron.log

WORKDIR /var/www/html
COPY docker-files/start-apache.sh .
COPY app/ .
RUN chown -R www-data:www-data /var/www/html
RUN chmod 0744 /var/www/html/cron.sh /var/www/html/filter-job.php

CMD ["/var/www/html/start-apache.sh"]

FROM ubuntu:14.04

RUN apt-get update
RUN apt-get install -y -q php5-cli php5-mysql php5-xdebug

EXPOSE 80

CMD php -S 0.0.0.0:80 -t /var/www/slims \
    -d xdebug.remote_enable=1 \
    -d xdebug.remote_connect_back=1 \
    -d xdebug.remote_autostart=1
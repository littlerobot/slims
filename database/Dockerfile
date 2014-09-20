FROM phusion/baseimage

RUN apt-get update
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get install -y mysql-server

ADD slims.sql /tmp/slims.sql

RUN service mysql start && \
    mysql -uroot -e "CREATE DATABASE slims;" && \
    mysql slims -uroot < /tmp/slims.sql && \
    rm /tmp/slims.sql && \
    mysql -uroot -e "CREATE USER 'slims'@'%' IDENTIFIED BY 'slims';" && \
    mysql -uroot -e "GRANT ALL PRIVILEGES ON slims.*  TO 'slims'@'%';"


EXPOSE 3306

CMD mysqld_safe --bind-address=0.0.0.0

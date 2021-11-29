FROM webdevops/php-apache-dev:7.4

RUN apt-get update
RUN apt-get install -y libsasl2-modules
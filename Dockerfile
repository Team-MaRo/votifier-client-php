FROM php:8.5-cli

WORKDIR /usr/src/app

RUN apt-get update && apt-get install -y

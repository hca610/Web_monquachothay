FROM php:7.4-cli

# Install Composer & pdo_mysql
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN apt update && apt install -y -f git zip \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions @composer \
    && install-php-extensions pdo_mysql

COPY . /src
WORKDIR /src
RUN composer install && php artisan key:generate && php artisan jwt:secret
CMD php artisan migrate:fresh --seed && php artisan serve --host 0.0.0.0 
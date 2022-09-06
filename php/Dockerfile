FROM php:7.4.7-fpm
WORKDIR /var/www/html
RUN apt-get update \
  && docker-php-ext-install pdo_mysql
RUN curl -sSL https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 -o mhsendmail \
  && chmod +x mhsendmail \
  && mv mhsendmail /usr/local/bin/mhsendmail
COPY ./php.ini /usr/local/etc/php/

# 日本語環境を追加
RUN apt-get update && apt-get install -y locales \
    && sed -i -e 's/# \(ja_JP.UTF-8\)/\1/' /etc/locale.gen \
    && locale-gen \
    && update-locale LANG=ja_JP.UTF-8
# ここまで
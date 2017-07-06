FROM wordpress:latest

ARG woocommerce_version

RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip wget gettext \
    && wget https://downloads.wordpress.org/plugin/woocommerce.${woocommerce_version}.zip -O /tmp/temp.zip \
    && cd /usr/src/wordpress/wp-content/plugins \
    && unzip /tmp/temp.zip \
    && rm /tmp/temp.zip \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/wp-content/plugins/komoju-woocommerce

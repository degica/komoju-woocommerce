FROM wordpress:latest

ARG woocommerce_version

RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip wget gettext vim \
    && wget https://downloads.wordpress.org/plugin/woocommerce.${woocommerce_version}.zip -O /tmp/woocommerce.zip \
    && wget https://downloads.wordpress.org/plugin/relative-url.0.1.7.zip -O /tmp/relative-url.zip \
    && cd /usr/src/wordpress/wp-content/plugins \
    && unzip /tmp/woocommerce.zip \
    && unzip /tmp/relative-url.zip \
    && rm /tmp/woocommerce.zip \
    && rm /tmp/relative-url.zip \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/www/html/wp-content/uploads/wc-logs/ \
    && chown -R www-data /var/www/html/wp-content/uploads/wc-logs/

COPY . /var/www/html/wp-content/plugins/komoju-woocommerce

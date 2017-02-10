FROM wordpress:latest

RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip wget \
    && wget https://downloads.wordpress.org/plugin/woocommerce.$(WOOCOMMERCE_VERSION).zip -O /tmp/temp.zip \
    && cd /usr/src/wordpress/wp-content/plugins \
    && unzip /tmp/temp.zip \
    && rm /tmp/temp.zip \
    && rm -rf /var/lib/apt/lists/*

RUN wget https://github.com/komoju/komoju-woocommerce/archive/master.zip -O /tmp/komoju-woocommerce.zip \
    && cd /usr/src/wordpress/wp-content/plugins \
    && unzip /tmp/komoju-woocommerce.zip \
    && rm /tmp/komoju-woocommerce.zip

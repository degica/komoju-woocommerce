FROM wordpress:latest

ARG woocommerce_version

RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip wget gettext vim bash-completion git sudo \
    && wget https://downloads.wordpress.org/plugin/woocommerce.${woocommerce_version}.zip -O /tmp/woocommerce.zip \
    && wget https://downloads.wordpress.org/plugin/relative-url.0.1.7.zip -O /tmp/relative-url.zip \
    && cd /usr/src/wordpress/wp-content/plugins \
    && unzip /tmp/woocommerce.zip \
    && unzip /tmp/relative-url.zip \
    && rm /tmp/woocommerce.zip \
    && rm /tmp/relative-url.zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN mkdir -p /var/www/html/wp-content/uploads/wc-logs/ \
    && chmod -R 666 /var/www/html/wp-content/uploads \
    && chown -R www-data /var/www/html/wp-content/uploads/

# Node JS
RUN curl -sL https://deb.nodesource.com/setup_lts.x | bash - \
   && apt-get install -y nodejs \
   && rm -rf /var/lib/apt/lists/*

RUN useradd --uid 1000 --shell /bin/bash --user-group --create-home me
RUN echo 'me ALL=(ALL) NOPASSWD:ALL' > /etc/sudoers.d/me

COPY . /var/www/html/wp-content/plugins/komoju-woocommerce

RUN chown -R me /var/www/html/wp-content/plugins/komoju-woocommerce

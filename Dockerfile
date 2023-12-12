FROM wordpress

RUN apt-get update && apt-get install -y \
    wget \
    unzip \
    nano \
    curl \
    tcpdump

RUN wget https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

RUN cd /var/www/html/wp-content/plugins \
    && wget https://downloads.wordpress.org/plugin/woocommerce.8.3.1.zip \
    && unzip woocommerce.8.3.1.zip
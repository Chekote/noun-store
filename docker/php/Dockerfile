# chekote/noun-store-php
FROM chekote/php:7-phpunit5

RUN apt-get update && \
    apt-get install -y --no-install-recommends --no-install-suggests \
        #
        # Guzzle requirements. See http://docs.guzzlephp.org/en/stable/overview.html#requirements
        php${PHP_VERSION}-curl \
        #
        # CircleCI Requirements
        ssh && \
    #
    # Cleanup
    apt-get autoremove -y && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    rm -rf /var/cache/apt/*

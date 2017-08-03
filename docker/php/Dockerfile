# chekote/noun-store
FROM chekote/php:5.6.30-c

RUN apt-get update && \

    apt-get install -y --no-install-recommends \

        # Composer Dependencies
        php${PHP_VERSION}-zip \

        # PHPUnit Dependencies
        php${PHP_VERSION}-dom \
        php${PHP_VERSION}-mbstring && \

    # Cleanup
    apt-get autoremove -y && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    rm -rf /var/cache/apt/*

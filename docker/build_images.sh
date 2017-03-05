#!/usr/bin/env bash

ROOT="$( cd "$( dirname "${BASH_SOURCE[0]}" )/../" && pwd )"

. "$ROOT"/docker/lib/images.sh

docker build "$ROOT"/docker/php -t ${PHP_ACCOUNT}/${PHP_REPO}:${PHP_TAG}

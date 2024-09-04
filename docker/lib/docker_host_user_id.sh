#!/usr/bin/env bash

# Determines the User ID of the User running Docker from the perspective of the Docker Engine.
# This is useful for ensuring the user in the Docker container has the correct access levels to mounted volumes.

# Determine the UID of the user running the Docker container.
if [ "$(uname)" == 'Darwin' ]; then
  # We're on Mac OS  X. We're virtualized using xhyve, and will have a UID of 1000.
  DOCKER_HOST_USER_ID=1000
else
  # We're on Linux. There's no virtualization, so we use our own UID.
  DOCKER_HOST_USER_ID=$(id -u)
fi

export DOCKER_HOST_USER_ID

#!/usr/bin/env bash

# Sets a TTY environmental variable with a Docker terminal emulation argument based on
# if the current shell session supports it.

# Do we have terminal emulation available in the current shell?
if [ -t 0 ]; then
  # Yes, set the TTY environmental variable to '-t' so when passed to Docker, the containers will also use it.
  TTY=-t
fi

export TTY

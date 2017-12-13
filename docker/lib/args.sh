#!/usr/bin/env bash

#
# Processes script arguments to ensure that the Docker container can access references files or directories
#
# This script will iterate over all of the arguments passed to the script and determine if any of them reference files
# or directories.
#
# If the arg references a file or directory, the script will ensure that the path is a fully qualified path
# (by converting a relative path to a fully qualified one)
#
# After ensuring the path is a fully qualified path, this script will compare the path with $WORKING_DIR, and if the
# path is contained within $WORKING_DIR, then the path will be modified to be a relative path in relation to
# $WORKING_DIR
#
# If the path is NOT contained within the $WORKING_DIR, then the dirname of the path will be mounted into the container
# as a volume.
#

VOLUMES=''
ARGS=()
I=0
for ARG in "$@"
do
    # get the dir name of the arg. If it's not a path, this will return nothing
    DIR=$(dirname "$ARG" 2> /dev/null)

    # do we see the dir name on the file system?
    if [ "$DIR" = '.' ] || [ ! -d "$DIR" ]; then
      # no, so this is probably not a path. just add it to the args as-is
      ARGS[$I]="$ARG"
    else
      # yes, so we need to process it

      # ensure that we're working with a fully qualified path
      REAL_PATH=$(cd "$DIR" && pwd)/$(basename "$ARG")

      # is the path contained within the working dir?
      if [[ "$REAL_PATH" == "$WORKING_DIR"* ]]; then
        # yes, convert it so it is relative to the workind dir
        RELATIVE=${REAL_PATH/$WORKING_DIR/.}
        ARGS[$I]="$RELATIVE"
      else
        # no, add it to the volumes so the Docker container can access it
        VOLUMES="$VOLUMES -v $DIR:$DIR"
        ARGS[$I]="$REAL_PATH"
      fi
    fi

    ((++I))
done

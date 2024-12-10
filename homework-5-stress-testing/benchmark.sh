#!/usr/bin/env bash

CONCURRENCY="${1:-50}"
REPEATS="${2:-100}"

docker run --rm -t --network host -v ./urls.txt:/tmp/urls.txt \
    yokogawa/siege -b -v -r"$REPEATS" -c"$CONCURRENCY" \
    --content-type "application/x-www-form-urlencoded" -f /tmp/urls.txt

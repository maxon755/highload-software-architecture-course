#!/usr/bin/env bash

CONCURRENCY="${1:-10}"
TIME="${2:-30}"

docker run --rm -t --network host \
    yokogawa/siege -b -v --time="$TIME"s -c"$CONCURRENCY" \
    http://localhost/test

#!/usr/bin/env bash

CONCURRENCY="${1:-50}"
REPEATS="${2:-10}"

docker run --rm -t --network haiduk_homework3 yokogawa/siege -v -b -r"$REPEATS" -c"$CONCURRENCY" \
    --content-type "application/json" \
    "http://web-server/mongo POST {}"

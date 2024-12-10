#!/bin/bash

if [ $# -ne 1 ]; then
  echo "Usage: $0 <query>"
  exit 1
fi

curl -XGET "http://localhost:9200/fruits/_search" -H 'Content-Type: application/json' -d '{
  "query": {
    "match": {
      "name": {
        "query": "'"$1"'",
        "analyzer": "autocomplete_analyzer"
      }
    }
  }
}'

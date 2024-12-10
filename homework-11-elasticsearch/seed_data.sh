#!/bin/bash

curl -XPOST "http://localhost:9200/fruits/_doc/1" -H 'Content-Type: application/json' -d '{
  "name": "apple"
}'

curl -XPOST "http://localhost:9200/fruits/_doc/2" -H 'Content-Type: application/json' -d '{
  "name": "banana"
}'

curl -XPOST "http://localhost:9200/fruits/_doc/3" -H 'Content-Type: application/json' -d '{
  "name": "watermelon"
}'

curl -XPOST "http://localhost:9200/fruits/_doc/4" -H 'Content-Type: application/json' -d '{
  "name": "blueberry"
}'

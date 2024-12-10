## HSA. Homework11. NoSQL Databases: Elasticsearch

ElasticSearch index that serves autocomplete needs with leveraging typos and errors

ElasticSearch version `7.14.0`

### Setup
Create index:
```bash
./create_index.sh
```
Fill index with some data:
```bash
./seed_data.sh
```

### Search
Search for the fruit:
```bash
./search.sh apple
```

It handles typos:
```bash
./search.sh wotermeon
```
```json
{
  "took": 11,
  "timed_out": false,
  "_shards": {
    "total": 1,
    "successful": 1,
    "skipped": 0,
    "failed": 0
  },
  "hits": {
    "total": {
      "value": 1,
      "relation": "eq"
    },
    "max_score": 1.4321768,
    "hits": [
      {
        "_index": "fruits",
        "_type": "_doc",
        "_id": "3",
        "_score": 1.4321768,
        "_source": {
          "name": "watermelon"
        }
      }
    ]
  }
}
```
---
```bash
./search.sh banul
```
```json
{
  "took": 2,
  "timed_out": false,
  "_shards": {
    "total": 1,
    "successful": 1,
    "skipped": 0,
    "failed": 0
  },
  "hits": {
    "total": {
      "value": 1,
      "relation": "eq"
    },
    "max_score": 2.854124,
    "hits": [
      {
        "_index": "fruits",
        "_type": "_doc",
        "_id": "2",
        "_score": 2.854124,
        "_source": {
          "name": "banana"
        }
      }
    ]
  }
}
```

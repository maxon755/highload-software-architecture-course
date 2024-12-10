## HSA. Homework9. SQL Databases

Benchmarking of `Mysql` database.

### The machine:

**CPU**: Intel(R) Core(TM) i7-10750H CPU @ 2.60GHz, 4333 MHz; 12 core

**RAM**: 16GB

**Storage**: SSD Micron MTFDHBA512TDV; Read **3300 МB/s** Write **2700 МB/s**

### The server:
MySQL Community Server - GPL  v8.0.34

### Table schema

```sql
CREATE TABLE `users`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `username`   varchar(50)  NOT NULL,
    `email`      varchar(100) NOT NULL,
    `birth_date` date DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
```

### Write benchmarks

Write performance is tested with `sysbench` tool

number of inserts: 1 million  
number of threads: 12

Prepare table:
```bash
docker-compose exec sysbench sysbench --db-driver=mysql --mysql-host=mysql --mysql-user=user --mysql-password=secret --mysql-db=storage /sysbench/scripts/users.lua prepare
```

Run benchmark:
```bash
docker-compose exec sysbench sysbench --db-driver=mysql --mysql-host=mysql --mysql-user=user --mysql-password=secret --mysql-db=storage --events=1000000 --threads=12 --time=0  /sysbench/scripts/users.lua run
```

| innodb_flush_log_at_trx_commit | transactions per second | execution time, s |
|--------------------------------|-------------------------|-------------------|
| 0                              | 2470.15                 | 404.6             |
| 2                              | 2287.02                 | 437.1             |
| 1                              | 1056.23                 | 946.5             |

**Conclusion:**  
When using the mysql server, changing the innodb_flush_log_at_trx_commit parameter affects record insertion speed:

- Setting `innodb_flush_log_at_trx_commit` to 0 results in the fastest record insertion, as it delays log buffer flushing until the transaction is committed.

- With `innodb_flush_log_at_trx_commit` set to 2, record insertion is slightly slower, as the log buffer is flushed to disk every second.

- The slowest record insertion occurs with `innodb_flush_log_at_trx_commit` set to 1, as the log buffer is flushed to disk after every transaction commit.

### Read benchmark

Seed `users` table with 40 million rows:
```bash
php seed-db.php 40000000
```

Dates range: `1900-01-01` - `2023-12-31`

Queries:
```sql
select *
from users
where birth_date >= "1990-01-01"
  and birth_date < "2000-01-01";

select *
from users
where birth_date >= "1995-01-01"
  and birth_date < "2000-01-01";

select *
from users
where birth_date >= "1999-01-01"
  and birth_date < "2000-01-01";

select *
from users
where birth_date = "2000-01-01";
```

|                                | from ‘1990-01-01’ to ‘2000-01-01' (10 years) | from ‘1995-01-01’ to ‘2000-01-01' (5 years) | from ‘1999-01-01’ to ‘2000-01-01' (1 year) | ‘2000-01-01’ (1 day) |
|--------------------------------|----------------------------------------------|---------------------------------------------|--------------------------------------------|----------------------|
| Without index on birth_date    | 176.273s (3225098 rows)                      | 91.036s (1612984 rows)                      | 25.862s (322577 rows)                      | 9.377s (905 rows)    |
| With BTREE index on birth_date | 178.378s (3225098 rows) (index not used)     | 99.562s (1612984 rows)                      | 17.773s (322577 rows)                      | 0.080s  (905 rows)   |

> HASH index is not supported by Innodb storage engine

Index selectivity is calculated by:
```sql
SELECT (COUNT(DISTINCT birth_date) / COUNT(*)) AS index_selectivity FROM users;
```

and equals **0.0011**

**Conclusion**:  
BTREE index for `birth_date` column is not effective for big range selection because
of it low selectivity.
But significantly decrease execution time for query with singe filter value (`2000-01-01` in the table)

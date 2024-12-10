<?php

declare(strict_types=1);

require_once __DIR__ . '/../Scenario.php';

class RedisQueueRead implements Scenario
{
    private Redis $redis;

    private string $queueName = 'test_queue';

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect('redis');
    }

    public static function description() : string
    {
        return 'Reading from Redis queue (list)';
    }

    public function prepare() : void
    {
        $values = range(0, 999_999);

        $this->redis->lPush($this->queueName, ...$values);
        $this->redis->lPush($this->queueName, ...$values);
        $this->redis->lPush($this->queueName, ...$values);
    }

    public function execute() : bool
    {
        $result = $this->redis->rPop($this->queueName);

        return !($result === false);
    }

    public function cleanup() : void
    {
        $this->redis->del($this->queueName);
        $this->redis->close();
    }
}

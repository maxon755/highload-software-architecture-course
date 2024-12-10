<?php

declare(strict_types=1);

require_once __DIR__ . '/../Scenario.php';

class RedisQueueWrite implements Scenario
{
    private Redis $redis;

    private string $queueName = 'test_queue';

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect('redis');
    }

    public function execute() : bool
    {
        $this->redis->lPush($this->queueName, random_int(0, 999));

        return true;
    }

    public function prepare() : void
    {
        $this->redis->del($this->queueName);
    }

    public function cleanup() : void
    {
        $this->redis->del($this->queueName);
        $this->redis->close();
    }

    public static function description() : string
    {
        return 'Writing to Redis queue (list)';
    }
}

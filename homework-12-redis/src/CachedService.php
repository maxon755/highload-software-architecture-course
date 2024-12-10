<?php

declare(strict_types=1);

namespace App;

use Redis;
use Psr\Log\LoggerInterface;

class CachedService
{
    public function __construct(
        private readonly Redis $redisClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function getClassicallyCachedValue(string $key, int $ttl = 10): int
    {
        $value = $this->redisClient->get($key);

        if ($value === false) {
            $value = $this->heavyComputationJob();

            $this->redisClient->setex($key, $ttl, $value);
        }

        return (int)$value;
    }

    public function getProbabilisticCachedValue(string $key, int $ttl = 10, float $beta = 1): int
    {
        $cachedData = $this->redisClient->hgetall($key);

        $value = isset($cachedData['value']) ? (int)$cachedData['value'] : null;
        $computationTime = isset($cachedData['computationTime']) ? (int)$cachedData['computationTime'] : null;
        $expiry = isset($cachedData['expiry']) ? (int)$cachedData['expiry'] : null;


        if (!$value || $this->shouldRecompute($expiry, $computationTime, $beta)) {
            if (!$value) {
                $this->logger->info("VALUE_NOT_FOUND");
            } else {
                $this->logger->info("PROBABILISTIC_COMPUTATION");

                $this->redisClient->setex('probabilistic_computation_in_progress', 60, true,);
            }

            $start = time();
            $value = $this->heavyComputationJob();
            $computationTime = time() - $start;
            $expiry = time() + $ttl;

            $this->redisClient->hmset($key, [
                'value' => $value,
                'computationTime' => $computationTime,
                'expiry' => $expiry,
            ]);

            $this->redisClient->expireat($key, $expiry);

            $this->redisClient->setex('probabilistic_computation_in_progress', 60, false);
        }

        return $value;
    }

    private function shouldRecompute(int $expiry, float $computationTime, float $beta = 1): bool
    {
        $p = $this->redisClient->get('probabilistic_computation_in_progress');

        if ($p) {
            return false;
        }

        return (time() - $computationTime * $beta * log($this->randomFloat()) >= $expiry);
    }

    function randomFloat($min = 0, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }


    private function heavyComputationJob(): int
    {
        $this->logger->info("Heavy computation job was started");

        sleep(3);

        return random_int(0, 99);
    }
}

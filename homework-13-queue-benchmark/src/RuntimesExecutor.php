<?php

declare(strict_types=1);

namespace Maxon755\Benchmark;

use parallel\Runtime;
use parallel\Future;


class RuntimesExecutor
{
    /**
     * @param int $threadsNumber
     * @param callable $task
     * @param array $arguments
     *
     * @return ExecutionResult
     */
    public function execute(int $threadsNumber, callable $task, array $arguments = []): ExecutionResult
    {
        $runtimes = array_map(fn() => new Runtime(), range(1, $threadsNumber));

        $futures = [];
        $startTime = microtime(true);
        foreach ($runtimes as $runtime) {
            $futures[] = $runtime->run($task, $arguments);
        }
        $this->waitForFuturesDone($futures);

        return new ExecutionResult(
            $futures,
            microtime(true) - $startTime
        );
    }

    /**
     * @param Future[] $futures
     *
     * @return void
     */
    private function waitForFuturesDone(array $futures): void
    {
        if (count($futures) === 0) {
            return;
        }

        do {
            usleep(1);
            $allDone = array_reduce(
                $futures,
                function (bool $c, Future $future) : bool {
                    return $c && $future->done();
                },
                true
            );
        } while (false === $allDone);
    }
}

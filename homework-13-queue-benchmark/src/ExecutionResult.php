<?php

declare(strict_types=1);

namespace Maxon755\Benchmark;

readonly class ExecutionResult
{
    public function __construct(
        public array $futures,
        public float $executionTime
    )
    {
    }
}

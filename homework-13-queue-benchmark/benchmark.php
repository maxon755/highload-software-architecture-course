<?php

require_once __DIR__ . '/vendor/autoload.php';

use parallel\Future;
use Maxon755\Benchmark\RuntimesExecutor;

$concurrency = $argv[1];
$time = $argv[2];
$scenario = $argv[3];

require_once __DIR__ . '/' . $scenario;

/** @var Scenario $scenarioClass */
$scenarioClass = getScenarioClassName();
echo $scenario . PHP_EOL;
echo $scenarioClass::description() . PHP_EOL;

echo "Concurrency: $concurrency" . PHP_EOL;
echo "Execution time: $time seconds" . PHP_EOL . PHP_EOL;

$runtimeExecutor = new RuntimesExecutor();

// Preparation
$preparationTask = function (string $scenario, string $scenarioClass) {
    require_once __DIR__ . '/' . $scenario;
    $scenario = new $scenarioClass();

    $scenario->prepare();
};

$runtimeExecutor->execute($concurrency, $preparationTask, [$scenario, $scenarioClass]);

echo 'Preparation done' . PHP_EOL;

// benchmarking
$benchmarkTask = function (string $scenario, string $scenarioClass, int $time) {
    require_once __DIR__ . '/' . $scenario;
    $scenario = new $scenarioClass();

    $executionCount = 0;
    $startTime = microtime(true);

    while ($scenario->execute()) {
        $executionCount++;

        $executionTime = microtime(true) - $startTime;

        if ($executionTime >= $time) {
            break;
        }
    }
    $scenario->cleanup();

    return $executionCount;
};

$result = $runtimeExecutor->execute($concurrency, $benchmarkTask, [$scenario, $scenarioClass, $time]);
$totalExecutions = array_reduce($result->futures, fn($sum, Future $future) => $sum + $future->value());
$totalExecutionTime = round($result->executionTime, 3);

$executionRate = (int)($totalExecutions / $time);

echo <<<OUTPUT
Real execution time: $totalExecutionTime seconds
Number of executions: $totalExecutions
Executions per second: $executionRate

OUTPUT;

function getScenarioClassName() : string
{
    $declaredClasses = get_declared_classes();

    return array_pop($declaredClasses);
}

<?php

declare(strict_types=1);

interface Scenario
{
    public static function description(): string;

    public function prepare(): void;

    public function execute(): bool;

    public function cleanup(): void;
}

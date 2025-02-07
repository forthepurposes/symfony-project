<?php

namespace App\Metric;

use App\Metric\Type\AbstractMetric;
use InfluxDB2\Point;

class MemoryUsageMetric extends AbstractMetric
{
    public function collect(): Point
    {
        $memoryUsage = memory_get_usage(true) / 1024 / 1024;
        return new Point('memory_usage', ['value' => $memoryUsage], ['host' => gethostname()]);
    }

    public function name(): string
    {
        return "memory_usage";
    }
}

<?php

namespace App\Metric;

use App\Metric\Type\AbstractMetric;
use InfluxDB2\Point;

class CpuLoadMetric extends AbstractMetric
{
    public function collect(): Point
    {
        $cpuLoad = sys_getloadavg()[0];
        return new Point('cpu_load', ['value' => $cpuLoad], ['host' => gethostname()]);
    }

    public function name(): string
    {
        return "cpu_load";
    }
}

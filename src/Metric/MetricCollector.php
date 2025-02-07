<?php

namespace App\Controller;

use App\Metric\CpuLoadMetric;
use App\Metric\MemoryUsageMetric;
use App\Service\InfluxDBService;

class MetricCollector
{
    private InfluxDBService $influx;
    private array $registeredMetrics;

    public function __construct()
    {
        $this->influx = new InfluxDBService();
        $this->registeredMetrics = [
            new CpuLoadMetric(),
            new MemoryUsageMetric()
        ];
    }

    public function collectAll(): void
    {
        foreach ($this->registeredMetrics as $metric) {
            $metricData = $metric->collect();
            $this->influx->writeMetric($metric->name(), $metricData);
        }
    }
}

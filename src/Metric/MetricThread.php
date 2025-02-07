<?php

namespace App\Metric;

use App\Controller\MetricCollector;
use Thread;

class MetricThread extends Thread
{
    private MetricCollector $collector;

    public function __construct(MetricCollector $collector)
    {
        $this->collector = $collector;
    }

    public function run()
    {
        while (true) {
            $this->collector->collectAll();
            sleep(1);
        }
    }
}

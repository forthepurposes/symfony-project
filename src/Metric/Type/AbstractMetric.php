<?php

namespace App\Metric\Type;

use InfluxDB2\Point;

abstract class AbstractMetric
{
    abstract public function name(): String;
    abstract public function collect(): Point;
}

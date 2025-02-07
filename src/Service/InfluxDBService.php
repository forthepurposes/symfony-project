<?php

namespace App\Service;

use InfluxDB2\Client;
use InfluxDB2\Point;

class InfluxDBService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'url' => $_ENV['INFLUX_URL'],
            'token' => $_ENV['INFLUX_TOKEN'],
            'org' => $_ENV['INFLUX_ORG'],
            'bucket' => $_ENV['INFLUX_BUCKET']
        ]);
    }

    public function writeMetric(string $measurement, array $fields, array $tags = [])
    {
        $writeApi = $this->client->createWriteApi();

        $point = Point::measurement($measurement)
            ->addFields($fields)
            ->addTags($tags)
            ->time(time());

        $writeApi->write($point);
    }

    public function close()
    {
        $this->client->close();
    }
}

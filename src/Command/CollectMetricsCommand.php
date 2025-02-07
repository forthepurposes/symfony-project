<?php

namespace App\Command;

use App\Controller\MetricCollector;
use App\Metric\MetricThread;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:start-collecting-metrics')]
class CollectMetricsCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Started metric collector");

        $thread = new MetricThread(new MetricCollector());
        $thread->start();

        return Command::SUCCESS;
    }
}

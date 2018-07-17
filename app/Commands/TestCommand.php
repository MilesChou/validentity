<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Validentity\Taiwan;

class TestCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this->setDescription('test')
            ->addOption('--count', null, InputOption::VALUE_REQUIRED, 'Test number', '100000')
            ->addOption('--step', null, InputOption::VALUE_REQUIRED, 'Test step check', '10000');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = (int)$input->getOption('count');
        $step = (int)$input->getOption('step');

        $taiwan = new Taiwan();

        $startTime = microtime(true);
        $output->writeln("Start at {$startTime}");

        for ($i = 0; $i < $count; $i++) {
            $fakeIdentity = $taiwan->generate();
            $isValid = $taiwan->check($fakeIdentity);

            if (!$isValid) {
                throw new \RuntimeException("Invalid identity {$fakeIdentity}");
            }

            if ($i % $step === 0) {
                $output->writeln("Completed {$i} ...");
            }
        }

        $endTime = microtime(true);
        $output->writeln("End at {$endTime}");
        $totalTime = $endTime - $startTime;
        $output->writeln(sprintf('Total time is %f second', $totalTime));

        $avgTime = $totalTime / $count;
        $output->writeln(sprintf('Average time is %f second', $avgTime));
    }
}

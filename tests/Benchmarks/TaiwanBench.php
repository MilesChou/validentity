<?php

namespace Tests\Benchmarks;

use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use Validentity\Taiwan;

class TaiwanBench
{
    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchTaiwan()
    {
        $target = new Taiwan();
        $target->check($target->generate());
    }
}
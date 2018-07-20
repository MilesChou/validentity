<?php

namespace Benchmarks;

use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use Validentity\Taiwan;

class TaiwanBench
{
    /**
     * @Revs(10000)
     * @Iterations(3)
     */
    public function benchTaiwan()
    {
        $target = new Taiwan();
        $target->check($target->generate());
    }
}
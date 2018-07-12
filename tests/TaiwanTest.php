<?php

namespace Tests;

use MilesChou\IdentityCard\Taiwan;

class TaiwanTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Taiwan
     */
    private $target;

    protected function setUp()
    {
        $this->target = new Taiwan();
    }

    protected function tearDown()
    {
        $this->target = null;
    }

    /**
     * @test
     */
    public function normalCase()
    {
        $this->assertTrue(true);
    }
}

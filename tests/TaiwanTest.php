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

    public function invalidId()
    {
        return [
            ['A0123456789'],
            ['A9876543210'],
            ['@123456789'],
            ['0123456789'],
            ['中123456789'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidId
     */
    public function shouldReturnFalseWhenGotInvalidId($invalidId)
    {
        $this->assertFalse($this->target->check($invalidId));
    }

    public function validId()
    {
        return [
            ['A123456789'],
            ['AC01234567'],
            ['FA12345689'],
            ['HD12345678'],
        ];
    }

    /**
     * @test
     * @dataProvider validId
     */
    public function shouldReturnTrueWhenGotValidId($id)
    {
        $this->assertTrue($this->target->check($id));
    }
}

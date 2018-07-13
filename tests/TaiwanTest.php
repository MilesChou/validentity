<?php

namespace Tests;

use Validentity\Taiwan;

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

    public function invalidArguments()
    {
        return [
            ['NULL', null],
            ['bool', true],
            ['bool', false],
            ['int', 0],
            ['int', 1],
            ['double', 1.0],
            ['array', []],
            ['object', new \stdClass()],
        ];
    }

    /**
     * @test
     * @dataProvider invalidArguments
     */
    public function shouldThrowExceptionWhenGotInvalidArguments($exceptedType, $invalidArguments)
    {
        $this->setExpectedException('InvalidArgumentException', $exceptedType);

        $this->target->check($invalidArguments);
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
            ['HD12345570'],
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

    /**
     * @test
     */
    public function shouldGenerateValidIdentity()
    {
        $fakeId = $this->target->generate();

        $this->assertTrue($this->target->check($fakeId), "Assert identity '$fakeId' is valid");
    }
}

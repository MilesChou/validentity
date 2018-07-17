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
    public function shouldThrowExceptionWhenCallNormalizeWithInvalidArguments($exceptedType, $invalidArguments)
    {
        $this->setExpectedException('InvalidArgumentException', $exceptedType);

        $this->target->normalize($invalidArguments);
    }

    public function invalidId()
    {
        return [
            ['a123456789'],
            ['AA00000000'],
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
            ['N152093966'],
            ['Z163009774'],
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

    /**
     * @test
     */
    public function shouldAllReturnTrueWhenSetValidateAll()
    {
        $this->target->setValidateType(Taiwan::VALIDATE_ALL);

        $this->assertTrue($this->target->check('A123456789'));
        $this->assertTrue($this->target->check('AC01234567'));
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenSetValidateLocalWithForeignIdentity()
    {
        $this->target->setValidateType(Taiwan::VALIDATE_LOCAL);

        $this->assertTrue($this->target->check('A123456789'));
        $this->assertFalse($this->target->check('AC01234567'));
    }

    /**
     * @test
     */
    public function shouldReturnFalseWhenSetValidateForeignWithLocalIdentity()
    {
        $this->target->setValidateType(Taiwan::VALIDATE_FOREIGN);

        $this->assertFalse($this->target->check('A123456789'));
        $this->assertTrue($this->target->check('AC01234567'));
    }
}

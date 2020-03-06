<?php namespace Unit\Chekote\NounStore\Singleton;

use Chekote\NounStore\Singleton;
use Unit\Chekote\NounStore\TestCase;

class SingletonClass
{
    use Singleton;
}

/**
 * @covers \Chekote\NounStore\Singleton::getInstance()
 */
class GetInstanceTest extends TestCase
{
    public function testGetInstanceReturnsInstanceOfSingleton()
    {
        $instance = SingletonClass::getInstance();

        $this->assertInstanceOf(SingletonClass::class, $instance);
    }

    public function testSubsequentCallsToGetInstanceReturnSameInstance()
    {
        $instance1 = SingletonClass::getInstance();
        $instance2 = SingletonClass::getInstance();

        $this->assertEquals(spl_object_hash($instance1), spl_object_hash($instance2));
    }
}

<?php namespace Chekote\NounStore\Store;

use Chekote\NounStore\Store;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @covers Store
 */
abstract class StoreTest extends TestCase
{
    /** @var Store */
    protected $store;

    const KEY = 'Some Key';
    const FIRST_VALUE = 'The First Value';
    const SECOND_VALUE = 'The Second Value';

    /**
     * Sets up the environment before each test.
     */
    public function setUp()
    {
        $this->store = new Store();
        $this->store->set(self::KEY, self::FIRST_VALUE);
        $this->store->set(self::KEY, self::SECOND_VALUE);
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown()
    {
        $this->store = null;
    }

    /**
     * Ensures that the specified method on Store is accessible.
     *
     * @param  string           $name the method to make accessible.
     * @return ReflectionMethod the method.
     */
    protected function makeMethodAccessible($name)
    {
        $method = (new ReflectionClass(Store::class))->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * Ensures that the specified property on Store is accessible.
     *
     * @param  string             $name the property to make accessible.
     * @return ReflectionProperty the property.
     */
    protected function makePropertyAccessible($name)
    {
        $property = (new ReflectionClass(Store::class))->getProperty($name);
        $property->setAccessible(true);

        return $property;
    }
}

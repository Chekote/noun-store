<?php namespace Chekote\NounStore\Store;

use Chekote\NounStore\Store;
use PHPUnit\Framework\TestCase;

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
}

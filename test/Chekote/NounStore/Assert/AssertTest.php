<?php namespace Chekote\NounStore\Assert;

use Chekote\NounStore\Assert;
use Chekote\NounStore\Key;
use Chekote\NounStore\Store;
use Chekote\NounStore\Store\StoreTest;
use PHPUnit\Framework\TestCase;

abstract class AssertTest extends TestCase
{
    /** @var Assert */
    protected $assert;

    /** @var Store */
    protected $store;

    /** @var Key */
    protected $key;

    /**
     * Sets up the environment before each test.
     */
    public function setUp()
    {
        $this->store = new Store();
        $this->store->set(StoreTest::KEY, StoreTest::FIRST_VALUE);
        $this->store->set(StoreTest::KEY, StoreTest::SECOND_VALUE);

        $this->assert = new Assert($this->store, Key::getInstance());
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown()
    {
        $this->assert = null;
    }
}

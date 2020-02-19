<?php namespace Unit\Chekote\NounStore\Assert;

use Chekote\NounStore\Assert;
use Chekote\NounStore\Key;
use Chekote\NounStore\Store;
use Phake_IMock;
use Unit\Chekote\NounStore\TestCase;
use Unit\Chekote\Phake\Phake;

abstract class AssertTest extends TestCase
{
    /** @var Assert|Phake_IMock */
    protected $assert;

    /** @var Store|Phake_IMock */
    protected $store;

    /** @var Key|Phake_IMock */
    protected $key;

    /**
     * Sets up the environment before each test.
     */
    public function setUp()
    {
        $this->key = Phake::strictMock(Key::class);
        $this->store = Phake::strictMockWithConstructor(Store::class, $this->key);
        $this->assert = Phake::strictMockWithConstructor(Assert::class, $this->store, $this->key);
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown()
    {
        $this->assert = null;
        $this->key = null;
        $this->store = null;
    }
}

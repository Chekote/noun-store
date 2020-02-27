<?php namespace Chekote\NounStore\Assert;

use Chekote\NounStore\Assert;
use Chekote\NounStore\Key;
use Chekote\NounStore\Key\KeyPhake;
use Chekote\NounStore\Store;
use Chekote\NounStore\Store\StorePhake;
use Chekote\NounStore\TestCase;
use Chekote\Phake\Phake;

abstract class AssertTest extends TestCase
{
    /** @var AssertPhake */
    protected $assert;

    /** @var StorePhake */
    protected $store;

    /** @var KeyPhake */
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

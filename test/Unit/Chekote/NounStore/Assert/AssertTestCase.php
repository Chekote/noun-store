<?php namespace Unit\Chekote\NounStore\Assert;

use Chekote\NounStore\Assert;
use Chekote\NounStore\Key;
use Chekote\NounStore\Store;
use Unit\Chekote\NounStore\TestCase;
use Unit\Chekote\Phake\Phake;

abstract class AssertTestCase extends TestCase
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
    public function setUp(): void
    {
        $this->key = Phake::strictMock(Key::class);
        $this->store = Phake::strictMockWithConstructor(Store::class, $this->key);
        $this->assert = Phake::strictMockWithConstructor(Assert::class, $this->store, $this->key);
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown(): void
    {
        $this->assert = null;
        $this->key = null;
        $this->store = null;
    }
}

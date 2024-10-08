<?php namespace Unit\Chekote\NounStore\Assert;

use Chekote\NounStore\Assert;
use Chekote\NounStore\Key;
use Chekote\NounStore\Store;
use Phake\IMock;
use Unit\Chekote\NounStore\Store\StorePhake;
use Unit\Chekote\NounStore\TestCase;
use Unit\Chekote\Phake\Phake;

abstract class AssertTestCase extends TestCase
{
    protected IMock|AssertPhake $assert;

    protected IMock|StorePhake $store;

    protected IMock|KeyPhake $key;

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
        unset($this->assert);
        unset($this->key);
        unset($this->store);
    }
}

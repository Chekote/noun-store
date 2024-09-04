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
    protected IMock|AssertPhake|null $assert;

    protected IMock|StorePhake|null $store;

    protected IMock|KeyPhake|null $key;

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

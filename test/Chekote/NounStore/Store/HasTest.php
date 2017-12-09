<?php namespace Chekote\NounStore\Store;

use InvalidArgumentException;

/**
 * @covers Store::has()
 */
class HasTest extends StoreTest
{
    /**
     * Tests that InvalidArgumentException is thrown if Store::has is called with the nth parameter
     * and the key also contains an nth value, but they do not match.
     */
    public function testHasThrowsInvalidArgumentExceptionWithMismatchedNthInKeyAndParam()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->store->has('1st Thing', 1);
    }

    /**
     * Tests that store::has returns true if nth value in key is found in store.
     */
    public function testHasWithExistingNthKeyReturnsTrue()
    {
        $this->assertTrue($this->store->has('1st ' . self::KEY));
    }

    /**
     * Tests that store::has returns false if nth value in key is not found in store.
     */
    public function testHasWithMissingNthKeyReturnsFalse()
    {
        $this->assertFalse($this->store->has('3rd ' . self::KEY));
    }

    /**
     * Tests that store::has returns true if nth param value is found in store.
     */
    public function testHasWithExistingNthParameterReturnsTrue()
    {
        $this->assertTrue($this->store->has(self::KEY, 0));
    }

    /**
     * Tests that store::has returns false if nth param value is not found in store.
     */
    public function testHasWithMissingNthParameterReturnsFalse()
    {
        $this->assertFalse($this->store->has(self::KEY, 2));
    }
}

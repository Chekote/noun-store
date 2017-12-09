<?php namespace Chekote\NounStore\Store;

use InvalidArgumentException;

/**
 * @covers Store::get()
 */
class GetTest extends StoreTest
{
    /**
     * Tests that InvalidArgumentException is thrown if Store::get is called with the nth parameter
     * and the key also contains an nth value, but they do not match.
     */
    public function testGetThrowsInvalidArgumentExceptionWithMismatchedNthInKeyAndParam()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->store->get('1st Thing', 1);
    }

    /**
     * Tests that Store::get returns the item at the end of the stack.
     */
    public function testGetReturnsItemAtEndOfStack()
    {
        $this->assertEquals(self::SECOND_VALUE, $this->store->get(self::KEY));
    }

    /**
     * Tests that Store::get returns the nth item at of the stack when the $key contains nth.
     */
    public function testGetWithNthKeyReturnsNthItem()
    {
        $this->assertEquals(self::FIRST_VALUE, $this->store->get('1st ' . self::KEY));
    }

    /**
     * Tests that Store::get returns the nth item at of the stack when $nth parameter is provided.
     */
    public function testGetWithNthParameterReturnsNthItem()
    {
        $this->assertEquals(self::FIRST_VALUE, $this->store->get(self::KEY, 0));
    }

    /**
     * Tests that Store::get returns null when the specified $key does not exist.
     */
    public function testGetReturnsNullWhenKeyDoesNotExist()
    {
        $this->assertEquals(null, $this->store->get('Thing'));
    }

    /**
     * Tests that Store::get returns null when the specified nth $key does not exist.
     */
    public function testGetReturnsNullWhenNthKeyDoesNotExist()
    {
        $this->assertEquals(null, $this->store->get('3rd ' . self::KEY));
    }

    /**
     * Tests that Store::get returns null when the specified $nth param does not exist.
     */
    public function testGetReturnsNullWhenNthDoesNotExist()
    {
        $this->assertEquals(null, $this->store->get(self::KEY, 2));
    }
}

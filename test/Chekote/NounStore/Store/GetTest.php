<?php namespace Chekote\NounStore\Store;

use Chekote\Phake\Phake;
use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Store::get()
 */
class GetTest extends StoreTest
{
    public function setUp() {
        parent::setUp();

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->get(Phake::anyParameters())->thenCallParent();
    }

    /**
     * Tests that InvalidArgumentException is thrown if Store::get is called with the index parameter
     * and the key also contains an nth value, but they do not match.
     */
    public function testGetThrowsInvalidArgumentExceptionWithMismatchedNthAndIndex()
    {
        $key = '1st Thing';
        $index = 1;

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenThrow(new InvalidArgumentException());

        $this->expectException(InvalidArgumentException::class);

        $this->store->get($key, $index);
    }

    /**
     * Tests that Store::get returns the item at the end of the stack.
     */
    public function testGetReturnsItemAtEndOfStack()
    {
        $key = self::KEY;
        $index = null;
        $parsedKey = self::KEY;
        $parsedIndex = null;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
        }

        $this->assertEquals(self::SECOND_VALUE, $this->store->get(self::KEY));
    }

    /**
     * Tests that Store::get returns the nth item at of the stack when the $key contains nth.
     */
    public function testGetWithNthKeyReturnsNthItem()
    {
        $key = '1st ' . self::KEY;
        $index = null;
        $parsedKey = self::KEY;
        $parsedIndex = 0;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
        }

        $this->assertEquals(self::FIRST_VALUE, $this->store->get($key));
    }

    /**
     * Tests that Store::get returns the index item at of the stack when index parameter is provided.
     */
    public function testGetWithIndexParameterReturnsIndexItem()
    {
        $key = self::KEY;
        $index = 0;
        $parsedKey = self::KEY;
        $parsedIndex = 0;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
        }

        $this->assertEquals(self::FIRST_VALUE, $this->store->get(self::KEY, $index));
    }

    /**
     * Tests that Store::get returns null when the specified $key does not exist.
     */
    public function testGetReturnsNullWhenKeyDoesNotExist()
    {
        $key = 'Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = null;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(false);
        }

        $this->assertEquals(null, $this->store->get($key));
    }

    /**
     * Tests that Store::get returns null when the specified nth $key does not exist.
     */
    public function testGetReturnsNullWhenNthKeyDoesNotExist()
    {
        $key = '3rd ' . self::KEY;
        $index = null;
        $parsedKey = self::KEY;
        $parsedIndex = 2;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(false);
        }

        $this->assertEquals(null, $this->store->get($key));
    }

    /**
     * Tests that Store::get returns null when the specified $index param does not exist.
     */
    public function testGetReturnsNullWhenIndexDoesNotExist()
    {
        $key = self::KEY;
        $index = 2;
        $parsedKey = self::KEY;
        $parsedIndex = 2;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(false);
        }

        $this->assertEquals(null, $this->store->get($key, $index));
    }
}

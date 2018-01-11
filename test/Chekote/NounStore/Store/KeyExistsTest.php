<?php namespace Chekote\NounStore\Store;

use Chekote\Phake\Phake;
use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Store::keyExists()
 */
class KeyExistsTest extends StoreTest
{
    public function setUp() {
        parent::setUp();

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->keyExists(Phake::anyParameters())->thenCallParent();
    }

    public function testMismatchedNthAndIndexThrowsInvalidArgumentException()
    {
        $key = '1st Thing';
        $index = 1;

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenThrow(new InvalidArgumentException());

        $this->expectException(InvalidArgumentException::class);

        $this->store->keyExists($key, $index);
    }

    public function testExistingNthKeyReturnsTrue()
    {
        $key = '1st ' . self::KEY;
        $index = null;
        $parsedKey = self::KEY;
        $parsedIndex = 0;

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);

        $this->assertTrue($this->store->keyExists($key));
    }

    public function testMissingNthKeyReturnsFalse()
    {
        $key = '3rd ' . self::KEY;
        $index = null;
        $parsedKey = self::KEY;
        $parsedIndex = 2;

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);

        $this->assertFalse($this->store->keyExists($key));
    }

    public function testExistingIndexParameterReturnsTrue()
    {
        $key = self::KEY;
        $index = 0;
        $parsedKey = self::KEY;
        $parsedIndex = 0;

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);

        $this->assertTrue($this->store->keyExists($key, $index));
    }

    public function testMissingIndexParameterReturnFalse()
    {
        $key = self::KEY;
        $index = 2;
        $parsedKey = self::KEY;
        $parsedIndex = 2;

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);

        $this->assertFalse($this->store->keyExists($key, $index));
    }
}

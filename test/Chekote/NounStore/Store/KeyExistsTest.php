<?php namespace Chekote\NounStore\Store;

use InvalidArgumentException;

/**
 * @covers Store::keyExists()
 */
class KeyExistsTest extends StoreTest
{
    public function testMismatchedNthAndIndexThrowsInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->store->keyExists('1st Thing', 1);
    }

    public function testExistingNthKeyReturnsTrue()
    {
        $this->assertTrue($this->store->keyExists('1st ' . self::KEY));
    }

    public function testMissingNthKeyReturnsFalse()
    {
        $this->assertFalse($this->store->keyExists('3rd ' . self::KEY));
    }

    public function testExistingIndexParameterReturnsTrue()
    {
        $this->assertTrue($this->store->keyExists(self::KEY, 0));
    }

    public function testMissingIndexParameterReturnFalse()
    {
        $this->assertFalse($this->store->keyExists(self::KEY, 2));
    }
}

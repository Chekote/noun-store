<?php namespace Chekote\NounStore\Store;

use InvalidArgumentException;

/**
 * @covers Store::keyExists()
 */
class KeyExistsTest extends StoreTest
{
    public function testMismatchedNthInKeyAndParamThrowsInvalidArgumentException()
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

    public function testExistingNthParameterReturnsTrue()
    {
        $this->assertTrue($this->store->keyExists(self::KEY, 0));
    }

    public function testMissingNthParameterReturnFalse()
    {
        $this->assertFalse($this->store->keyExists(self::KEY, 2));
    }
}

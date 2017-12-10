<?php namespace Chekote\NounStore\Store;

use InvalidArgumentException;
use OutOfBoundsException;

/**
 * @covers Store::assertKeyExists()
 */
class AssertKeyExistsTest extends StoreTest
{
    public function testMismatchedNthAndIndexAreRejected()
    {
        $this->expectException(InvalidArgumentException::class);

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->store->assertKeyExists('1st Thing', 1);
    }

    public function testKeyWithExistingNthReturnsValue()
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(self::FIRST_VALUE, $this->store->assertKeyExists('1st ' . self::KEY));
    }

    public function testKeyWithNonExistingNthThrowsException()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Entry '3rd " . self::KEY . "' was not found in the store.");

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->store->assertKeyExists('3rd ' . self::KEY);
    }

    public function testExistingIndexReturnsValue()
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(self::FIRST_VALUE, $this->store->assertKeyExists(self::KEY, 0));
    }

    public function testNonExistentIndexThrowsException()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Entry '3rd " . self::KEY . "' was not found in the store.");

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->store->assertKeyExists(self::KEY, 2);
    }
}

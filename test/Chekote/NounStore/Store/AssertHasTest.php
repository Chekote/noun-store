<?php namespace Chekote\NounStore\Store;

use InvalidArgumentException;
use OutOfBoundsException;

/**
 * @covers Store::assertHas()
 */
class AssertHasTest extends StoreTest
{
    public function testMismatchedNthInKeyAndParamAreRejected()
    {
        $this->expectException(InvalidArgumentException::class);

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->store->assertHas('1st Thing', 1);
    }

    public function testKeyWithExistingNthReturnsValue()
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(self::FIRST_VALUE, $this->store->assertHas('1st ' . self::KEY));
    }

    public function testKeyWithNonExistingNthThrowsException()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Entry '3rd " . self::KEY . "' was not found in the store.");

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->store->assertHas('3rd ' . self::KEY);
    }

    public function testExistingNthParameterReturnsValue()
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(self::FIRST_VALUE, $this->store->assertHas(self::KEY, 0));
    }

    public function testNonExistentNthParameterThrowsException()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Entry '3rd " . self::KEY . "' was not found in the store.");

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->store->assertHas(self::KEY, 2);
    }
}

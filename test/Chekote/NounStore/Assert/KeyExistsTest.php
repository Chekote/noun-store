<?php namespace Chekote\NounStore\Assert;

use Chekote\NounStore\Store\StoreTest;
use InvalidArgumentException;
use OutOfBoundsException;

class AssertKeyExistsTest extends AssertTest
{
    public function testMismatchedNthAndIndexAreRejected()
    {
        $this->expectException(InvalidArgumentException::class);

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assert->keyExists('1st Thing', 1);
    }

    public function testKeyWithExistingNthReturnsValue()
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(StoreTest::FIRST_VALUE, $this->assert->keyExists('1st ' . StoreTest::KEY));
    }

    public function testKeyWithNonExistingNthThrowsException()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Entry '3rd " . StoreTest::KEY . "' was not found in the store.");

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assert->keyExists('3rd ' . StoreTest::KEY);
    }

    public function testExistingIndexReturnsValue()
    {
        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(StoreTest::FIRST_VALUE, $this->assert->keyExists(StoreTest::KEY, 0));
    }

    public function testNonExistentIndexThrowsException()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Entry '3rd " . StoreTest::KEY . "' was not found in the store.");

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assert->keyExists(StoreTest::KEY, 2);
    }
} 
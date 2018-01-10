<?php namespace Chekote\NounStore\Assert;

use Chekote\NounStore\Store\StoreTest;
use Chekote\Phake\Phake;
use InvalidArgumentException;
use OutOfBoundsException;

/**
 * @covers \Chekote\NounStore\Assert::keyExists()
 */
class KeyExistsTest extends AssertTest
{
    public function setUp() {
        parent::setUp();

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->assert)->keyExists(Phake::anyParameters())->thenCallParent();
    }

    public function testMismatchedNthAndIndexAreRejected()
    {
        $key = '1st Thing';
        $index = 1;

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenThrow(new InvalidArgumentException());

        $this->expectException(InvalidArgumentException::class);

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assert->keyExists($key, $index);
    }

    public function testKeyWithExistingNthReturnsValue()
    {
        $key = '1st ' . StoreTest::KEY;
        $parsedKey = StoreTest::KEY;
        $parsedIndex = 0;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, null)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
            Phake::when($this->store)->get($parsedKey, $parsedIndex)->thenReturn(StoreTest::FIRST_VALUE);
        }

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(StoreTest::FIRST_VALUE, $this->assert->keyExists($key));
    }

    public function testKeyWithNonExistingNthThrowsException()
    {
        $key = '3rd ' . StoreTest::KEY;
        $parsedKey = StoreTest::KEY;
        $parsedIndex = 2;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, null)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(false);
            Phake::when($this->key)->build($parsedKey, $parsedIndex)->thenReturn($key);
        }

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Entry '$key' was not found in the store.");

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assert->keyExists($key);
    }

    public function testExistingIndexReturnsValue()
    {
        $key = StoreTest::KEY;
        $index = 0;
        $parsedKey = StoreTest::KEY;
        $parsedIndex = 0;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, null)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
            Phake::when($this->store)->get($parsedKey, $parsedIndex)->thenReturn(StoreTest::FIRST_VALUE);
        }

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals(StoreTest::FIRST_VALUE, $this->assert->keyExists($key, $index));
    }

    public function testNonExistentIndexThrowsException()
    {
        $key = StoreTest::KEY;
        $index = 2;
        $parsedKey = StoreTest::KEY;
        $parsedIndex = 2;
        $builtKey = '3rd ' . $parsedKey;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(false);
            Phake::when($this->key)->build($parsedKey, $parsedIndex)->thenReturn($builtKey);
        }

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Entry '$builtKey' was not found in the store.");

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assert->keyExists($key, $index);
    }
}

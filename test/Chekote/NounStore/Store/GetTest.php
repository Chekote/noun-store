<?php namespace Chekote\NounStore\Store;

use Chekote\Phake\Phake;
use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Store::get()
 */
class GetTest extends StoreTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->get(Phake::anyParameters())->thenCallParent();
    }

    public function testKeyIsParsedAndParsedValuesAreUsed()
    {
        $key = '2nd ' . StoreTest::KEY;
        $index = null;
        $parsedKey = StoreTest::KEY;
        $parsedIndex = 1;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
        }

        $this->assertEquals(StoreTest::SECOND_VALUE, $this->store->get($key, $index));

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->store)->keyExists($parsedKey, $parsedIndex);
        }
    }

    public function testInvalidArgumentExceptionBubblesUpFromParse()
    {
        $key = '10th Thing';
        $index = 5;
        $exception = new InvalidArgumentException(
            "$index was provided for index param when key '$key' contains an nth value, but they do not match"
        );

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenThrow($exception);

        $this->assertException($exception, function () use ($key, $index) {
            $this->store->get($key, $index);
        });

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::verify($this->key)->parse($key, $index);
    }

    public function testReturnsNullWhenKeyDoesNotExist()
    {
        $key = StoreTest::KEY;
        $index = 2;
        $parsedKey = $key;
        $parsedIndex = $index;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(false);
        }

        $this->assertNull($this->store->get($key, $index));

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->store)->keyExists($parsedKey, $parsedIndex);
        }
    }

    public function testLastItemIsReturnedWhenParsedIndexIsNull()
    {
        $key = StoreTest::KEY;
        $index = null;
        $parsedKey = $key;
        $parsedIndex = $index;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
        }

        $this->assertEquals(StoreTest::SECOND_VALUE, $this->store->get($key, $index));

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->store)->keyExists($parsedKey, $parsedIndex);
        }
    }

    public function testIndexItemIsReturnedWhenParsedIndexIsNotNull()
    {
        $key = '1st ' . StoreTest::KEY;
        $index = null;
        $parsedKey = StoreTest::KEY;
        $parsedIndex = 0;

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
        }

        $this->assertEquals(StoreTest::FIRST_VALUE, $this->store->get($key, $index));

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->store)->keyExists($parsedKey, $parsedIndex);
        }

    }

}

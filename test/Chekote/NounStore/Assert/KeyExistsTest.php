<?php namespace Chekote\NounStore\Assert;

use Chekote\Phake\Phake;
use InvalidArgumentException;
use OutOfBoundsException;

/**
 * @covers \Chekote\NounStore\Assert::keyExists()
 */
class KeyExistsTest extends AssertTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->assert)->keyExists(Phake::anyParameters())->thenCallParent();
    }

    public function testKeyIsParsedAndParsedValuesAreUsed()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
            Phake::when($this->store)->get($parsedKey, $parsedIndex)->thenReturn('something');
        }

        $this->assert->keyExists($key, $index);

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->store)->keyExists($parsedKey, $parsedIndex);
            Phake::verify($this->store)->get($parsedKey, $parsedIndex);
        }
    }

    public function testInvalidArgumentExceptionBubblesUpFromParse()
    {
        $key = '10th Thing';
        $index = 5;
        $exception = new InvalidArgumentException(
            "$index was provided for index param when key '$key' contains an nth value, but they do not match"
        );

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenThrow($exception);

        $this->assertException($exception, function () use ($key, $index) {
            $this->assert->keyExists($key, $index);
        });

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::verify($this->key)->parse($key, $index);
    }

    public function testMissingKeyThrowsOutOfBoundsException()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;

        $exception = new OutOfBoundsException("Entry '$key' was not found in the store.");

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(false);
            Phake::when($this->key)->build($parsedKey, $parsedIndex)->thenReturn($key);
        }

        $this->assertException($exception, function () use ($key, $index) {
            $this->assert->keyExists($key, $index);
        });

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->store)->keyExists($parsedKey, $parsedIndex);
            Phake::verify($this->key)->build($parsedKey, $parsedIndex);
        }
    }

    public function testStoredValueIsReturned()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $value = 'Some Value';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
            Phake::when($this->store)->get($parsedKey, $parsedIndex)->thenReturn($value);
        }

        $this->assertEquals($value, $this->assert->keyExists($key, $index));

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->store)->keyExists($parsedKey, $parsedIndex);
            Phake::verify($this->store)->get($parsedKey, $parsedIndex);
        }
    }
}

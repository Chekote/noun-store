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
            Phake::expect($this->key, 1)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::expect($this->store, 1)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
            Phake::expect($this->store, 1)->get($parsedKey, $parsedIndex)->thenReturn('something');
        }

        $this->assert->keyExists($key, $index);
    }

    public function testInvalidArgumentExceptionBubblesUpFromParse()
    {
        $key = '10th Thing';
        $index = 5;
        $exception = new InvalidArgumentException(
            "$index was provided for index param when key '$key' contains an nth value, but they do not match"
        );

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse($key, $index)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyExists($key, $index);
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
            Phake::expect($this->key, 1)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::expect($this->store, 1)->keyExists($parsedKey, $parsedIndex)->thenReturn(false);
            Phake::expect($this->key, 1)->build($parsedKey, $parsedIndex)->thenReturn($key);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyExists($key, $index);
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
            Phake::expect($this->key, 1)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::expect($this->store, 1)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
            Phake::expect($this->store, 1)->get($parsedKey, $parsedIndex)->thenReturn($value);
        }

        $this->assertEquals($value, $this->assert->keyExists($key, $index));
    }
}

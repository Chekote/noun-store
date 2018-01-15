<?php namespace Chekote\NounStore\Assert;

use InvalidArgumentException;
use OutOfBoundsException;

/**
 * @covers \Chekote\NounStore\Assert::keyExists()
 */
class KeyExistsTest extends AssertTest
{
    public function testKeyIsParsedAndParsedValuesAreUsed()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;

        /* @noinspection PhpUndefinedMethodInspection */
        {
            $this->key->parse($key, $index)->willReturn([$parsedKey, $parsedIndex])->shouldBeCalledTimes(1);
            $this->store->keyExists($parsedKey, $parsedIndex)->willReturn(true)->shouldBeCalledTimes(1);
            $this->store->get($parsedKey, $parsedIndex)->willReturn('something')->shouldBeCalledTimes(1);
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
        $this->key->parse($key, $index)->willThrow($exception)->shouldBeCalledTimes(1);

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
            $this->key->parse($key, $index)->willReturn([$parsedKey, $parsedIndex])->shouldBeCalledTimes(1);
            $this->store->keyExists($parsedKey, $parsedIndex)->willReturn(false)->shouldBeCalledTimes(1);
            $this->key->build($parsedKey, $parsedIndex)->willReturn($key)->shouldBeCalledTimes(1);
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
            $this->key->parse($key, $index)->willReturn([$parsedKey, $parsedIndex])->shouldBeCalledTimes(1);
            $this->store->keyExists($parsedKey, $parsedIndex)->willReturn(true)->shouldBeCalledTimes(1);
            $this->store->get($parsedKey, $parsedIndex)->willReturn($value)->shouldBeCalledTimes(1);
        }

        $this->assertEquals($value, $this->assert->keyExists($key, $index));
    }
}

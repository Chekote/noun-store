<?php namespace Chekote\NounStore\Assert;

use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 * @covers \Chekote\NounStore\Assert::keyValueContains()
 * @covers \Chekote\NounStore\Assert::keyExists()
 */
class KeyValueContainsTest extends AssertTest
{
    public function testKeyIsParsedAndParsedValuesAreUsed()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $value = 'Some Value';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            $this->key->parse($key, $index)->willReturn([$parsedKey, $parsedIndex])->shouldBeCalledTimes(1);

            /* should not be necessary, but we can't mock out Assert::keyExists with Prophecy */
            $this->key->parse($parsedKey, $parsedIndex)->willReturn([$parsedKey, $parsedIndex])->shouldBeCalledTimes(1);
            $this->store->keyExists($parsedKey, $parsedIndex)->willReturn(true)->shouldBeCalledTimes(1);
            $this->store->get($parsedKey, $parsedIndex)->willReturn($value)->shouldBeCalledTimes(1);

            $this->store->keyValueContains($parsedKey, $value, $parsedIndex)->willReturn(true)->shouldBeCalledTimes(1);
        }

        $this->assert->keyValueContains($key, $value, $index);
    }

    public function testInvalidArgumentExceptionBubblesUpFromParse()
    {
        $key = '10th Thing';
        $index = 5;
        $value = 'Some Value';
        $exception = new InvalidArgumentException(
            "$index was provided for index param when key '$key' contains an nth value, but they do not match"
        );

        /* @noinspection PhpUndefinedMethodInspection */
        $this->key->parse($key, $index)->willThrow($exception)->shouldBeCalledTimes(1);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains($key, $value, $index);
    }

    public function testMissingKeyThrowsOutOfBoundsException()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $value = 'Some Value';
        $exception = new OutOfBoundsException("Entry '$key' was not found in the store.");

        /* @noinspection PhpUndefinedMethodInspection */
        {
            $this->key->parse($key, $index)->willReturn([$parsedKey, $parsedIndex]);

            /* should not be necessary, but we can't mock out Assert::keyExists with Prophecy */
            $this->key->parse($parsedKey, $parsedIndex)->willReturn([$parsedKey, $parsedIndex]);
            $this->store->keyExists($parsedKey, $parsedIndex)->willReturn(false);
            $this->key->build($parsedKey, $parsedIndex)->willReturn($key);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains($key, $value, $index);
    }

    public function testFailedMatchThrowsRuntimeException()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $expectedValue = 'Some Value';
        $storedValue = 'Something completely different';
        $exception = new RuntimeException("Entry '$key' does not contain '$expectedValue'");

        /* @noinspection PhpUndefinedMethodInspection */
        {
            $this->key->parse($key, $index)->willReturn([$parsedKey, $parsedIndex])->shouldBeCalledTimes(1);

            // mock out behavior of Assert::keyExists
            $this->key->parse($parsedKey, $parsedIndex)->willReturn([$parsedKey, $parsedIndex])->shouldBeCalledTimes(1);
            $this->store->keyExists($parsedKey, $parsedIndex)->willReturn(true)->shouldBeCalledTimes(1);
            $this->store->get($parsedKey, $parsedIndex)->willReturn($storedValue)->shouldBeCalledTimes(1);

            $this->store->keyValueContains($parsedKey, $expectedValue, $parsedIndex)->willReturn(false)->shouldBeCalledTimes(1);
            $this->key->build($parsedKey, $parsedIndex)->willReturn($key)->shouldBeCalledTimes(1);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains($key, $expectedValue, $index);
    }

    public function testSuccessfulMatchThrowsNoException()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $storedValue = 'This contains Some Value. Yes it does';
        $expectedValue = 'Some Value';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            $this->key->parse($key, $index)->willReturn([$parsedKey, $parsedIndex])->shouldBeCalledTimes(1);

            // mock out behavior of Assert::keyExists
            $this->key->parse($parsedKey, $parsedIndex)->willReturn([$parsedKey, $parsedIndex])->shouldBeCalledTimes(1);
            $this->store->keyExists($parsedKey, $parsedIndex)->willReturn(true)->shouldBeCalledTimes(1);
            $this->store->get($parsedKey, $parsedIndex)->willReturn($storedValue)->shouldBeCalledTimes(1);

            $this->store->keyValueContains($parsedKey, $expectedValue, $parsedIndex)->willReturn(true)->shouldBeCalledTimes(1);
        }

        $this->assert->keyValueContains($key, $expectedValue, $index);
    }
}

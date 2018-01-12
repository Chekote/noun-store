<?php namespace Chekote\NounStore\Assert;

use Chekote\Phake\Phake;
use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 * @covers \Chekote\NounStore\Assert::keyValueIs()
 */
class KeyValueIsTest extends AssertTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->assert)->keyValueIs(Phake::anyParameters())->thenCallParent();
    }

    public function testKeyIsParsedAndParsedValuesAreUsed()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $value = 'Some Value';

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->assert)->keyExists($parsedKey, $parsedIndex)->thenReturn(null);
            Phake::when($this->store)->get($parsedKey, $parsedIndex)->thenReturn($value);
        }

        $this->assert->keyValueIs($key, $value, $index);

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->assert)->keyExists($parsedKey, $parsedIndex);
            Phake::verify($this->store)->get($parsedKey, $parsedIndex);
        }
    }

    public function testInvalidArgumentExceptionBubblesUpFromParse()
    {
        $key = '10th Thing';
        $index = 5;
        $value = 'Some Value';
        $exception = new InvalidArgumentException(
            "$index was provided for index param when key '$key' contains an nth value, but they do not match"
        );

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenThrow($exception);

        $this->assertException($exception, function () use ($key, $value, $index) {
            $this->assert->keyValueIs($key, $value, $index);
        });

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::verify($this->key)->parse($key, $index);
    }

    public function testMissingKeyThrowsOutOfBoundsException()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $value = 'Some Value';
        $exception = new OutOfBoundsException("Entry '$key' was not found in the store.");

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->assert)->keyExists($parsedKey, $parsedIndex)->thenThrow($exception);
        }

        $this->assertException($exception, function () use ($key, $value, $index) {
            $this->assert->keyValueIs($key, $value, $index);
        });

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->assert)->keyExists($parsedKey, $parsedIndex);
        }
    }

    public function testFailedMatchThrowsRuntimeException()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $value = 'Some Value';
        $exception = new RuntimeException("Entry '$key' does not match '$value'");

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->assert)->keyExists($parsedKey, $parsedIndex)->thenReturn(null);
            Phake::when($this->store)->get($parsedKey, $parsedIndex)->thenReturn('Some Other Value');
            Phake::when($this->key)->build($parsedKey, $parsedIndex)->thenReturn($key);
        }

        $this->assertException($exception, function () use ($key, $value, $index) {
            $this->assert->keyValueIs($key, $value, $index);
        });

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->assert)->keyExists($parsedKey, $parsedIndex);
            Phake::verify($this->store)->get($parsedKey, $parsedIndex);
            Phake::verify($this->key)->build($parsedKey, $parsedIndex);
        }
    }

    public function testSuccessfulMatchThrowsNoException()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $value = 'Some Value';

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->assert)->keyExists($parsedKey, $parsedIndex)->thenReturn(null);
            Phake::when($this->store)->get($parsedKey, $parsedIndex)->thenReturn($value);
        }

        $this->assert->keyValueIs($key, $value, $index);

        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::verify($this->key)->parse($key, $index);
            Phake::verify($this->assert)->keyExists($parsedKey, $parsedIndex);
            Phake::verify($this->store)->get($parsedKey, $parsedIndex);
        }
    }
}

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

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->key, 1)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::expect($this->assert, 1)->keyExists($parsedKey, $parsedIndex)->thenReturn(null);
            Phake::expect($this->store, 1)->get($parsedKey, $parsedIndex)->thenReturn($value);
        }

        $this->assert->keyValueIs($key, $value, $index);
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
        Phake::expect($this->key, 1)->parse($key, $index)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueIs($key, $value, $index);
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
            Phake::expect($this->key, 1)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::expect($this->assert, 1)->keyExists($parsedKey, $parsedIndex)->thenThrow($exception);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueIs($key, $value, $index);
    }

    public function testFailedMatchThrowsRuntimeException()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $value = 'Some Value';
        $exception = new RuntimeException("Entry '$key' does not match '$value'");

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->key, 1)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::expect($this->assert, 1)->keyExists($parsedKey, $parsedIndex)->thenReturn(null);
            Phake::expect($this->store, 1)->get($parsedKey, $parsedIndex)->thenReturn('Some Other Value');
            Phake::expect($this->key, 1)->build($parsedKey, $parsedIndex)->thenReturn($key);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueIs($key, $value, $index);
    }

    public function testSuccessfulMatchThrowsNoException()
    {
        $key = '10th Thing';
        $index = null;
        $parsedKey = 'Thing';
        $parsedIndex = 9;
        $value = 'Some Value';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->key, 1)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::expect($this->assert, 1)->keyExists($parsedKey, $parsedIndex)->thenReturn(null);
            Phake::expect($this->store, 1)->get($parsedKey, $parsedIndex)->thenReturn($value);
        }

        $this->assert->keyValueIs($key, $value, $index);
    }
}

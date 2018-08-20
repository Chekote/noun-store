<?php namespace Chekote\NounStore\Assert;

use Chekote\NounStore\Key\KeyTest;
use Chekote\Phake\Phake;
use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 * @covers \Chekote\NounStore\Assert::keyValueContains()
 */
class KeyValueContainsTest extends AssertTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->assert)->keyValueContains(Phake::anyParameters())->thenCallParent();
    }

    public function testInvalidArgumentExceptionBubblesUpFromKeyExists()
    {
        $value = 'Some Value';
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists(KeyTest::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains(KeyTest::INVALID_KEY, $value);
    }

    // An invalid key should not get past keyExists(), so this should never actually be possible. But we test
    // the behavior here to ensure that our method behaves correctly should the impossible ever occur.
    public function testInvalidArgumentExceptionBubblesUpFromKeyValueContains()
    {
        $value = 'Some Value';
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->assert, 1)->keyExists(KeyTest::INVALID_KEY)->thenReturn(true);
            Phake::expect($this->store, 1)->keyValueContains(KeyTest::INVALID_KEY, $value)->thenThrow($exception);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains(KeyTest::INVALID_KEY, $value);
    }

    public function testMissingKeyThrowsOutOfBoundsException()
    {
        $key = '10th Thing';
        $value = 'Some Value';
        $exception = new OutOfBoundsException("Entry '$key' was not found in the store.");

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists($key)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains($key, $value);
    }

    public function testFailedMatchThrowsRuntimeException()
    {
        $key = '10th Thing';
        $value = 'Some Value';
        $exception = new RuntimeException("Entry '$key' does not contain '$value'");

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->assert, 1)->keyExists($key)->thenReturn(null);
            Phake::expect($this->store, 1)->keyValueContains($key, $value)->thenReturn(false);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains($key, $value);
    }

    public function testSuccessfulMatchThrowsNoException()
    {
        $key = '10th Thing';
        $value = 'Some Value';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->assert, 1)->keyExists($key)->thenReturn(null);
            Phake::expect($this->store, 1)->keyValueContains($key, $value)->thenReturn(true);
        }

        $this->assert->keyValueContains($key, $value);
    }
}

<?php namespace Unit\Chekote\NounStore\Assert;

use Chekote\NounStore\AssertionFailedException;
use InvalidArgumentException;
use OutOfBoundsException;
use Unit\Chekote\NounStore\Key\KeyTestCase;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Assert::keyValueContains()
 */
class KeyValueContainsTest extends AssertTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->assert)->keyValueContains(Phake::anyParameters())->thenCallParent();
    }

    public function testInvalidArgumentExceptionBubblesUpFromKeyExists(): void
    {
        $value = 'A Value';
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists(KeyTestCase::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains(KeyTestCase::INVALID_KEY, $value);
    }

    // An invalid key should not get past keyExists(), so this should never actually be possible. But we test
    // the behavior here to ensure that our method behaves correctly should the impossible ever occur.
    public function testInvalidArgumentExceptionBubblesUpFromKeyValueContains(): void
    {
        $value = 'Grape';
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->assert, 1)->keyExists(KeyTestCase::INVALID_KEY)->thenReturn(true);
            Phake::expect($this->store, 1)->keyValueContains(KeyTestCase::INVALID_KEY, $value)->thenThrow($exception);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains(KeyTestCase::INVALID_KEY, $value);
    }

    public function testMissingKeyThrowsOutOfBoundsException(): void
    {
        $key = '13th Thing';
        $value = 'Banana';
        $exception = new OutOfBoundsException("Entry '$key' was not found in the store.");

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists($key)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains($key, $value);
    }

    public function testFailedMatchThrowsRuntimeException(): void
    {
        $key = '14th Thing';
        $value = 'Strawberry';
        $exception = new AssertionFailedException("Entry '$key' does not contain '$value'");

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->assert, 1)->keyExists($key)->thenReturn(null);
            Phake::expect($this->store, 1)->keyValueContains($key, $value)->thenReturn(false);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueContains($key, $value);
    }

    public function testSuccessfulMatchThrowsNoException(): void
    {
        $key = '15th Thing';
        $value = 'Blackberry';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->assert, 1)->keyExists($key)->thenReturn(null);
            Phake::expect($this->store, 1)->keyValueContains($key, $value)->thenReturn(true);
        }

        $this->assert->keyValueContains($key, $value);
    }

    public function testSuccessfulMatchReturnsValue(): void
    {
        $key = '16th Thing';
        $value = 'Raspberry';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->assert, 1)->keyExists($key)->thenReturn($value);
            Phake::expect($this->store, 1)->keyValueContains($key, $value)->thenReturn(true);
        }

        $this->assertSame($value, $this->assert->keyValueContains($key, $value));
    }
}

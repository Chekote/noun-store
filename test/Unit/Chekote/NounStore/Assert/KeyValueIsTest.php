<?php namespace Unit\Chekote\NounStore\Assert;

use Chekote\NounStore\AssertionFailedException;
use InvalidArgumentException;
use OutOfBoundsException;
use Unit\Chekote\NounStore\Key\KeyTestCase;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Assert::keyValueIs()
 */
class KeyValueIsTest extends AssertTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->assert)->keyValueIs(Phake::anyParameters())->thenCallParent();
    }

    public function testInvalidArgumentExceptionBubblesUpFromKeyExists(): void
    {
        $value = 'Another Value';
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists(KeyTestCase::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueIs(KeyTestCase::INVALID_KEY, $value);
    }

    public function testMissingKeyThrowsOutOfBoundsException(): void
    {
        $key = '16th Thing';
        $value = 'Kiwi';
        $exception = new OutOfBoundsException("Entry '$key' was not found in the store.");

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists($key)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueIs($key, $value);
    }

    public function testFailedMatchThrowsRuntimeException(): void
    {
        $key = '17th Thing';
        $value = 'Orange';
        $exception = new AssertionFailedException("Entry '$key' does not match '$value'");

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists($key)->thenReturn('Some Other Value');

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueIs($key, $value);
    }

    public function testSuccessfulMatchThrowsNoException(): void
    {
        $key = '18th Thing';
        $value = 'Pear';

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists($key)->thenReturn($value);

        $this->assert->keyValueIs($key, $value);
    }
}

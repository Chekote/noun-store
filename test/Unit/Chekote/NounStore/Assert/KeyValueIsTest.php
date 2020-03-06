<?php namespace Unit\Chekote\NounStore\Assert;

use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;
use Unit\Chekote\NounStore\Key\KeyTest;
use Unit\Chekote\Phake\Phake;

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

    public function testInvalidArgumentExceptionBubblesUpFromKeyExists()
    {
        $value = 'Some Value';
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists(KeyTest::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueIs(KeyTest::INVALID_KEY, $value);
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

        $this->assert->keyValueIs($key, $value);
    }

    public function testFailedMatchThrowsRuntimeException()
    {
        $key = '10th Thing';
        $value = 'Some Value';
        $exception = new RuntimeException("Entry '$key' does not match '$value'");

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists($key)->thenReturn('Some Other Value');

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyValueIs($key, $value);
    }

    public function testSuccessfulMatchThrowsNoException()
    {
        $key = '10th Thing';
        $value = 'Some Value';

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists($key)->thenReturn($value);

        $this->assert->keyValueIs($key, $value);
    }
}

<?php namespace Unit\Chekote\NounStore\Assert;

use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;
use stdClass;
use Unit\Chekote\NounStore\Key\KeyTest;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Assert::keyIsClass()
 */
class KeyIsClassTest extends AssertTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->assert)->keyIsClass(Phake::anyParameters())->thenCallParent();
    }

    public function testInvalidArgumentExceptionBubblesUpFromKeyExists()
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists(KeyTest::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyIsClass(KeyTest::INVALID_KEY, stdClass::class);
    }

    // An invalid key should not get past keyExists(), so this should never actually be possible. But we test
    // the behavior here to ensure that our method behaves correctly should the impossible ever occur.
    public function testInvalidArgumentExceptionBubblesUpFromKeyValueContains()
    {
        $value = stdClass::class;
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->assert, 1)->keyExists(KeyTest::INVALID_KEY)->thenReturn(true);
            Phake::expect($this->store, 1)->keyIsClass(KeyTest::INVALID_KEY, $value)->thenThrow($exception);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyIsClass(KeyTest::INVALID_KEY, $value);
    }

    public function testMissingKeyThrowsOutOfBoundsException()
    {
        $key = '13th Thing';
        $value = stdClass::class;
        $exception = new OutOfBoundsException("Entry '$key' was not found in the store.");

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->assert, 1)->keyExists($key)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyIsClass($key, $value);
    }

    public function testFailedMatchThrowsRuntimeException()
    {
        $key = '14th Thing';
        $value = stdClass::class;
        $exception = new RuntimeException("Entry '$key' does not match instance of '$value'");
        $randomClass = $this;

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->assert, 1)->keyExists($key)->thenReturn($randomClass);
            Phake::expect($this->store, 1)->keyIsClass($key, $value)->thenReturn(false);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyIsClass($key, $value);
    }

    public function testSuccessfulMatchThrowsNoException()
    {
        $key = '15th Thing';
        $value = stdClass::class;

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->assert, 1)->keyExists($key)->thenReturn(new $value());
            Phake::expect($this->store, 1)->keyIsClass($key, $value)->thenReturn(true);
        }

        $this->assert->keyIsClass($key, $value);
    }
}

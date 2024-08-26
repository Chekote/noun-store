<?php namespace Unit\Chekote\NounStore\Assert;

use InvalidArgumentException;
use OutOfBoundsException;
use Unit\Chekote\NounStore\Key\KeyTest;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Assert::keyExists()
 */
class KeyExistsTest extends AssertTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->assert)->keyExists(Phake::anyParameters())->thenCallParent();
    }

    public function testKeyIsParsedAndParsedValuesAreUsed(): void
    {
        $key = '10th Thing';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->store, 1)->keyExists($key)->thenReturn(true);
            Phake::expect($this->store, 1)->get($key)->thenReturn('something');
        }

        $this->assert->keyExists($key);
    }

    public function testInvalidArgumentExceptionBubblesUpFromKeyExists(): void
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->keyExists(KeyTest::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyExists(KeyTest::INVALID_KEY);
    }

    // An invalid key should not get past keyExists(), so this should never actually be possible. But we test
    // the behavior here to ensure that our method behaves correctly should the impossible ever occur.
    public function testInvalidArgumentExceptionBubblesUpFromGet(): void
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->store, 1)->keyExists(KeyTest::INVALID_KEY)->thenReturn(true);
            Phake::expect($this->store, 1)->get(KeyTest::INVALID_KEY)->thenThrow($exception);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyExists(KeyTest::INVALID_KEY);
    }

    public function testMissingKeyThrowsOutOfBoundsException(): void
    {
        $key = '11th Thing';

        $exception = new OutOfBoundsException("Entry '$key' was not found in the store.");

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->keyExists($key)->thenReturn(false);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyExists($key);
    }

    public function testStoredValueIsReturned(): void
    {
        $key = '12th Thing';
        $value = 'Apple';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->store, 1)->keyExists($key)->thenReturn(true);
            Phake::expect($this->store, 1)->get($key)->thenReturn($value);
        }

        $this->assertEquals($value, $this->assert->keyExists($key));
    }
}

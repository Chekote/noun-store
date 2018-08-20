<?php namespace Chekote\NounStore\Assert;

use Chekote\NounStore\Key\KeyTest;
use Chekote\Phake\Phake;
use InvalidArgumentException;
use OutOfBoundsException;

/**
 * @covers \Chekote\NounStore\Assert::keyExists()
 */
class KeyExistsTest extends AssertTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->assert)->keyExists(Phake::anyParameters())->thenCallParent();
    }

    public function testKeyIsParsedAndParsedValuesAreUsed()
    {
        $key = '10th Thing';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->store, 1)->keyExists($key)->thenReturn(true);
            Phake::expect($this->store, 1)->get($key)->thenReturn('something');
        }

        $this->assert->keyExists($key);
    }

    public function testInvalidArgumentExceptionBubblesUpFromKeyExists()
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
    public function testInvalidArgumentExceptionBubblesUpFromGet()
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

    public function testMissingKeyThrowsOutOfBoundsException()
    {
        $key = '10th Thing';

        $exception = new OutOfBoundsException("Entry '$key' was not found in the store.");

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->keyExists($key)->thenReturn(false);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->assert->keyExists($key);
    }

    public function testStoredValueIsReturned()
    {
        $key = '10th Thing';
        $value = 'Some Value';

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->store, 1)->keyExists($key)->thenReturn(true);
            Phake::expect($this->store, 1)->get($key)->thenReturn($value);
        }

        $this->assertEquals($value, $this->assert->keyExists($key));
    }
}

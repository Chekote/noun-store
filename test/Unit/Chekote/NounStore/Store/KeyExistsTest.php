<?php namespace Unit\Chekote\NounStore\Store;

use InvalidArgumentException;
use Unit\Chekote\NounStore\Key\KeyTestCase;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Store::keyExists()
 */
class KeyExistsTest extends StoreTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->keyExists(Phake::anyParameters())->thenCallParent();
    }

    public function testInvalidArgumentExceptionBubblesUpFromGet(): void
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->get(KeyTestCase::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->keyExists(KeyTestCase::INVALID_KEY);
    }

    public static function returnDataProvider(): array
    {
        return [
            // key,               value                              exists?
            [ 'No such key',      null,                              false ], // missing key
            [ StoreTestCase::KEY, StoreTestCase::$MOST_RECENT_VALUE, true  ], // present key
        ];
    }

    /**
     * @dataProvider returnDataProvider
     * @param string $key    the key to check for existence of.
     * @param mixed  $value  the value that the mocked Store::get() should return.
     * @param bool   $exists the expected result from keyExists().
     */
    public function testReturn($key, $value, $exists): void
    {
        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->get($key)->thenReturn($value);

        $this->assertEquals($exists, $this->store->keyExists($key));
    }
}

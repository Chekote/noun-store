<?php namespace Unit\Chekote\NounStore\Store;

use InvalidArgumentException;
use Unit\Chekote\NounStore\Key\KeyTest;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Store::keyExists()
 */
class KeyExistsTest extends StoreTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->keyExists(Phake::anyParameters())->thenCallParent();
    }

    public function testInvalidArgumentExceptionBubblesUpFromGet()
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->get(KeyTest::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->keyExists(KeyTest::INVALID_KEY);
    }

    public function returnDataProvider()
    {
        return [
            // key,           value                          exists?
            [ 'No such key',  null,                          false ], // missing key
            [ StoreTest::KEY, StoreTest::$MOST_RECENT_VALUE, true  ], // present key
        ];
    }

    /**
     * @dataProvider returnDataProvider
     * @param string $key    the key to check for existence of.
     * @param mixed  $value  the value that the mocked Store::get() should return.
     * @param bool   $exists the expected result from keyExists().
     */
    public function testReturn($key, $value, $exists)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->get($key)->thenReturn($value);

        $this->assertEquals($exists, $this->store->keyExists($key));
    }
}

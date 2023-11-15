<?php namespace Unit\Chekote\NounStore\Store;

use InvalidArgumentException;
use stdClass;
use Unit\Chekote\NounStore\Key\KeyTest;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Store::keyIsClass()
 */
class KeyIsClassTest extends StoreTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->keyIsClass(Phake::anyParameters())->thenCallParent();
    }

    public function testInvalidArgumentExceptionBubblesUpFromGet()
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->get(KeyTest::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->keyIsClass(KeyTest::INVALID_KEY, stdClass::class);
    }

    public function returnDataProvider()
    {
        return [
            // storedValue,      checkedValue, expectedResult
            [ new stdClass(), stdClass::class,        true   ],
            [ new stdClass(), KeyIsClassTest::class,  false  ],
        ];
    }

    /**
     * @dataProvider returnDataProvider
     * @param string $storedValue    the value that should be in the store and will be returned by the mocked get()
     * @param string $checkedValue   the value that will be passed to keyIsClass()
     * @param bool   $expectedResult the expected results from keyIsClass()
     */
    public function testReturn($storedValue, $checkedValue, $expectedResult)
    {
        $key = StoreTest::KEY;
        $parsedKey = $key;

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->get($parsedKey)->thenReturn($storedValue);

        $this->assertEquals($expectedResult, $this->store->keyIsClass($key, $checkedValue));
    }
}

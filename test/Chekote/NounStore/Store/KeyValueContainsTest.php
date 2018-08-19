<?php namespace Chekote\NounStore\Store;

use Chekote\Phake\Phake;
use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Store::keyValueContains()
 */
class KeyValueContainsTest extends StoreTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->keyValueContains(Phake::anyParameters())->thenCallParent();
    }

    public function testInvalidArgumentExceptionBubblesUpFromGet()
    {
        $key = "10th Thing's doodad";
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->get($key)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->keyValueContains($key, "Doesn't matter");
    }

    public function returnDataProvider()
    {
        return [
        //    storedValue,       checkedValue, expectedResult
            [ 'This is a value', 'is a',       true           ],
            [ 'This is a value', 'words',      false          ],
        ];
    }

    /**
     * @dataProvider returnDataProvider
     * @param string $storedValue    the value that should be in the store and will be returned by the mocked get()
     * @param string $checkedValue   the value that will be passed to keyValueContains()
     * @param bool   $expectedResult the expected results from keyExists()
     */
    public function testReturn($storedValue, $checkedValue, $expectedResult)
    {
        $key = StoreTest::KEY;
        $parsedKey = $key;
        $parsedIndex = null;

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->get($parsedKey)->thenReturn($storedValue);

        $this->assertEquals($expectedResult, $this->store->keyValueContains($key, $checkedValue));
    }
}

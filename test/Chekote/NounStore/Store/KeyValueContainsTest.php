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

    public function testKeyIsParsedAndParsedValuesAreUsed()
    {
        $key = '2nd ' . StoreTest::KEY;
        $index = null;
        $parsedKey = StoreTest::KEY;
        $parsedIndex = 1;
        $value = substr(self::SECOND_VALUE, 0, 2);

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->key, 1)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::expect($this->store, 1)->get($parsedKey, $parsedIndex)->thenReturn(self::SECOND_VALUE);
        }

        $this->assertTrue($this->store->keyValueContains($key, $value, $index));
    }

    public function testInvalidArgumentExceptionBubblesUpFromParse()
    {
        $key = '10th Thing';
        $index = 5;
        $exception = new InvalidArgumentException(
            "$index was provided for index param when key '$key' contains an nth value, but they do not match"
        );

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse($key, $index)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->keyValueContains($key, "Doesn't matter", $index);
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
        $index = null;
        $parsedKey = $key;
        $parsedIndex = $index;

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->key, 1)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::expect($this->store, 1)->get($parsedKey, $parsedIndex)->thenReturn($storedValue);
        }

        $this->assertEquals($expectedResult, $this->store->keyValueContains($key, $checkedValue, $index));
    }
}

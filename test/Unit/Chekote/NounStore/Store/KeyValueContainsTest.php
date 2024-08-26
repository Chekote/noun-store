<?php namespace Unit\Chekote\NounStore\Store;

use InvalidArgumentException;
use Unit\Chekote\NounStore\Key\KeyTestCase;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Store::keyValueContains()
 */
class KeyValueContainsTest extends StoreTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->keyValueContains(Phake::anyParameters())->thenCallParent();
    }

    public function testInvalidArgumentExceptionBubblesUpFromGet(): void
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->get(KeyTestCase::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->keyValueContains(KeyTestCase::INVALID_KEY, "Doesn't matter");
    }

    public static function returnDataProvider(): array
    {
        return [
            // storedValue,      checkedValue, expectedResult
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
    public function testReturn($storedValue, $checkedValue, $expectedResult): void
    {
        $key = StoreTestCase::KEY;
        $parsedKey = $key;

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->get($parsedKey)->thenReturn($storedValue);

        $this->assertEquals($expectedResult, $this->store->keyValueContains($key, $checkedValue));
    }
}

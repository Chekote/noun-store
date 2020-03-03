<?php namespace Unit\Chekote\NounStore\Store;

use InvalidArgumentException;
use Unit\Chekote\NounStore\Key\KeyTest;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Store::get()
 */
class GetTest extends StoreTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->get(Phake::anyParameters())->thenCallParent();
    }

    public function testKeyIsParsedAndParsedValuesAreUsed()
    {
        $key = '2nd ' . StoreTest::KEY;
        $parsedKey = StoreTest::KEY;
        $parsedIndex = 1;

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->store, 1)->keyExists($key)->thenReturn(true);
            Phake::expect($this->key, 1)->parse($key)->thenReturn([[$parsedKey, $parsedIndex]]);
        }

        $this->assertEquals(StoreTest::SECOND_VALUE, $this->store->get($key));
    }

    public function testInvalidArgumentExceptionBubblesUpFromKeyExists()
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->keyExists(KeyTest::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->get(KeyTest::INVALID_KEY);
    }

    // If the Key service is behaving properly, this should never actually be possible. But we test the behavior
    // here to ensure that our method behaves correctly should the impossible ever occur.
    public function testInvalidArgumentExceptionBubblesUpFromParse()
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->store, 1)->keyExists(KeyTest::INVALID_KEY)->thenReturn(true);
            Phake::expect($this->key, 1)->parse(KeyTest::INVALID_KEY)->thenThrow($exception);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->get(KeyTest::INVALID_KEY);
    }

    public function testReturnsNullWhenKeyDoesNotExist()
    {
        $key = '3rd ' . StoreTest::KEY;

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->store, 1)->keyExists($key)->thenReturn(false);

        $this->assertNull($this->store->get($key));
    }

    /**
     * @dataProvider happyPathProvider
     * @param string  $key       the key to fetch.
     * @param array[] $parsedKey the parsed key.
     * @param mixed   $expected  the expected value.
     */
    public function testHappyPath($key, $parsedKey, $expected)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->store, 1)->keyExists($key)->thenReturn(true);
            Phake::expect($this->key, 1)->parse($key)->thenReturn($parsedKey);
        }

        $this->assertEquals($expected, $this->store->get($key));
    }

    public function happyPathProvider()
    {
        return [
            //                                                key                      parsed key                expected
            'Noun without index returns most recent noun' => [StoreTest::KEY,          [[StoreTest::KEY, null]], StoreTest::MOST_RECENT_VALUE],
            'Noun with index returns specific noun'       => ['1st ' . StoreTest::KEY, [[StoreTest::KEY,    0]], StoreTest::FIRST_VALUE],
        ];
    }
}

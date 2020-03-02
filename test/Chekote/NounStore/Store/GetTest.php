<?php namespace Chekote\NounStore\Store;

use Chekote\NounStore\Key\KeyTest;
use Chekote\Phake\Phake;
use InvalidArgumentException;

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
        Phake::expect($this->key, 1)->parse($key)->thenReturn("$parsedKey.$parsedIndex");

        $this->assertEquals(StoreTest::SECOND_VALUE, $this->store->get($key));
    }

    public function testInvalidArgumentExceptionBubblesUpFromParse()
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse(KeyTest::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->get(KeyTest::INVALID_KEY);
    }

    public function testReturnsNullWhenKeyDoesNotExist()
    {
        $key = '3rd ' . StoreTest::KEY;
        $dotPath = StoreTest::KEY . '.2';

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse($key)->thenReturn($dotPath);

        $this->assertNull($this->store->get($key));
    }

    public function testLastItemIsReturnedWhenParsedIndexIsNull()
    {
        $key = StoreTest::KEY;
        $dotPath = $key;

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse($key)->thenReturn($dotPath);

        $this->assertEquals(StoreTest::SECOND_VALUE, $this->store->get($key));
    }

    public function testIndexItemIsReturnedWhenParsedIndexIsNotNull()
    {
        $key = '1st ' . StoreTest::KEY;
        $dotPath = StoreTest::KEY . '.0';

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse($key)->thenReturn($dotPath);

        $this->assertEquals(StoreTest::FIRST_VALUE, $this->store->get($key));
    }
}

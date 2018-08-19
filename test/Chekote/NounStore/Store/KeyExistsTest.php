<?php namespace Chekote\NounStore\Store;

use Chekote\Phake\Phake;
use InvalidArgumentException;

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

    public function testInvalidArgumentExceptionBubblesUpFromParse()
    {
        $key = "Thing's doodle flip";
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse($key)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->keyExists($key);
    }

    public function returnDataProvider()
    {
        return [
        //    key,            expectedResult
            [ 'No such key',  false ], // missing key
            [ StoreTest::KEY, true  ], // present key
        ];
    }

    /**
     * @dataProvider returnDataProvider
     * @param string $key            the key to pass to keyExists, and which will be returned from the mocked parse()
     * @param bool   $expectedResult the expected results from keyExists()
     */
    public function testReturn($key, $expectedResult)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse($key)->thenReturn([$key, null]);

        $this->assertEquals($expectedResult, $this->store->keyExists($key));
    }
}

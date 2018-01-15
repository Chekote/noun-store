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
        $key = '10th Thing';
        $index = 5;
        $exception = new InvalidArgumentException(
            "$index was provided for index param when key '$key' contains an nth value, but they do not match"
        );

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenThrow($exception);

        $this->assertException($exception, function () use ($key, $index) {
            $this->store->keyExists($key, $index);
        });

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::verify($this->key)->parse($key, $index);
    }

    public function returnDataProvider()
    {
        return [
        //    key,            index, expectedResult
            [ 'No such key',   null, false ], // missing key
            [ StoreTest::KEY,     2, false ], // missing index
            [ StoreTest::KEY,  null, true  ], // present key
            [ StoreTest::KEY,     1, true  ], // present index
        ];
    }

    /**
     * @dataProvider returnDataProvider
     * @param string $key            the key to pass to keyExists, and which will be returned from the mocked parse()
     * @param int    $index          the index to pass to KeyExists, and which will be returned from the mocked parse()
     * @param bool   $expectedResult the expected results from keyExists()
     */
    public function testReturn(string $key, ?int $index, bool $expectedResult)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenReturn([$key, $index]);

        $this->assertEquals($expectedResult, $this->store->keyExists($key, $index));

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::verify($this->key)->parse($key, $index);
    }
}

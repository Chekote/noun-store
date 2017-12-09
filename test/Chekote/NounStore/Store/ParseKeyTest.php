<?php namespace Chekote\NounStore\Store;

use Chekote\NounStore\Store;
use InvalidArgumentException;
use ReflectionClass;

/**
 * @covers Store::parseKey()
 */
class ParseKeyTest extends StoreTest
{
    /**
     * Provides examples of valid key and nth pairs with expected parse results.
     *
     * @return array
     */
    public function validKeyAndNthCombinationsDataProvider()
    {
        return [
        //   key             nth   parseKey parseNth
            ['Thing',       null, 'Thing',     null], // no nth in key or nth param
            ['1st Thing',   null, 'Thing',        0], // 1st in key with no nth param
            ['1st Thing',      0, 'Thing',        0], // nth in key with matching nth param
            ['2nd Thing',   null, 'Thing',        1], // 2nd in key with no nth param
            ['3rd Thing',   null, 'Thing',        2], // 3rd in key with no nth param
            ['4th Thing',   null, 'Thing',        3], // 3th in key with no nth param
            ['478th Thing', null, 'Thing',      477], // high nth in key with no nth param
            ['Thing',          0, 'Thing',        0], // no nth in key with 0 nth param
            ['Thing',         49, 'Thing',       49], // no nth in key with high nth param
        ];
    }

    /**
     * Tests that calling Store::parseKey with valid key and nth combinations works correctly.
     *
     * @dataProvider validKeyAndNthCombinationsDataProvider
     * @param string $key       the key to parse
     * @param int    $nth       the nth to pass along with the key
     * @param string $parsedKey the expected resulting parsed key
     * @param int    $parsedNth the expected resulting parsed nth
     */
    public function testParseKeyParsesValidKeysAndNthCombinations($key, $nth, $parsedKey, $parsedNth)
    {
        $parseKey = (new ReflectionClass(Store::class))->getMethod('parseKey');
        $parseKey->setAccessible(true);

        list($actualKey, $actualNth) = $parseKey->invoke($this->store, $key, $nth);

        $this->assertEquals($parsedKey, $actualKey);
        $this->assertEquals($parsedNth, $actualNth);
    }

    /**
     * Provides examples of mismatched key & nth pairs.
     *
     * @return array
     */
    public function mismatchedKeyAndNthDataProvider()
    {
        return [
            ['1st Thing', 1],
            ['1st Thing', 2],
            ['4th Person', 0],
            ['4th Person', 4],
            ['4th Person', 10],
        ];
    }

    /**
     * Tests that calling Store::parseKey with mismatched key and nth param throws an exception.
     *
     * @dataProvider mismatchedKeyAndNthDataProvider
     * @param string $key the key to parse
     * @param string $nth the mismatched nth to pass along with the key
     */
    public function testParseKeyThrowsExceptionIfKeyAndNthMismatch($key, $nth)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "$nth was provided for nth param when key '$key' contains an nth value, but they do not match"
        );

        $parseKey = (new ReflectionClass(Store::class))->getMethod('parseKey');
        $parseKey->setAccessible(true);

        $parseKey->invoke($this->store, $key, $nth);
    }
}

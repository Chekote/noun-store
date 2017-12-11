<?php namespace Chekote\NounStore\Store;

use InvalidArgumentException;

class ParseKeyTest extends StoreTest
{
    /**
     * Provides examples of valid key and index pairs with expected parse results.
     *
     * @return array
     */
    public function validKeyAndIndexCombinationsDataProvider()
    {
        return [
        //   key           index   parseKey parseIndex
            ['Thing',       null, 'Thing',     null], // no nth in key or index param
            ['1st Thing',   null, 'Thing',        0], // 1st in key with no index param
            ['1st Thing',      0, 'Thing',        0], // nth in key with matching index param
            ['2nd Thing',   null, 'Thing',        1], // 2nd in key with no index param
            ['3rd Thing',   null, 'Thing',        2], // 3rd in key with no index param
            ['4th Thing',   null, 'Thing',        3], // 3th in key with no index param
            ['478th Thing', null, 'Thing',      477], // high nth in key with no index param
            ['Thing',          0, 'Thing',        0], // no nth in key with 0 index param
            ['Thing',         49, 'Thing',       49], // no nth in key with high index param
        ];
    }

    /**
     * Tests that calling Store::parseKey with valid key and index combinations works correctly.
     *
     * @dataProvider validKeyAndIndexCombinationsDataProvider
     * @param string $key         the key to parse
     * @param int    $index       the index to pass along with the key
     * @param string $parsedKey   the expected resulting parsed key
     * @param int    $parsedIndex the expected resulting parsed index
     */
    public function testParseKeyParsesValidKeysAndNthCombinations($key, $index, $parsedKey, $parsedIndex)
    {
        $parseKey = $this->makeMethodAccessible('parseKey');

        list($actualKey, $actualIndex) = $parseKey->invoke($this->store, $key, $index);

        $this->assertEquals($parsedKey, $actualKey);
        $this->assertEquals($parsedIndex, $actualIndex);
    }

    /**
     * Provides examples of mismatched key & index pairs.
     *
     * @return array
     */
    public function mismatchedKeyAndIndexDataProvider()
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
     * Tests that calling Store::parseKey with mismatched key and index param throws an exception.
     *
     * @dataProvider mismatchedKeyAndIndexDataProvider
     * @param string $key   the key to parse
     * @param string $index the mismatched index to pass along with the key
     */
    public function testParseKeyThrowsExceptionIfKeyAndIndexMismatch($key, $index)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "$index was provided for index param when key '$key' contains an nth value, but they do not match"
        );

        $parseKey = $this->makeMethodAccessible('parseKey');

        $parseKey->invoke($this->store, $key, $index);
    }
}

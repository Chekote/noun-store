<?php namespace Chekote\NounStore\Key;

/**
 * @covers \Chekote\NounStore\Key::build()
 * @covers \Chekote\NounStore\Key::getOrdinal()
 * @covers \Chekote\NounStore\Singleton::getInstance()
 */
class BuildTest extends KeyTest
{
    /**
     * Tests that calling the method with valid key and index combinations works correctly.
     *
     * @dataProvider validKeyAndIndexCombinationsDataProvider
     * @param string $key      the key to use for the build
     * @param int    $index    the index to use for the build
     * @param string $expected the expected resulting key
     */
    public function testBuildKeyBuildsValidKeyAndIndexCombinations($key, $index, $expected)
    {
        $actual = $this->key->build($key, $index);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Provides examples of valid key and index pairs with expected build results.
     *
     * @return array
     */
    public function validKeyAndIndexCombinationsDataProvider()
    {
        return [
            // key   index  expected result
            ['Thing', null, 'Thing'],
            ['Thing',    0, '1st Thing'],
            ['Thing',    1, '2nd Thing'],
            ['Thing',    2, '3rd Thing'],
            ['Thing',    3, '4th Thing'],
            ['Thing',    4, '5th Thing'],
            ['Thing',  477, '478th Thing'],
        ];
    }
}

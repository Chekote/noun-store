<?php namespace Chekote\NounStore\Key;
use Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Key::build()
 */
class BuildTest extends KeyTest
{
    public function setUp() {
        parent::setUp();

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->build(Phake::anyParameters())->thenCallParent();
    }

    /**
     * Tests that calling the method with valid key and index combinations works correctly.
     *
     * @dataProvider validKeyAndIndexCombinationsDataProvider
     * @param string $key      the key to use for the build
     * @param int    $index    the index to use for the build
     * @param int    $nth      the nth that we expect build to pass to getOrdinal()
     * @param string $ordinal  the ordinal that the mocked getOrdinal() should return
     * @param string $expected the expected resulting key
     */
    public function testBuildKeyBuildsValidKeyAndIndexCombinations($key, $index, $nth, $ordinal, $expected)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        Phake::when($this->key)->getOrdinal($nth)->thenReturn($ordinal);

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
            // key   index  nth,  ordinal, expected result
            ['Thing', null, null, null,    'Thing'],
            ['Thing',    0,    1, 'st',    '1st Thing'],
            ['Thing',    1,    2, 'nd',    '2nd Thing'],
            ['Thing',    2,    3, 'rd',    '3rd Thing'],
            ['Thing',    3,    4, 'th',    '4th Thing'],
            ['Thing',    4,    5, 'th',    '5th Thing'],
            ['Thing',  477,  478, 'th',    '478th Thing'],
        ];
    }
}

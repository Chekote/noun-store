<?php namespace Chekote\NounStore\Key;

use Chekote\Phake\Phake;
use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Key::parse()
 */
class ParseTest extends KeyTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse(Phake::anyParameters())->thenCallParent();
    }

    /**
     * Provides examples of valid key and index pairs with expected parse results.
     *
     * @return array
     */
    public function successScenarioDataProvider()
    {
        return [
        //   key             parsedKey, parsedIndex
            ['Thing',       'Thing',           null],
            ['1st Thing',   'Thing',              0],
            ['2nd Thing',   'Thing',              1],
            ['3rd Thing',   'Thing',              2],
            ['4th Thing',   'Thing',              3],
            ['478th Thing', 'Thing',            477],
        ];
    }

    /**
     * Tests that calling Key::parse with valid key works correctly.
     *
     * @dataProvider successScenarioDataProvider
     * @param string $key         the key to parse
     * @param string $parsedKey   the expected resulting parsed key
     * @param int    $parsedIndex the expected resulting parsed index
     */
    public function testSuccessScenario($key, $parsedKey, $parsedIndex)
    {
        $this->assertEquals([$parsedKey, $parsedIndex], $this->key->parse($key));
    }

    /**
     * Provides examples of invalid keys.
     *
     * @return array
     */
    public function invalidKeyDataProvider()
    {
        return [
            ["Thing's stuff"],
            ["1st Thing's thingamajig"],
        ];
    }

    /**
     * Tests that calling Key::parse with an invalid key throws an exception.
     *
     * @dataProvider invalidKeyDataProvider
     * @param string $key the key to parse
     */
    public function testParseKeyThrowsExceptionIfKeyAndIndexMismatch($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key syntax is invalid');

        $this->key->parse($key);
    }
}

<?php namespace Unit\Chekote\NounStore\Key;

use InvalidArgumentException;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Key::parseNoun()
 */
class ParseNounTest extends KeyTest
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parseNoun(Phake::anyParameters())->thenCallParent();
    }

    /**
     * Provides examples of valid key and index pairs with expected parse results.
     *
     * @return array
     */
    public static function successScenarioDataProvider(): array
    {
        return [
            // key          parsedKey,  parsedIndex
            ['Thing',       'Thing',           null],
            ['1st Thing',   'Thing',              0],
            ['2nd Thing',   'Thing',              1],
            ['3rd Thing',   'Thing',              2],
            ['4th Thing',   'Thing',              3],
            ['10th Thing',  'Thing',              9],
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
    public function testSuccessScenario($key, $parsedKey, $parsedIndex): void
    {
        /* @noinspection PhpUndefinedMethodInspection */
        $this->assertEquals(
            [$parsedKey, $parsedIndex],
            Phake::makeVisible($this->key)->parseNoun($key)
        );
    }

    /**
     * Provides examples of invalid keys.
     *
     * @return array
     */
    public static function invalidKeyDataProvider(): array
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
    public function testParseKeyThrowsExceptionIfKeyAndIndexMismatch($key): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::makeVisible($this->key)->parseNoun($key);
    }
}

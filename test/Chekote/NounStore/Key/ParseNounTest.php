<?php namespace Chekote\NounStore\Key;

use Chekote\Phake\Phake;
use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Key::parseNoun()
 */
class ParseNounTest extends KeyTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parseNoun(Phake::anyParameters())->thenCallParent();
    }

    /**
     * Provides examples of valid key and nth pairs with expected dotPath results.
     *
     * @return array
     */
    public function successScenarioDataProvider()
    {
        return [
            // key          dotPath
            ['Thing',       'Thing'    ],
            ['1st Thing',   'Thing.0'  ],
            ['2nd Thing',   'Thing.1'  ],
            ['3rd Thing',   'Thing.2'  ],
            ['4th Thing',   'Thing.3'  ],
            ['478th Thing', 'Thing.477'],
        ];
    }

    /**
     * Tests that calling Key::parse with valid key works correctly.
     *
     * @dataProvider successScenarioDataProvider
     * @param string $key     the key to parse
     * @param string $dotPath the expected resulting dot path
     */
    public function testSuccessScenario($key, $dotPath)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertEquals($dotPath, Phake::makeVisible($this->key)->parseNoun($key));
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

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::makeVisible($this->key)->parseNoun($key);
    }
}

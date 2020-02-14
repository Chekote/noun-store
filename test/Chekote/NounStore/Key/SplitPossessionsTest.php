<?php namespace Chekote\NounStore\Key;

use Chekote\Phake\Phake;
//use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Key::splitPossessions()
 */
class SplitPossessionsTest extends KeyTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->splitPossessions(Phake::anyParameters())->thenCallParent();
    }

    /**
     * @dataProvider nouns
     */
    public function testSuccessScenario($key, array $parts)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        $this->assertSame(
            $parts,
            Phake::makeVisible($this->key)->splitPossessions($key)
        );
    }

    /**
     * Data provider of possessive nouns and their component nouns.
     *
     * @return array[]
     */
    public function nouns()
    {
        return [
            ['Customer',                       ['Customer'                        ]],
            ["Customer's Car",                 ['Customer',     'Car'             ]],
            ["8th Customer's Car",             ['8th Customer', 'Car'             ]],
            ["Customer's 2nd Car",             ['Customer',     '2nd Car'         ]],
            ["7th Customer's 4th Car",         ['7th Customer', '4th Car'         ]],
            ["7th Customer's 4th Car's Wheel", ['7th Customer', '4th Car', 'Wheel']],
        ];
    }
}

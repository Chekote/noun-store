<?php namespace Unit\Chekote\NounStore\Key;

use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Key::splitPossessions()
 */
class SplitPossessionsTest extends KeyTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->splitPossessions(Phake::anyParameters())->thenCallParent();
    }

    /**
     * @dataProvider nouns
     */
    public function testSuccessScenario(string $key, array $parts): void
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
    public static function nouns(): array
    {
        return [
            ['Frenchman',                      ['Frenchman'                       ]],
            ["Dude's Car",                     ['Dude',         'Car'             ]],
            ["8th Customer's Car",             ['8th Customer', 'Car'             ]],
            ["Lad's 2nd Car",                  ['Lad',          '2nd Car'         ]],
            ["7th Customer's 4th Car",         ['7th Customer', '4th Car'         ]],
            ["7th Customer's 4th Car's Wheel", ['7th Customer', '4th Car', 'Wheel']],
        ];
    }
}

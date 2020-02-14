<?php namespace Chekote\NounStore\Key;

use Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Key::splitPossessions()
 */
class SplitPossessionsTest extends KeyTest
{
    /** @var string */
    const KEY = 'Customer';

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
            [self::KEY,                                 [self::KEY                             ]],
            [self::KEY . "'s Car",                      [self::KEY,          'Car'             ]],
            ['8th ' . self::KEY . "'s Car",             ['8th ' . self::KEY, 'Car'             ]],
            [self::KEY . "'s 2nd Car",                  [self::KEY,          '2nd Car'         ]],
            ['7th ' . self::KEY . "'s 4th Car",         ['7th ' . self::KEY, '4th Car'         ]],
            ['7th ' . self::KEY . "'s 4th Car's Wheel", ['7th ' . self::KEY, '4th Car', 'Wheel']],
        ];
    }
}

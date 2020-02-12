<?php namespace Chekote\NounStore\Key;

use Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Key::isPossessive()
 */
class IsPossessiveTest extends KeyTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->isPossessive(Phake::anyParameters())->thenCallParent();
    }

    /**
     * @dataProvider possessiveNouns
     */
    public function testReturnsTrueForPossessiveNoun($noun)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        $this->assertTrue(
            Phake::makeVisible($this->key)->isPossessive($noun),
            "'$noun' should be considered a possessive noun"
        );
    }

    /**
     * Data provider of possessive nouns.
     *
     * @return array[]
     */
    public function possessiveNouns()
    {
        return [
            ["Customer's Car"],
            ["8th Customer's Car"],
            ["Customer's 2nd Car"],
            ["7th Customer's 4th Car"],
        ];
    }

    /**
     * @dataProvider nonPossessiveNouns
     */
    public function testReturnsFalseForNonPossessiveNoun($noun)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        $this->assertFalse(
            Phake::makeVisible($this->key)->isPossessive($noun),
            "'$noun' should not be considered a possessive noun"
        );
    }

    /**
     * Data provider of non possessive nouns.
     *
     * @return array[]
     */
    public function nonPossessiveNouns()
    {
        return [
            ['Item'],
            ['1st Item'],
        ];
    }
}

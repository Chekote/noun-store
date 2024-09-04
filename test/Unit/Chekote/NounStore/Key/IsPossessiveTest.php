<?php namespace Unit\Chekote\NounStore\Key;

use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Key::isPossessive()
 */
class IsPossessiveTest extends KeyTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->isPossessive(Phake::anyParameters())->thenCallParent();
    }

    /**
     * @dataProvider possessiveNouns
     */
    public function testReturnsTrueForPossessiveNoun($noun): void
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
    public static function possessiveNouns(): array
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
    public function testReturnsFalseForNonPossessiveNoun($noun): void
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
    public static function nonPossessiveNouns(): array
    {
        return [
            ['Item'],
            ['1st Item'],
        ];
    }
}

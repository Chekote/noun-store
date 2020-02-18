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

    public function testHappyPath()
    {
        $key = "2nd Customer's 4th Car";
        $splitNouns = ['2nd Customer', '4th Car'];
        $parsedKey = [['Customer', 1], ['Car', 3]];

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->key, 1)->splitPossessions($key)->thenReturn($splitNouns);
            Phake::expect($this->key, 1)->parseNoun($splitNouns[0])->thenReturn($parsedKey[0]);
            Phake::expect($this->key, 1)->parseNoun($splitNouns[1])->thenReturn($parsedKey[1]);
        }

        $this->assertEquals($parsedKey, $this->key->parse($key));
    }

    public function testInvalidArgumentExceptionBubblesUpFromParseNoun()
    {
        $invalidKey = "Customer's's Car";
        $splitNouns = ["Customer's", 'Car'];
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::expect($this->key, 1)->splitPossessions($invalidKey)->thenReturn($splitNouns);
            Phake::expect($this->key, 1)->parseNoun($splitNouns[0])->thenThrow($exception);
        }

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->key->parse($invalidKey);
    }
}

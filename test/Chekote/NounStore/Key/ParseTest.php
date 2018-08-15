<?php namespace Chekote\NounStore\Key;

use Chekote\NounStore\Key;
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

    public function testInvalidKeyThrowsInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key is not valid. Must match pattern ' . Key::REGEX_KEY);

        $this->key->parse("'''''");
    }

    public function testValidKeyIsProcessed()
    {
        $key = "10th thing's address";
        $index = null;

        $pregResult = ["10th thing's address", '10th ', '10', 'thing', "'s address", 'address'];
        $processResult = ['thing', 9, 'address'];

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->processMatches($index, $pregResult)->thenReturn($processResult);

        $this->assertEquals($processResult, $this->key->parse($key, $index));
    }

    public function testInvalidArgumentExceptionBubblesUpFromProcessMatches()
    {
        $exception = new InvalidArgumentException('nth must be equal to or larger than 1');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->processMatches(Phake::anyParameters())->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->key->parse('0th thing');
    }
}

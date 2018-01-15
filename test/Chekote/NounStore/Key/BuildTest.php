<?php namespace Chekote\NounStore\Key;

use Chekote\Phake\Phake;
use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Key::build()
 */
class BuildTest extends KeyTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->build(Phake::anyParameters())->thenCallParent();
    }

    public function testNullIndexReturnsUnmodifiedKey()
    {
        $key = 'Thing';

        $this->assertEquals($key, $this->key->build($key, null));
    }

    public function testNonNullIndexReturnsModifiedKey()
    {
        $key = 'Thing';
        $index = 9;
        $nth = $index + 1;

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->getOrdinal($nth)->thenReturn('th');

        $this->assertEquals('10th Thing', $this->key->build($key, $index));

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::verify($this->key)->getOrdinal($nth);
    }

    public function testInvalidArgumentExceptionBubblesUpFromGetOrdinal()
    {
        $key = 'Thing';
        $index = -2;
        $nth = $index + 1;

        $exception = new InvalidArgumentException('$nth must be a positive number');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->getOrdinal($nth)->thenThrow($exception);

        $this->assertException($exception, function () use ($key, $index) {
            $this->key->build($key, $index);
        });

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::verify($this->key)->getOrdinal($nth);
    }
}

<?php namespace Unit\Chekote\NounStore\Key;

use InvalidArgumentException;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Key::build()
 */
class BuildTest extends KeyTest
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->build(Phake::anyParameters())->thenCallParent();
    }

    public function testNullIndexReturnsUnmodifiedKey(): void
    {
        $key = 'Thing';

        $this->assertEquals($key, $this->key->build($key, null));
    }

    public function testNonNullIndexReturnsModifiedKey(): void
    {
        $key = 'Thing';
        $index = 18;
        $nth = $index + 1;

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->getOrdinal($nth)->thenReturn('th');

        $this->assertEquals('19th Thing', $this->key->build($key, $index));
    }

    public function testInvalidArgumentExceptionBubblesUpFromGetOrdinal(): void
    {
        $key = 'Thing';
        $index = -2;
        $nth = $index + 1;

        $exception = new InvalidArgumentException('$nth must be a positive number');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->getOrdinal($nth)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->key->build($key, $index);
    }
}

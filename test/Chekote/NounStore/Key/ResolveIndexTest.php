<?php namespace Chekote\NounStore\Key;

use Chekote\Phake\Phake;
use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Key::resolveIndex()
 */
class ResolveIndexTest extends KeyTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->resolveIndex(Phake::anyParameters())->thenCallParent();
    }

    public function testIndexIsReturnedIfNthIsNull() {
        $index = 10;
        $nth = null;

        /* @noinspection PhpUndefinedMethodInspection */
        $this->assertEquals($index, Phake::makeVisible($this->key)->resolveIndex($index, $nth));
    }

    /**
     * @return array
     */
    public function mismatchedIndexAndNthDataProvider()
    {
        return [
        //   index, nth
            [    1,   0],
            [    1,   1],
            [    1,   5],
            [    4,   0],
            [    4,   4],
            [    4,  10],
        ];
    }

    /**
     * @dataProvider mismatchedIndexAndNthDataProvider
     * @param int $index   the mismatched index to pass along with the nth
     * @param int $nth the mismatched nth to pass along with the index
     */
    public function testThrowsExceptionIfKeyAndIndexMismatch($index, $nth)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("index $index was provided with nth $nth, but they are not equivalent");

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::makeVisible($this->key)->resolveIndex($index, $nth);
    }

    /**
     * @return array
     */
    public function invalidNthDataProvider()
    {
        return [
            [  0],
            [ -1],
            [ -4],
            [-10],
        ];
    }

    /**
     * @dataProvider invalidNthDataProvider
     * @param int $nth the invalid nth to pass along with the index
     */
    public function testThrowsExceptionIfNthIsLessThanOne($nth) {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('nth must be equal to or larger than 1');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::makeVisible($this->key)->resolveIndex(null, $nth);
    }

    /**
     * @return array
     */
    public function validIndexAndNthDataProvider()
    {
        return [
        //   index, nth
            [    0,   1],
            [   10,  11],
            [  100, 101],
            [ null,  50],
        ];
    }

    /**
     * Tests that calling with valid index and key param returns nth decremented by 1.
     *
     * @dataProvider validIndexAndNthDataProvider
     * @param int $index the valid index to pass along with the nth
     * @param int $nth   the valid nth to pass along with the index
     */
    public function testValidNthIsReturnedDecrementedByOne($index, $nth) {
        $expected = $nth - 1;

        /* @noinspection PhpUndefinedMethodInspection */
        $this->assertEquals($expected, Phake::makeVisible($this->key)->resolveIndex($index, $nth));
    }
}

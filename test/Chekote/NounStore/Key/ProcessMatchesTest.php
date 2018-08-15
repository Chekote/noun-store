<?php namespace Chekote\NounStore\Key;

use Chekote\Phake\Phake;
use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Key::processMatches()
 */
class ProcessMatchesTest extends KeyTest
{
    const MATCHES_NO_NTH              = ["thing", '', '', 'thing'];
    const MATCHES_NO_NTH_RELATIONSHIP = ["thing's address", '', '', 'thing', "'s address", 'address'];
    const MATCHES_NTH                 = ["15th thing", '15th ', '15', 'thing'];
    const MATCHES_NTH_AND_RELATION    = ["15th thing's address", '15th ', '15', 'thing', "'s address", 'address'];

    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->processMatches(Phake::anyParameters())->thenCallParent();
    }

    public function successDataProvider() {
        return [
        //  index, matches,                          resolvedIndex, expectedNth, expectedKey, expectedRelation
            [null, self::MATCHES_NO_NTH,                      null,        null, 'thing',     null     ],
            [   1, self::MATCHES_NO_NTH,                         1,        null, 'thing',     null     ],
            [null, self::MATCHES_NO_NTH_RELATIONSHIP,         null,        null, 'thing',     'address'],
            [   1, self::MATCHES_NO_NTH_RELATIONSHIP,            1,        null, 'thing',     'address'],
            [null, self::MATCHES_NTH,                           14,          15, 'thing',     null     ],
            [  14, self::MATCHES_NTH,                           14,          15, 'thing',     null     ],
            [null, self::MATCHES_NTH_AND_RELATION,              14,          15, 'thing',     'address'],
            [  14, self::MATCHES_NTH_AND_RELATION,              14,          15, 'thing',     'address'],
        ];
    }

    /**
     * @dataProvider successDataProvider()
     * @param int|null    $index            the index to pass to the method.
     * @param array       $matches          the regex matches to pass to the method.
     * @param int|null    $resolvedIndex    the index that mocked resolveIndex() should return.
     * @param string      $expectedKey      the expected key to be returned from the method.
     * @param int|null    $expectedNth      the nth expected to be pulled from the $matches array.
     * @param string|null $expectedRelation the expected relationship to be returned from the method.
     */
    public function testSuccess($index, array $matches, $resolvedIndex, $expectedNth, $expectedKey, $expectedRelation) {
        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->resolveIndex($index, $expectedNth)->thenReturn($resolvedIndex);

        /* @noinspection PhpUndefinedMethodInspection */
        $this->assertEquals(
            [$expectedKey, $resolvedIndex, $expectedRelation],
            Phake::makeVisible($this->key)->processMatches($index, $matches)
        );
    }

    public function testExceptionBubblesUpFromResolveIndex() {
        $exception = new InvalidArgumentException('nth must be equal to or larger than 1');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->resolveIndex(Phake::anyParameters())->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::makeVisible($this->key)->processMatches(null, ["0th thing", '0th ', '0', 'thing']);
    }
}

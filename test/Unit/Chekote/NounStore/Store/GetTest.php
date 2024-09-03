<?php namespace Unit\Chekote\NounStore\Store;

use InvalidArgumentException;
use Unit\Chekote\NounStore\Key\KeyTestCase;
use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Store::get()
 */
class GetTest extends StoreTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->get(Phake::anyParameters())->thenCallParent();
    }

    public function testKeyIsParsedAndParsedValuesAreUsed(): void
    {
        $key = '2nd ' . StoreTestCase::KEY;
        $parsedKey = StoreTestCase::KEY;
        $parsedIndex = 1;

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse($key)->thenReturn([[$parsedKey, $parsedIndex]]);

        $this->assertEquals(StoreTestCase::$SECOND_VALUE, $this->store->get($key));
    }

    public function testInvalidArgumentExceptionBubblesUpFromParse()
    {
        $exception = new InvalidArgumentException('Key syntax is invalid');

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse(KeyTestCase::INVALID_KEY)->thenThrow($exception);

        $this->expectException(get_class($exception));
        $this->expectExceptionMessage($exception->getMessage());

        $this->store->get(KeyTestCase::INVALID_KEY);
    }

    /**
     * @dataProvider happyPathProvider
     * @param string  $key       the key to fetch.
     * @param array[] $parsedKey the parsed key.
     * @param mixed   $expected  the expected value.
     */
    public function testHappyPath(string $key, array $parsedKey, mixed $expected): void
    {
        /* @noinspection PhpUndefinedMethodInspection */
        Phake::expect($this->key, 1)->parse($key)->thenReturn($parsedKey);

        $this->assertEquals($expected, $this->store->get($key));
    }

    public static function happyPathProvider(): array
    {
        return [
            //                                                     key                                             parsed key                                     expected
            'Noun without index returns most recent noun'      => [StoreTestCase::KEY,                            [[StoreTestCase::KEY, null]],                   StoreTestCase::$MOST_RECENT_VALUE],
            'Noun with index returns specific noun'            => ['1st ' . StoreTestCase::KEY,                   [[StoreTestCase::KEY,    0]],                   StoreTestCase::$FIRST_VALUE],
            'Non-existent noun returns null'                   => ['3rd ' . StoreTestCase::KEY,                   [[StoreTestCase::KEY,    2]],                   null],
            'Possessive noun w/o index string property'        => [StoreTestCase::KEY . "'s color",               [[StoreTestCase::KEY, null], ['color', null]],  'Blue'],
            'Possessive noun with index string property'       => ['1st ' . StoreTestCase::KEY . "'s color",      [[StoreTestCase::KEY, 0], ['color', null]],     'Red'],
            'Possessive noun w/o index collection w/o index'   => [StoreTestCase::KEY . "'s option",              [[StoreTestCase::KEY, null], ['option', null]], ['Cruise Control', 'Air Conditioning']],
            'Possessive noun with index collection w/o index'  => ['1st ' . StoreTestCase::KEY . "'s option",     [[StoreTestCase::KEY, 0], ['option', null]],    ['GPS', 'Heated Seats']],
            'Possessive noun w/o index collection with index'  => [StoreTestCase::KEY . "'s 1st option",          [[StoreTestCase::KEY, null], ['option', 0]],    'Cruise Control'],
            'Possessive noun with index collection with index' => ['1st ' . StoreTestCase::KEY . "'s 1st option", [[StoreTestCase::KEY, 0], ['option', 0]],       'GPS'],
        ];
    }
}

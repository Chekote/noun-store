<?php namespace Chekote\NounStore\Store;

use Chekote\NounStore\Key;
use Chekote\NounStore\Key\KeyPhake;
use Chekote\NounStore\Store;
use Chekote\NounStore\TestCase;
use Chekote\Phake\Phake;

abstract class StoreTest extends TestCase
{
    /** @var KeyPhake */
    protected $key;

    /** @var StorePhake */
    protected $store;

    const KEY = 'Color';
    const FIRST_VALUE = 'Red';
    const SECOND_VALUE = 'Blue';

    const MOST_RECENT_VALUE = self::SECOND_VALUE;

    /**
     * Sets up the environment before each test.
     */
    public function setUp()
    {
        $this->key = Phake::strictMock(Key::class);
        $this->store = Phake::strictMockWithConstructor(Store::class, $this->key);

        /* @noinspection PhpUndefinedFieldInspection */
        Phake::makeVisible($this->store)->nouns = [self::KEY => [self::FIRST_VALUE, self::SECOND_VALUE]];
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown()
    {
        $this->key = null;
        $this->store = null;

        Phake::verifyExpectations();
    }
}

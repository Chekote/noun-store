<?php namespace Chekote\NounStore\Store;

use Chekote\NounStore\Key;
use Chekote\NounStore\Store;
use Chekote\Phake\Phake;
use Chekote\PHPUnit\Framework\TestCase;

abstract class StoreTest extends TestCase
{
    /** @var Key|\Phake_IMock */
    protected $key;

    /** @var Store|\Phake_IMock */
    protected $store;

    const KEY = 'Some Key';
    const FIRST_VALUE = 'The First Value';
    const SECOND_VALUE = 'The Second Value';

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
    }
}

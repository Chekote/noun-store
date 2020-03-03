<?php namespace Unit\Chekote\NounStore\Store;

use Chekote\NounStore\Key;
use Chekote\NounStore\Store;
use stdClass;
use Unit\Chekote\NounStore\TestCase;
use Unit\Chekote\Phake\Phake;

abstract class StoreTest extends TestCase
{
    /** @var Key|\Phake_IMock */
    protected $key;

    /** @var Store|\Phake_IMock */
    protected $store;

    /** @var stdClass the first value stored under self::KEY */
    protected static $FIRST_VALUE;

    /** @var stdClass the second value stored under self::KEY */
    protected static $SECOND_VALUE;

    /** @var stdClass the most recent value stored under self::KEY */
    protected static $MOST_RECENT_VALUE;

    const KEY = 'Car';

    /**
     * Sets up the classes initial static state.
     */
    public static function initialize()
    {
        $car1 = new stdClass();
        $car1->color = 'Red';
        $car1->option = ['GPS', 'Heated Seats'];

        $car2 = new stdClass();
        $car2->color = 'Blue';
        $car2->option = ['Cruise Control', 'Air Conditioning'];

        self::$FIRST_VALUE = $car1;
        self::$SECOND_VALUE = $car2;
        self::$MOST_RECENT_VALUE = $car2;
    }

    /**
     * Sets up the environment before each test.
     */
    public function setUp()
    {
        $this->key = Phake::strictMock(Key::class);
        $this->store = Phake::strictMockWithConstructor(Store::class, $this->key);

        /* @noinspection PhpUndefinedFieldInspection */
        Phake::makeVisible($this->store)->nouns = [self::KEY => [self::$FIRST_VALUE, self::$SECOND_VALUE]];
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

StoreTest::initialize();

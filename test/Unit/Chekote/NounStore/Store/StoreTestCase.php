<?php namespace Unit\Chekote\NounStore\Store;

use Chekote\NounStore\Key;
use Chekote\NounStore\Store;
use Phake\IMock;
use stdClass;
use Unit\Chekote\NounStore\Assert\KeyPhake;
use Unit\Chekote\NounStore\TestCase;
use Unit\Chekote\Phake\Phake;

abstract class StoreTestCase extends TestCase
{
    protected IMock|KeyPhake $key;

    protected IMock|StorePhake $store;

    /** the first value stored under self::KEY */
    protected static stdClass $firstValue;

    /** the second value stored under self::KEY */
    protected static stdClass $secondValue;

    /** the most recent value stored under self::KEY */
    public static stdClass $mostRecentValue;

    const KEY = 'Car';

    /**
     * Sets up the classes initial static state.
     */
    public static function initialize(): void
    {
        $car1 = new stdClass();
        $car1->color = 'Red';
        $car1->option = ['GPS', 'Heated Seats'];

        $car2 = new stdClass();
        $car2->color = 'Blue';
        $car2->option = ['Cruise Control', 'Air Conditioning'];

        self::$firstValue = $car1;
        self::$secondValue = $car2;
        self::$mostRecentValue = $car2;
    }

    /**
     * Sets up the environment before each test.
     */
    public function setUp(): void
    {
        $this->key = Phake::strictMock(Key::class);
        $this->store = Phake::strictMockWithConstructor(Store::class, $this->key);

        /* @noinspection PhpUndefinedFieldInspection */
        Phake::makeVisible($this->store)->nouns = [self::KEY => [self::$firstValue, self::$secondValue]];
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown(): void
    {
        unset($this->key);
        unset($this->store);

        Phake::verifyExpectations();
    }
}

StoreTestCase::initialize();

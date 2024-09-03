<?php namespace Integration\Chekote\NounStore\Store;

use Chekote\NounStore\Store;
use Integration\Chekote\NounStore\TestCase;
use InvalidArgumentException;

class GetTest extends TestCase
{
    /** @var Store */
    protected static $store;

    /** @var string */
    const NOUN = 'Person';

    /** @var Car */
    protected static $chevy;

    /** @var Car */
    protected static $ford;

    /** @var Car */
    protected static $kia;

    /** @var Car */
    protected static $toyota;

    /** @var Person */
    protected static $alice;

    /** @var Person */
    protected static $bob;

    /**
     * Sets up the classes initial static state.
     */
    public static function initialize(): void
    {
        self::$store = new Store();

        self::$kia = new Car();
        self::$kia->make = 'Kia';

        self::$toyota = new Car();
        self::$toyota->make = 'Toyota';

        self::$bob = new Person();
        self::$bob->name = 'Bob';
        self::$bob->car = [self::$kia, self::$toyota];

        self::$ford = new Car();
        self::$ford->make = 'Ford';

        self::$chevy = new Car();
        self::$chevy->make = 'Chevrolet';

        self::$alice = new Person();
        self::$alice->name = 'Alice';
        self::$alice->car = [self::$ford, self::$chevy];

        self::$store->set(self::NOUN, self::$bob);
        self::$store->set(self::NOUN, self::$alice);
    }

    /**
     * @dataProvider happyPathDataProvider
     * @param string $key           the key to fetch the value for.
     * @param mixed  $expectedValue the expected value for the key.
     * @param string $message       the message to pass to the assertion, for reporting if the test fails.
     */
    public function testHappyPath(string $key, mixed $expectedValue, string $message): void
    {
        $this->assertSame($expectedValue, self::$store->get($key), $message);
    }

    public static function happyPathDataProvider(): array
    {
        return [
            'Noun'                       => ['Person',               self::$alice,                'Alice is the most recent Person in the store'],
            'Nth Noun'                   => ['1st Person',           self::$bob,                  'Bob is the 1st Person in the store'],
            'Related Noun'               => ["Person's car",         [self::$ford, self::$chevy], 'Alice is the most recent Person in the store, and cars are Ford and Chevrolet'],
            'Related Nth Noun'           => ["Person's 1st car",     self::$ford,                 'Alice is the most recent Person in the store, and her 1st car is the Ford'],
            'Nth Nouns Related Noun'     => ["1st Person's car",     [self::$kia, self::$toyota], 'Bob is the 1st Person in the store, and his cars are Kia and Toyota'],
            'Nth Nouns Related Nth Noun' => ["1st Person's 1st car", self::$kia,                  'Bob is the 1st Person in the store, and his 1st car is the Kia'],
            'Missing Noun'               => ['Dog',                  null,                        'Dog does not exist in the store'],
            'Missing Nth Noun'           => ['3rd Person',           null,                        '3rd Person does not exist in the store'],
            'Missing Related Noun'       => ["Person's dog",         null,                        'Alice is the most recent Person in the store, but has no dog'],
        ];
    }

    public function testInvalidKeyThrowsInvalidArgumentException(): void
    {
        $invalidKey = "Customer's's Car";

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Key syntax is invalid');

        self::$store->get($invalidKey);
    }
}

class Car
{
    /** @var string */
    public $make;
}

class Person
{
    /** @var Car[] */
    public $car;

    /** @var string */
    public $name;
}

GetTest::initialize();

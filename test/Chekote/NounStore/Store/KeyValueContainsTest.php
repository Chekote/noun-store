<?php namespace Chekote\NounStore\Store;

use Chekote\Phake\Phake;
use InvalidArgumentException;

/**
 * @covers \Chekote\NounStore\Store::keyValueContains()
 */
class KeyValueContainsTest extends StoreTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->keyValueContains(Phake::anyParameters())->thenCallParent();
    }

    public function successScenariosDataProvider()
    {
        return [
        //  key                  index, parsedKey, parsedIndex, storedValue,             checkedValue
            ['1st ' . self::KEY,  null, self::KEY,           0, self::FIRST_VALUE,       substr(self::FIRST_VALUE, 0, 2)       ], // key with nth
            ['2nd ' . self::KEY,  null, self::KEY,           1, self::SECOND_VALUE,      substr(self::SECOND_VALUE, 0, 2)      ], // key with nth
            [self::KEY,           null, self::KEY,        null, self::MOST_RECENT_VALUE, substr(self::MOST_RECENT_VALUE, 0, 2) ], // key without nth
            [self::KEY,              0, self::KEY,           0, self::FIRST_VALUE,       substr(self::FIRST_VALUE, 0, 2)       ], // with index
            [self::KEY,              1, self::KEY,           1, self::SECOND_VALUE,      substr(self::SECOND_VALUE, 0, 2)      ], // with index
        ];
    }

    public function failureScenariosDataProvider()
    {
        return [
        //  key,                 index, parsedKey,     parsedIndex, storedValue,        checkedValue
            ['1st ' . self::KEY,  null, self::KEY,               0, self::FIRST_VALUE,  self::SECOND_VALUE ], // value mismatch
            ['2nd ' . self::KEY,  null, self::KEY,               1, self::SECOND_VALUE, self::FIRST_VALUE  ], // value mismatch
            ['3rd ' . self::KEY,  null, self::KEY,               2, null,               null               ], // no such nth
            ['No Such Key',       null, 'No Such Key',        null, null,               null               ], // no such key
        ];
    }

    public function parseExceptionScenariosDataProvider()
    {
        return [
        //  key                  value index exception class                  exception message
            ['1st ' . self::KEY, null,    1, InvalidArgumentException::class, "1 was provided for index param when key '1st " . self::KEY . "' contains an nth value, but they do not match"],
        ];
    }

    /**
     * Executes a success scenario against the method.
     *
     * @dataProvider successScenariosDataProvider
     * @param string $key          the key to pass to the keyValueContains() method.
     * @param int    $index        the index to pass to the keyValueContains() method.
     * @param string $parsedKey    the key that the mocked parse() method should return.
     * @param string $parsedIndex  the index that the mocked parse() method should return.
     * @param mixed  $storedValue  the value that the mocked get() method should return.
     * @param mixed  $checkedValue the value to pass to the keyValueContains() method.
     */
    public function testSuccessScenario($key, $index, $parsedKey, $parsedIndex, $storedValue, $checkedValue)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->get($parsedKey, $parsedIndex)->thenReturn($storedValue);
        }

        $this->assertTrue($this->store->keyValueContains($key, $checkedValue, $index));
    }

    /**
     * Executes a failure scenario against the method.
     *
     * @dataProvider failureScenariosDataProvider
     * @param string $key          the key to pass to the keyValueContains() method.
     * @param int    $index        the index to pass to the keyValueContains() method.
     * @param string $parsedKey    the key that the mocked parse() method should return.
     * @param string $parsedIndex  the index that the mocked parse() method should return.
     * @param mixed  $storedValue  the value that the mocked get() method should return.
     * @param mixed  $checkedValue the value to pass to the keyValueContains() method.
     */
    public function testFailureScenario($key, $index, $parsedKey, $parsedIndex, $storedValue, $checkedValue)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->store)->get($parsedKey, $parsedIndex)->thenReturn($storedValue);
        }

        $this->assertFalse($this->store->keyValueContains($key, $checkedValue, $index));
    }

    /**
     * Executes an exception scenario against the method.
     *
     * @dataProvider parseExceptionScenariosDataProvider
     * @param string $key              the key to pass to the method.
     * @param mixed  $value            the value to pass to the method.
     * @param int    $index            the index to pass to the method.
     * @param string $exceptionClass   the expected class of the exception.
     * @param string $exceptionMessage the expected message of the exception.
     */
    public function testParseExceptionScenario($key, $value, $index, $exceptionClass, $exceptionMessage)
    {
        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->key)->parse($key, $index)->thenThrow(new $exceptionClass($exceptionMessage));

        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->store->keyValueContains($key, $value, $index);
    }
}

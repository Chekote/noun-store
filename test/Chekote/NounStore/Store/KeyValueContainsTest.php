<?php namespace Chekote\NounStore\Store;

use InvalidArgumentException;

class KeyValueContainsTest extends StoreTest
{
    public function successScenariosDataProvider()
    {
        return [
        //  key                  value                                index
            ['1st ' . self::KEY, substr(self::FIRST_VALUE, 0, 2)           ], // key with nth
            ['2nd ' . self::KEY, substr(self::SECOND_VALUE, 0, 2)          ], // key with nth
            [self::KEY,          substr(self::MOST_RECENT_VALUE, 0, 2)     ], // key without nth
            [self::KEY,          substr(self::FIRST_VALUE, 0, 2),         0], // with index
            [self::KEY,          substr(self::SECOND_VALUE, 0, 2),        1], // with index
        ];
    }

    public function failureScenariosDataProvider()
    {
        return [
        //  key                  value               index
            ['1st ' . self::KEY, self::SECOND_VALUE, null], // value mismatch
            ['2nd ' . self::KEY, self::FIRST_VALUE,  null], // value mismatch
            ['3rd ' . self::KEY, null,               null], // no such nth
            ['No Such Key',      null,               null], // no such key
        ];
    }

    public function exceptionScenariosDataProvider()
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
     * @param string $key   the key to pass to the method.
     * @param mixed  $value the value to pass to the method.
     * @param int    $index the index to pass to the method.
     */
    public function testSuccessScenario($key, $value, $index = null)
    {
        $this->assertTrue($this->store->keyValueContains($key, $value, $index));
    }

    /**
     * Executes a failure scenario against the method.
     *
     * @dataProvider failureScenariosDataProvider
     * @param string $key   the key to pass to the method.
     * @param mixed  $value the value to pass to the method.
     * @param int    $index the index to pass to the method.
     */
    public function testFailureScenario($key, $value, $index)
    {
        $this->assertFalse($this->store->keyValueContains($key, $value, $index));
    }

    /**
     * Executes an exception scenario against the method.
     *
     * @dataProvider exceptionScenariosDataProvider
     * @param string $key              the key to pass to the method.
     * @param mixed  $value            the value to pass to the method.
     * @param int    $index            the index to pass to the method.
     * @param string $exceptionClass   the expected class of the exception.
     * @param string $exceptionMessage the expected message of the exception.
     */
    public function testExceptionScenario($key, $value, $index, $exceptionClass, $exceptionMessage)
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->store->keyValueContains($key, $value, $index);
    }
}

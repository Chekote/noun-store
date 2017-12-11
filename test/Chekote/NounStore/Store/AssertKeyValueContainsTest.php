<?php namespace Chekote\NounStore\Store;

use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 * @covers Store::assertKeyValueContains()
 */
class AssertKeyValueContainsTest extends StoreTest
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
        //  key                  value               index exception class                  exception message
            ['1st ' . self::KEY, null,                  1, InvalidArgumentException::class, "1 was provided for index param when key '1st " . self::KEY . "' contains an nth value, but they do not match"],
            ['1st ' . self::KEY, self::SECOND_VALUE, null, RuntimeException::class,         "Entry '1st " . self::KEY . "' does not contain '" . self::SECOND_VALUE . "'"                                 ],
            ['2nd ' . self::KEY, self::FIRST_VALUE,  null, RuntimeException::class,         "Entry '2nd " . self::KEY . "' does not contain '" . self::FIRST_VALUE . "'"                                  ],
            ['3rd ' . self::KEY, null,               null, OutOfBoundsException::class,     "Entry '3rd " . self::KEY . "' was not found in the store."                                                   ],
            ['No Such Key',      null,               null, OutOfBoundsException::class,     "Entry 'No Such Key' was not found in the store."                                                             ],
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
        $this->store->assertKeyValueContains($key, $value, $index);
    }

    /**
     * Executes a failure scenario against the method.
     *
     * @dataProvider failureScenariosDataProvider
     * @param string $key              the key to pass to the method.
     * @param mixed  $value            the value to pass to the method.
     * @param int    $index            the index to pass to the method.
     * @param string $exceptionClass   the expected class of the exception.
     * @param string $exceptionMessage the expected message of the exception.
     */
    public function testFailureScenario($key, $value, $index, $exceptionClass, $exceptionMessage)
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->store->assertKeyValueContains($key, $value, $index);
    }
}

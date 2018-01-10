<?php namespace Chekote\NounStore\Assert;

use Chekote\NounStore\Store\StoreTest;
use Chekote\Phake\Phake;
use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 * @covers \Chekote\NounStore\Assert::keyValueContains()
 */
class KeyValueContainsTest extends AssertTest
{
    public function setUp() {
        parent::setUp();

        /** @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->assert)->keyValueContains(Phake::anyParameters())->thenCallParent();
    }

    public function successScenariosDataProvider()
    {
        return [
        //  key                       index, parsed key,     parsed index, value,
            ['1st ' . StoreTest::KEY,  null, StoreTest::KEY,            0, substr(StoreTest::FIRST_VALUE, 0, 2)      ], // key with nth
            ['2nd ' . StoreTest::KEY,  null, StoreTest::KEY,            1, substr(StoreTest::SECOND_VALUE, 0, 2)     ], // key with nth
            [StoreTest::KEY,              2, StoreTest::KEY,            2, substr(StoreTest::MOST_RECENT_VALUE, 0, 2)], // key without nth
            [StoreTest::KEY,              0, StoreTest::KEY,            0, substr(StoreTest::FIRST_VALUE, 0, 2),     ], // with index
            [StoreTest::KEY,              1, StoreTest::KEY,            1, substr(StoreTest::SECOND_VALUE, 0, 2),    ], // with index
        ];
    }

    public function parseExceptionDataProvider() {
        return [
        //   key,                     index, value, exception class,                 exception message
            ['1st ' . StoreTest::KEY,     1, null,  InvalidArgumentException::class, "1 was provided for index param when key '1st " . StoreTest::KEY . "' contains an nth value, but they do not match"],
        ];
    }

    public function keyExistsExceptionDataProvider() {
        return [
        //   key,                     index, parsed key,     parsed index, value, exception class,             exception message
            ['3rd ' . StoreTest::KEY,  null, StoreTest::KEY,            2, null,  OutOfBoundsException::class, "Entry '3rd " . StoreTest::KEY . "' was not found in the store." ],
            ['No Such Key',            null, StoreTest::KEY,            0, null,  OutOfBoundsException::class, "Entry 'No Such Key' was not found in the store."                ],
        ];
    }

    public function keyValueContainsExceptionDataProvider() {
        return [
        //   key,                     index, parsed key,     parsed index, built key,               value,                   exception class,         exception message
            ['1st ' . StoreTest::KEY,  null, StoreTest::KEY,            0, '1st ' . StoreTest::KEY, StoreTest::SECOND_VALUE, RuntimeException::class, "Entry '1st " . StoreTest::KEY . "' does not contain '" . StoreTest::SECOND_VALUE . "'" ],
            ['2nd ' . StoreTest::KEY,  null, StoreTest::KEY,            1, '2nd ' . StoreTest::KEY, StoreTest::FIRST_VALUE,  RuntimeException::class, "Entry '2nd " . StoreTest::KEY . "' does not contain '" . StoreTest::FIRST_VALUE . "'"  ],
        ];
    }

    /**
     * Executes a success scenario against the method.
     *
     * @dataProvider successScenariosDataProvider
     * @param string $key         the key to pass to the method.
     * @param int    $index       the index to pass to the method.
     * @param string $parsedKey   the key that the mocked parsed method should return.
     * @param int    $parsedIndex the index that the mocked parsed method should return.
     * @param mixed  $value       the value to pass to the method.
     */
    public function testSuccessScenario($key, $index, $parsedKey, $parsedIndex, $value)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->assert)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
            Phake::when($this->store)->keyValueContains($parsedKey, $value, $parsedIndex)->thenReturn(true);
        }

        $this->assert->keyValueContains($key, $value, $index);
    }

    /**
     * Executes a failure scenario caused by the parse method.
     *
     * @dataProvider parseExceptionDataProvider
     * @param string $key              the key to pass to the method.
     * @param int    $index            the index to pass to the method.
     * @param mixed  $value            the value to pass to the method.
     * @param string $exceptionClass   the expected class of the exception.
     * @param string $exceptionMessage the expected message of the exception.
     */
    public function testParseExceptionScenario($key, $index, $value, $exceptionClass, $exceptionMessage)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenThrow(new $exceptionClass($exceptionMessage));
        }

        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assert->keyValueContains($key, $value, $index);
    }

    /**
     * Executes a failure scenario caused by the keyExists method on the store.
     *
     * @dataProvider keyExistsExceptionDataProvider
     * @param string $key              the key to pass to the method.
     * @param int    $index            the index to pass to the method.
     * @param string $parsedKey        the key that the mocked parsed method should return.
     * @param int    $parsedIndex      the index that the mocked parsed method should return.
     * @param mixed  $value            the value to pass to the method.
     * @param string $exceptionClass   the expected class of the exception.
     * @param string $exceptionMessage the expected message of the exception.
     */
    public function testKeyExistsExceptionScenario($key, $index, $parsedKey, $parsedIndex, $value, $exceptionClass, $exceptionMessage)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->assert)->keyExists($parsedKey, $parsedIndex)->thenThrow(new $exceptionClass($exceptionMessage));
        }

        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assert->keyValueContains($key, $value, $index);
    }

    /**
     * Executes a failure scenario caused by the keyExists method on the store.
     *
     * @dataProvider keyValueContainsExceptionDataProvider
     * @param string $key              the key to pass to the method.
     * @param int    $index            the index to pass to the method.
     * @param string $parsedKey        the key that the mocked parsed method should return.
     * @param int    $parsedIndex      the index that the mocked parsed method should return.
     * @param mixed  $value            the value to pass to the method.
     * @param string $exceptionClass   the expected class of the exception.
     * @param string $exceptionMessage the expected message of the exception.
     */
    public function testKeyValueContainsExceptionScenario($key, $index, $parsedKey, $parsedIndex, $builtKey, $value, $exceptionClass, $exceptionMessage)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        {
            Phake::when($this->key)->parse($key, $index)->thenReturn([$parsedKey, $parsedIndex]);
            Phake::when($this->assert)->keyExists($parsedKey, $parsedIndex)->thenReturn(true);
            Phake::when($this->store)->keyValueContains($parsedKey, $value, $parsedIndex)->thenReturn(false);
            Phake::when($this->key)->build($parsedKey, $parsedIndex)->thenReturn($builtKey);
        }

        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        /* @noinspection PhpUnhandledExceptionInspection */
        $this->assert->keyValueContains($key, $value, $index);
    }
}

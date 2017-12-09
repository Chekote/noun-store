<?php namespace Chekote\NounStore\Store;

use Chekote\NounStore\Store;
use InvalidArgumentException;
use ReflectionClass;
use stdClass;

/**
 * @covers Store::buildKey()
 */
class BuildKeyTest extends StoreTest
{
    /**
     * Provides examples of all single argument data types.
     *
     * @param  string[] $exclude a list of data types to exclude
     * @return array
     */
    public function dataTypesDataProvider($exclude = [])
    {
        $types = [
            ['Hello World!'],
            [12345],
            [12.45],
            [true],
            [[]],
            [new stdClass()],
            [null],
            [stream_context_create()],
        ];

        return array_filter($types, function ($value) use ($exclude) {
            return !in_array(gettype($value[0]), $exclude);
        });
    }

    /**
     * Provides examples of all single argument data types excluding null or integer.
     *
     * @return array
     */
    public function nonNullOrIntDataTypesProvider()
    {
        return $this->dataTypesDataProvider(['NULL', 'integer']);
    }

    /**
     * Provides examples of all single argument data types excluding string.
     *
     * @return array
     */
    public function nonStringDataTypesProvider()
    {
        return $this->dataTypesDataProvider(['string']);
    }

    /**
     * Tests that calling Store::buildKey with valid key and index combinations works correctly.
     *
     * @dataProvider validKeyAndIndexCombinationsDataProvider
     * @param string $key      the key to use for the build
     * @param int    $index    the index to use for the build
     * @param string $expected the expected resulting key
     */
    public function testBuildKeyBuildsValidKeyAndIndexCombinations($key, $index, $expected)
    {
        $buildKey = (new ReflectionClass(Store::class))->getMethod('buildKey');
        $buildKey->setAccessible(true);

        $actual = $buildKey->invoke($this->store, $key, $index);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that Store::buildKey enforces that $key must be a string.
     *
     * @dataProvider nonStringDataTypesProvider
     * @param mixed $key the key value to send to buildKey()
     */
    public function testBuildKeyThrowsExceptionWhenKeyIsNotAString($key)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$key must be a string');

        $buildKey = (new ReflectionClass(Store::class))->getMethod('buildKey');
        $buildKey->setAccessible(true);

        $buildKey->invoke($this->store, $key, null);
    }

    /**
     * Tests that Store::buildKey enforces that $index must be null or int.
     *
     * @dataProvider nonNullOrIntDataTypesProvider
     * @param mixed $index the index value to send to buildKey()
     */
    public function testBuildKeyThrowsExceptionWhenIndexIsNotNullOrInt($index)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$index must be null or an int');

        $buildKey = (new ReflectionClass(Store::class))->getMethod('buildKey');
        $buildKey->setAccessible(true);

        $buildKey->invoke($this->store, 'Valid Key', $index);
    }

    /**
     * Provides examples of valid key and index pairs with expected build results.
     *
     * @return array
     */
    public function validKeyAndIndexCombinationsDataProvider()
    {
        return [
            // key   index  expected result
            ['Thing', null, 'Thing'],
            ['Thing',    0, '1st Thing'],
            ['Thing',    1, '2nd Thing'],
            ['Thing',    2, '3rd Thing'],
            ['Thing',    3, '4th Thing'],
            ['Thing',    4, '5th Thing'],
            ['Thing',  477, '478th Thing'],
        ];
    }
}

<?php namespace Chekote\NounStore;

use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

class Store
{
    /** @var array */
    protected $nouns;

    const ORDINAL_ST = 'st';
    const ORDINAL_ND = 'nd';
    const ORDINAL_RD = 'rd';
    const ORDINAL_TH = 'th';

    /**
     * Asserts that a value has been stored for the specified key.
     *
     * @param  string                   $key   The key to check. @see self::get() for formatting options.
     * @param  int                      $index [optional] The index of the key entry to check. If not specified, the
     *                                         method will ensure that at least one item is stored for the key.
     * @throws OutOfBoundsException     if a value has not been stored for the specified key.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                        that does not match the index.
     * @return mixed                    The value.
     */
    public function assertKeyExists($key, $index = null)
    {
        list($key, $index) = $this->parseKey($key, $index);

        if (!$this->keyExists($key, $index)) {
            throw new OutOfBoundsException("Entry '{$this->buildKey($key, $index)}' was not found in the store.");
        }

        return $this->get($key, $index);
    }

    /**
     * Asserts that the key's value matches the specified value.
     *
     * @param  string                   $key   The key to check. @see self::get() for formatting options.
     * @param  mixed                    $value The expected value.
     * @param  int                      $index [optional] The index of the key entry to retrieve. If not specified, the
     *                                         method will check the most recent value stored under the key.
     * @throws OutOfBoundsException     If a value has not been stored for the specified key.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                        that does not match the index.
     */
    public function assertKeyValueIs($key, $value, $index = null)
    {
        list($key, $index) = $this->parseKey($key, $index);

        $this->assertKeyExists($key, $index);

        if ($this->get($key, $index) != $value) {
            throw new RuntimeException(
                "Entry '{$this->buildKey($key, $index)}' does not match '" . print_r($value, true) . "'"
            );
        }
    }

    /**
     * Asserts that the key's value contains the specified string.
     *
     * @param  string                   $key   The key to check. @see self::get() for formatting options.
     * @param  string                   $value The value expected to be contained within the key's value.
     * @param  int                      $index [optional] The index of the key entry to retrieve. If not specified, the
     *                                         method will check the most recent value stored under the key.
     * @throws OutOfBoundsException     If a value has not been stored for the specified key.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                        that does not match the index.
     */
    public function assertKeyValueContains($key, $value, $index = null)
    {
        list($key, $index) = $this->parseKey($key, $index);

        $this->assertKeyExists($key, $index);

        if (!$this->keyValueContains($key, $value, $index)) {
            throw new RuntimeException(
                "Entry '{$this->buildKey($key, $index)}' does not contain '$value'"
            );
        }
    }

    /**
     * Removes all entries from the store.
     *
     * @return void
     */
    public function reset()
    {
        $this->nouns = [];
    }

    /**
     * Retrieves a value for the specified key.
     *
     * Each key is actually a collection. If you do not specify which item in the collection you want,
     * the method will return the most recent entry. You can specify the entry you want by either
     * using the plain english 1st, 2nd, 3rd etc in the $key param, or by specifying 0, 1, 2 etc in
     * the $index param. For example:
     *
     * Retrieve the most recent entry "Thing" collection:
     *   retrieve("Thing")
     *
     * Retrieve the 1st entry in the "Thing" collection:
     *   retrieve("1st Thing")
     *   retrieve("Thing", 0)
     *
     * Retrieve the 3rd entry in the "Thing" collection:
     *   retrieve("3rd Thing")
     *   retrieve("Thing", 2)
     *
     * Please note: The nth value in the string key is indexed from 1. In that "1st" is the first item stored.
     * The index parameter is indexed from 0. In that 0 is the first item stored.
     *
     * Please Note: If you specify both an $index param and an nth in the $key, they must both reference the same index.
     * If they do not, the method will throw an InvalidArgumentException.
     *
     * retrieve("1st Thing", 1);
     *
     * @param  string                   $key   The key to retrieve the value for. Can be prefixed with an nth descriptor.
     * @param  int                      $index [optional] The index of the key entry to retrieve. If not specified, the
     *                                         method will return the most recent value stored under the key.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                        that does not match the index.
     * @return mixed                    The value, or null if no value exists for the specified key/index combination.
     */
    public function get($key, $index = null)
    {
        list($key, $index) = $this->parseKey($key, $index);

        if (!$this->keyExists($key, $index)) {
            return;
        }

        return $index !== null ? $this->nouns[$key][$index] : end($this->nouns[$key]);
    }

    /**
     * Retrieves all values for the specified key.
     *
     * @param  string               $key The key to retrieve the values for.
     * @throws OutOfBoundsException if the specified $key does not exist in the store.
     * @return array                The values.
     */
    public function getAll($key)
    {
        if (!isset($this->nouns[$key])) {
            throw new OutOfBoundsException("'$key' does not exist in the store");
        }

        return $this->nouns[$key];
    }

    /**
     * Determines if a value has been stored for the specified key.
     *
     * @param  string                   $key   The key to check.
     * @param  int                      $index [optional] The index of the key entry to check. If not specified, the
     *                                         method will ensure that at least one item is stored for the key.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                        that does not match the index.
     * @return bool                     True if the a value has been stored, false if not.
     */
    public function keyExists($key, $index = null)
    {
        list($key, $index) = $this->parseKey($key, $index);

        return $index !== null ? isset($this->nouns[$key][$index]) : isset($this->nouns[$key]);
    }

    /**
     * Asserts that the key's value contains the specified string.
     *
     * @param  string                   $key   The key to check. @see self::get() for formatting options.
     * @param  string                   $value The value expected to be contained within the key's value.
     * @param  int                      $index [optional] The index of the key entry to retrieve. If not specified, the
     *                                         method will check the most recent value stored under the key.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                        that does not match the index.
     * @return bool                     True if the key's value contains the specified string, false if not.
     */
    public function keyValueContains($key, $value, $index = null)
    {
        list($key, $index) = $this->parseKey($key, $index);

        $actual = $this->get($key, $index);

        return is_string($actual) && strpos($actual, $value) !== false;
    }

    /**
     * Stores a value for the specified key.
     *
     * @param string $key   The key to store the value under.
     * @param mixed  $value The value to store.
     */
    public function set($key, $value)
    {
        $this->nouns[$key][] = $value;
    }

    /**
     * Parses a key into the separate key and index value.
     *
     * @example parseKey("Item"): ["Item", null]
     * @example parseKey("Item", 1): ["Item", 1]
     * @example parseKey("1st Item"): ["Item", 0]
     * @example parseKey("2nd Item"): ["Item", 1]
     * @example parseKey("3rd Item"): ["Item", 2]
     *
     * @param  string                   $key   the key to parse.
     * @param  int                      $index [optional] the index to return if the key does not contain one.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                        that does not match the index.
     * @return array                    a tuple, the 1st being the key with the nth removed, and the 2nd being the
     *                                        index.
     */
    protected function parseKey($key, $index = null)
    {
        if (preg_match('/^([1-9][0-9]*)(?:st|nd|rd|th) (.+)$/', $key, $matches)) {
            if ($index !== null && $index != $matches[1] - 1) {
                throw new InvalidArgumentException(
                    "$index was provided for index param when key '$key' contains an nth value, but they do not match"
                );
            }

            $index = $matches[1] - 1;
            $key = $matches[2];
        }

        return [$key, $index];
    }

    /**
     * Builds a key from it's separate key and index values.
     *
     * @example buildKey("Item", null): "Item"
     * @example buildKey("Item", 0): "1st Item"
     * @example buildKey("Item", 1): "2nd Item"
     * @example buildKey("Item", 2): "3rd Item"
     *
     * @param  string                   $key   The key to check.
     * @param  int                      $index The index (zero indexed) value for the key. If not specified, the method
     *                                         will not add an index notation to the key.
     * @throws InvalidArgumentException if $key is not a string.
     * @throws InvalidArgumentException if $index is not an int.
     * @return string                   the key with the index, or just the key if index is null.
     */
    protected function buildKey($key, $index)
    {
        if ($index === null) {
            return $key;
        }

        $nth = $index + 1;

        return $nth . $this->getOrdinal($nth) . ' ' . $key;
    }

    /**
     * Provides the ordinal notation for the specified nth number.
     *
     * @param  int    $nth the number to determine the ordinal for
     * @return string the ordinal
     */
    protected function getOrdinal($nth)
    {
        if ($nth < 0) {
            throw new InvalidArgumentException('$nth must be a positive number');
        }

        if ($nth > 9 && $nth < 20) {
            $ordinal = self::ORDINAL_TH;
        } else {
            switch (substr($nth, -1)) {
                case 1:
                    $ordinal = self::ORDINAL_ST;
                    break;
                case 2:
                    $ordinal = self::ORDINAL_ND;
                    break;
                case 3:
                    $ordinal = self::ORDINAL_RD;
                    break;
                default:
                    $ordinal = self::ORDINAL_TH;
                    break;
            }
        }

        return $ordinal;
    }
}

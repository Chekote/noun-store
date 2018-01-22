<?php namespace Chekote\NounStore;

use InvalidArgumentException;
use OutOfBoundsException;

class Store
{
    /** @var array */
    protected $nouns;

    const FIRST_ORDINAL = 'st';
    const SECOND_ORDINAL = 'nd';
    const THIRD_ORDINAL = 'rd';
    const FOURTH_THROUGH_NINTH_ORDINAL = 'th';

    /**
     * Asserts that a value has been stored for the specified key.
     *
     * @param  string                   $key The key to check. @see self::get() for formatting options.
     * @param  int                      $nth The nth (zero indexed) value for the key to check. If not specified, the
     *                                       method will ensure that at least one item is stored for the specified key.
     * @throws OutOfBoundsException     If a value has not been stored for the specified key.
     * @throws InvalidArgumentException $nth parameter is provided and $key contains an nth value, but they don't match.
     * @return mixed                    The value.
     */
    public function assertHas($key, $nth = null)
    {
        list($key, $nth) = $this->parseKey($key, $nth);

        if (!$this->has($key, $nth)) {
            throw new OutOfBoundsException("Entry '{$this->buildKey($key, $nth)}' was not found in the store.");
        }

        return $this->get($key, $nth);
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
     * the $nth param. For example:
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
     * Please note: The nth value in the string key is indexed from 1. In that 1st is the very first item stored.
     * The nth value in the nth parameter is indexed from 0. In that 0 is the first item stored.
     *
     * Please Note: You should not specify both an $nth *and* a plain english nth via the $key. If you
     * do, the method will throw an InvalidArgumentException. e.g:
     *
     * retrieve("1st Thing", 1);
     *
     * @param  string                   $key The key to retrieve the value for. Can be prefixed with an nth descriptor.
     * @param  int                      $nth [optional] The nth (zero indexed) value for the key to retrieve.
     * @throws InvalidArgumentException $nth parameter is provided and $key contains an nth value, but they don't match.
     * @return mixed                    The value, or null if no value exists for the specified key/nth combination.
     */
    public function get($key, $nth = null)
    {
        list($key, $nth) = $this->parseKey($key, $nth);

        if (!$this->has($key, $nth)) {
            return;
        }

        return $nth !== null ? $this->nouns[$key][$nth] : end($this->nouns[$key]);
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
     * @param  string                   $key The key to check.
     * @param  int                      $nth The nth (zero indexed) value for the key to check. If not specified, the
     *                                       method will ensure that at least one item is stored for the specified key.
     * @throws InvalidArgumentException $nth parameter is provided and $key contains an nth value, but they don't match.
     * @return bool                     True if the a value has been stored, false if not.
     */
    public function has($key, $nth = null)
    {
        list($key, $nth) = $this->parseKey($key, $nth);

        return $nth !== null ? isset($this->nouns[$key][$nth]) : isset($this->nouns[$key]);
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
     * Parses a key into the separate key and nth value.
     *
     * @example parseKey("Item"): ["Item", null]
     * @example parseKey("Item", 1): ["Item", 1]
     * @example parseKey("1st Item"): ["Item", 0]
     * @example parseKey("2nd Item"): ["Item", 1]
     * @example parseKey("3rd Item"): ["Item", 2]
     *
     * @param  string                   $key the key to parse.
     * @param  int                      $nth the nth to return if the key does not contain one.
     * @throws InvalidArgumentException $nth parameter is provided and $key contains an nth value, but they don't match.
     * @return array                    a tuple, the 1st being the key with the nth removed, and the 2nd being the nth.
     */
    protected function parseKey($key, $nth = null)
    {
        if (preg_match('/^([1-9][0-9]*)(?:st|nd|rd|th) (.+)$/', $key, $matches)) {
            if ($nth !== null && $nth != $matches[1] - 1) {
                throw new InvalidArgumentException(
            "$nth was provided for nth param when key '$key' contains an nth value, but they do not match"
        );
            }

            $nth = $matches[1] - 1;
            $key = $matches[2];
        }

        return [$key, $nth];
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
     * @param  int|null                 $index The index (zero indexed) value for the key. If not specified, the method
     *                                         will not add an nth notation to the key.
     * @throws InvalidArgumentException if $key is not a string.
     * @throws InvalidArgumentException if $index is not an int.
     * @return string                   the key with the nth, or just the key if index is null.
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
        switch (substr($nth, -1)) {
            case 1:
                $ordinal = self::FIRST_ORDINAL;
                break;
            case 2:
                $ordinal = self::SECOND_ORDINAL;
                break;
            case 3:
                $ordinal = self::THIRD_ORDINAL;
                break;
            default:
                $ordinal = self::FOURTH_THROUGH_NINTH_ORDINAL;
                break;
        }

        return $ordinal;
    }
}

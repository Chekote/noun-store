<?php namespace Chekote\NounStore;

use InvalidArgumentException;
use OutOfBoundsException;

class Store
{
    /** @var Key */
    protected $keyService;

    /** @var array */
    protected $nouns;

    /**
     * @param Key $keyService the key service to use for parsing and building keys
     * @codeCoverageIgnore
     */
    public function __construct(Key $keyService = null)
    {
        $this->keyService = $keyService ?: Key::getInstance();
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
     * the method will return the most recent entry. You can optionally specify the entry you want by
     * using the plain english 1st, 2nd, 3rd etc in the $key param. For example:
     *
     * Retrieve the most recent entry "Thing" collection:
     *   retrieve("Thing")
     *
     * Retrieve the 1st entry in the "Thing" collection:
     *   retrieve("1st Thing")
     *
     * Retrieve the 3rd entry in the "Thing" collection:
     *   retrieve("3rd Thing")
     *
     * @see    Key::build()
     * @see    Key::parse()
     * @param  string                   $key The key to retrieve the value for. Can be prefixed with an nth descriptor.
     * @throws InvalidArgumentException if the key syntax is invalid.
     * @return mixed                    The value, or null if no value exists for the specified key/index combination.
     */
    public function get($key)
    {
        if (!$this->keyExists($key)) {
            return null;
        }

        list($key, $index) = $this->keyService->parse($key);

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
     * @see    Key::build()
     * @see    Key::parse()
     * @param  string                   $key   The key to check.
     * @throws InvalidArgumentException if the key syntax is invalid.
     * @return bool                     True if the a value has been stored, false if not.
     */
    public function keyExists($key)
    {
        list($key, $index) = $this->keyService->parse($key);

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
        list($key, $index) = $this->keyService->parse($key, $index);

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
}

<?php namespace Chekote\NounStore;

use Illuminate\Support\Arr;
use InvalidArgumentException;

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
     * @see    Key::parseNoun()
     * @param  string                   $key The key to retrieve the value for. Supports nth notation.
     * @throws InvalidArgumentException if the key syntax is invalid.
     * @return mixed                    The value, or null if no value exists for the specified key/index combination.
     */
    public function get($key)
    {
        $i = 0;

        return array_reduce(
            $this->keyService->parse($key),
            static function ($carry, $item) use (&$i) {
                list($noun, $index) = $item;

                $carry = data_get($carry, $noun);

                try {
                    // Was a specific index requested?
                    if ($index !== null) {
                        // Yes, fetch the specific index
                        return data_get($carry, $index);
                    } else {
                        // No, return the noun itself, or the latest noun if this is the
                        // first component of the key, and the noun is a collection
                        return $i === 0 && Arr::accessible($carry) ? end($carry) : $carry;
                    }
                } finally {
                    ++$i;
                }
            },
            $this->nouns
        );
    }

    /**
     * Retrieves all values for the specified key.
     *
     * @param  string $key The key to retrieve the values for. Does not support nth notation.
     * @return array  The values, or an empty array if no value exists for the specified key.
     */
    public function getAll($key)
    {
        return isset($this->nouns[$key]) ? $this->nouns[$key] : [];
    }

    /**
     * Determines if a value has been stored for the specified key.
     *
     * @see    Key::build()
     * @see    Key::parseNoun()
     * @param  string                   $key The key to check. Supports nth notation.
     * @throws InvalidArgumentException if the key syntax is invalid.
     * @return bool                     True if the a value has been stored, false if not.
     */
    public function keyExists($key)
    {
        return $this->get($key) !== null;
    }

    /**
     * Asserts that the key's value contains the specified string.
     *
     * @see    Key::build()
     * @see    Key::parseNoun()
     * @param  string                   $key   The key to check.
     * @param  string                   $value The value expected to be contained within the key's value.
     * @throws InvalidArgumentException if the key syntax is invalid.
     * @return bool                     True if the key's value contains the specified string, false if not.
     */
    public function keyValueContains($key, $value)
    {
        $actual = $this->get($key);

        return is_string($actual) && strpos($actual, $value) !== false;
    }

    /**
     * Stores a value for the specified key.
     *
     * The specified value is added to the top of the "stack" for the specified key.
     *
     * @param string $key   The key to store the value under. Does not support nth notation.
     * @param mixed  $value The value to store.
     */
    public function set($key, $value)
    {
        $this->nouns[$key][] = $value;
    }

    /**
     * Asserts that the key's value contains the specified class instance.
     *
     * @see    Key::build()
     * @see    Key::parseNoun()
     * @param  string                   $key   The key to check.
     * @param  string                   $class The class instance expected to be contained within the key's value.
     * @throws InvalidArgumentException if the key syntax is invalid.
     * @return bool                     True if the key's value contains the specified class instance, false if not.
     */
    public function keyIsClass($key, $class)
    {
        $actual = $this->get($key);

        return $actual instanceof $class;
    }
}

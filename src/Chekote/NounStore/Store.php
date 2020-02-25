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
     * @see    Key::parse()
     * @param  string                   $key The key to retrieve the value for. Supports nth notation.
     * @throws InvalidArgumentException if the key syntax is invalid.
     * @return mixed                    The value, or null if no value exists for the specified key/index combination.
     */
    public function get($key)
    {
        $dotPath = $this->keyService->parse($key);

        $item = Arr::get($this->nouns, $dotPath);

        // if the end of the dotpath references a specific instance of a noun, return it.
        if (is_numeric(substr($dotPath, -1, 1))) {
            return $item;
        }

        // otherwise, return the last referenced noun.
        return is_array($item) ? end($item) : null;
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
     * @see    Key::parse()
     * @param  string                   $key The key to check. Supports nth notation.
     * @throws InvalidArgumentException if the key syntax is invalid.
     * @return bool                     True if the a value has been stored, false if not.
     */
    public function keyExists($key)
    {
        return Arr::has($this->nouns, $this->keyService->parse($key));
    }

    /**
     * Asserts that the key's value contains the specified string.
     *
     * @see    Key::build()
     * @see    Key::parse()
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
}

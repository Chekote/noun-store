<?php namespace Chekote\NounStore;

use InvalidArgumentException;
use OutOfBoundsException;

/**
 * Makes assertions regarding store data.
 */
class Assert
{
    protected Store $store;

    protected Key $keyService;

    /**
     * Assert constructor.
     *
     * @param Store    $store      the store to assert against.
     * @param Key|null $keyService the keyService to use for key parsing/building. Will use the default Key service
     *                             if not specified.
     *
     * @codeCoverageIgnore
     */
    public function __construct(Store $store, Key $keyService = null)
    {
        $this->store = $store;
        $this->keyService = $keyService ?: Key::getInstance();
    }

    /**
     * Asserts that a value has been stored for the specified key.
     *
     * @see    Key::build()
     * @see    Key::parseNoun()
     * @param  string                   $key The key to check. Supports nth notation.
     * @throws OutOfBoundsException     if a value has not been stored for the specified key.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                  that does not match the index.
     * @return mixed                    The value.
     */
    public function keyExists($key): mixed
    {
        if (!$this->store->keyExists($key)) {
            throw new OutOfBoundsException("Entry '$key' was not found in the store.");
        }

        return $this->store->get($key);
    }

    /**
     * Asserts that the key's value contains the specified string.
     *
     * @see    Key::build()
     * @see    Key::parseNoun()
     * @param  string                   $key    The key to check. Supports nth notation.
     * @param  string                   $needle The value expected to be contained within the key's value.
     * @throws AssertionFailedException If the key's value does not contain the specified string.
     * @throws OutOfBoundsException     If a value has not been stored for the specified key.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                  that does not match the index.
     * @return mixed                    The key's value.
     */
    public function keyValueContains($key, $needle): mixed
    {
        $haystack = $this->keyExists($key);

        if (!$this->store->keyValueContains($key, $needle)) {
            throw new AssertionFailedException("Entry '$key' does not contain '$needle'");
        }

        return $haystack;
    }

    /**
     * Asserts that the key's value matches the specified value.
     *
     * @see    Key::build()
     * @see    Key::parseNoun()
     * @param  string                   $key   The key to check. Supports nth notation.
     * @param  mixed                    $value The expected value.
     * @throws AssertionFailedException If the key's value does not match the specified value.
     * @throws OutOfBoundsException     If a value has not been stored for the specified key.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                  that does not match the index.
     */
    public function keyValueIs($key, $value): void
    {
        if ($this->keyExists($key) != $value) {
            throw new AssertionFailedException("Entry '$key' does not match '" . print_r($value, true) . "'");
        }
    }

    /**
     * Asserts that the key's value matches the specified class instance.
     *
     * @see    Key::build()
     * @see    Key::parseNoun()
     * @param  string                   $key   The key to check. Supports nth notation.
     * @param  class-string             $class The expected class instance.
     * @throws AssertionFailedException If the key's value is not an instance of the specified class.
     * @throws OutOfBoundsException     If a value has not been stored for the specified key.
     * @return mixed                    The key's value.
     */
    public function keyIsClass(string $key, string $class): mixed
    {
        if ($this->keyExists($key) && !$this->store->keyIsClass($key, $class)) {
            throw new AssertionFailedException("Entry '$key' does not match instance of '$class'");
        }

        return $this->store->get($key);
    }
}

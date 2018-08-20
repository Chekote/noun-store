<?php namespace Chekote\NounStore;

use InvalidArgumentException;
use OutOfBoundsException;
use RuntimeException;

/**
 * Makes assertions regarding store data.
 */
class Assert
{
    /** @var Store */
    protected $store;

    /** @var Key */
    protected $keyService;

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
     * @see    Key::parse()
     * @param  string                   $key   The key to check. Supports nth notation.
     * @throws OutOfBoundsException     if a value has not been stored for the specified key.
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                        that does not match the index.
     * @return mixed                    The value.
     */
    public function keyExists($key)
    {
        if (!$this->store->keyExists($key)) {
            throw new OutOfBoundsException("Entry '$key' was not found in the store.");
        }

        return $this->store->get($key);
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
    public function keyValueContains($key, $value, $index = null)
    {
        list($key, $index) = $this->keyService->parse($key, $index);

        $this->keyExists($key, $index);

        if (!$this->store->keyValueContains($key, $value, $index)) {
            throw new RuntimeException(
                "Entry '" . $this->keyService->build($key, $index) . "' does not contain '$value'"
            );
        }
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
    public function keyValueIs($key, $value, $index = null)
    {
        list($key, $index) = $this->keyService->parse($key, $index);

        if ($this->keyExists($key, $index) != $value) {
            throw new RuntimeException(
                "Entry '" . $this->keyService->build($key, $index) . "' does not match '" . print_r($value, true) . "'"
            );
        }
    }
}

<?php namespace Chekote\NounStore;

use InvalidArgumentException;
use OutOfBoundsException;

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
     * @param Store $store      the store to assert against.
     * @param Key   $keyService the keyService to use for key parsing/building.
     *
     * @codeCoverageIgnore
     */
    public function __construct(Store $store, Key $keyService)
    {
        $this->store = $store;
        $this->keyService = $keyService;
    }

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
    public function keyExists($key, $index = null)
    {
        list($key, $index) = $this->keyService->parse($key, $index);

        if (!$this->store->keyExists($key, $index)) {
            throw new OutOfBoundsException(
                "Entry '" . $this->keyService->build($key, $index) . "' was not found in the store."
            );
        }

        return $this->store->get($key, $index);
    }
}

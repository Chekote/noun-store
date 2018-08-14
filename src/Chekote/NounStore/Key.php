<?php namespace Chekote\NounStore;

use InvalidArgumentException;

class Key
{
    use Singleton;

    const ORDINAL_ST = 'st';
    const ORDINAL_ND = 'nd';
    const ORDINAL_RD = 'rd';
    const ORDINAL_TH = 'th';

    protected static $ordinals = [
        0 => self::ORDINAL_TH,
        1 => self::ORDINAL_ST,
        2 => self::ORDINAL_ND,
        3 => self::ORDINAL_RD,
        4 => self::ORDINAL_TH,
        5 => self::ORDINAL_TH,
        6 => self::ORDINAL_TH,
        7 => self::ORDINAL_TH,
        8 => self::ORDINAL_TH,
        9 => self::ORDINAL_TH,
    ];

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
     *                                         will not add an index notation to the key.
     * @throws InvalidArgumentException if $index is less than -1. Note: It should really be zero or higher, but this
     *                                        method does not assert that. The error is bubbling up from getOrdinal()
     * @return string                   the key with the index, or just the key if index is null.
     */
    public function build($key, $index)
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
     * @param  int                      $nth the number to determine the ordinal for
     * @throws InvalidArgumentException if $nth is not a positive number.
     * @return string                   the ordinal
     */
    public function getOrdinal($nth)
    {
        if ($nth < 0) {
            throw new InvalidArgumentException('$nth must be a positive number');
        }

        return $nth > 9 && $nth < 20 ? self::ORDINAL_TH : self::$ordinals[substr($nth, -1)];
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
    public function parse($key, $index = null)
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
     * Resolves an index and parsed nth value to an index.
     *
     * Ensures that if both an index and parsed nth value are provided, that they are equivalent. If only one is
     * provided, then the appropriate index will be returned. e.g. if an index is provided, it is returned as-is, as
     * it is already an index. If an nth is provided, it will be returned decremented by 1.
     *
     * @param  int|null                 $index the index to process
     * @param  int|null                 $nth   the nth to process
     * @throws InvalidArgumentException if both an $index and $key are provided, but the $key contains an nth value
     *                                        that does not match the index.
     * @throws InvalidArgumentException if nth is not null and is less than 1
     * @return int                      the resolved index.
     */
    protected function resolveIndex($index, $nth)
    {
        // If we don't have an nth, there's nothing to process. We'll just return the $index, even if it's null.
        if ($nth === null) {
            return $index;
        }

        $decrementedNth = $nth - 1;

        // If both index and nth are provided, but they aren't equivalent, we need to error out.
        if ($index !== null && $index !== $decrementedNth) {
            throw new InvalidArgumentException("index $index was provided with nth $nth, but they are not equivalent");
        }

        if ($decrementedNth < 0) {
            throw new InvalidArgumentException('nth must be equal to or larger than 1');
        }

        return $decrementedNth;
    }
}

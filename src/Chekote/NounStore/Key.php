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

    const POSSESSION = "'s ";

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
     * @example parseKey("1st Item"): ["Item", 0]
     * @example parseKey("2nd Item"): ["Item", 1]
     * @example parseKey("3rd Item"): ["Item", 2]
     *
     * @param  string                   $key the key to parse.
     * @throws InvalidArgumentException if the key syntax is invalid.
     * @return array                    a tuple, the 1st being the key with the nth removed, and the 2nd being the
     *                                      index that the nth translates to, or null if no nth was specified.
     */
    public function parse($key)
    {
        if (!preg_match("/^(([1-9][0-9]*)(?:st|nd|rd|th) )?([^']+)$/", $key, $matches)) {
            throw new InvalidArgumentException('Key syntax is invalid');
        }

        // @todo use null coalescing operator when upgrading to PHP 7
        $index = isset($matches[2]) && $matches[2] !== '' ? $matches[2] - 1 : null;
        $key = $matches[3];

        return [$key, $index];
    }

    /**
     * Determines if the specified key is a possessive noun.
     *
     * @param  string $key
     * @return bool   true if the key is possessive, false if not
     */
    protected function isPossessive($key)
    {
        return strpos($key, self::POSSESSION) !== false;
    }

    /**
     * Splits a possessive key into its separate nouns.
     *
     * @example splitPossessions("Customer's Car"): ['Customer', 'Car']
     * @example splitPossessions("8th Customer's Car"): ['8th Customer', 'Car']
     * @example splitPossessions("Customer's 2nd Car"): ['Customer', '2nd Car']
     * @example splitPossessions("7th Customer's 4th Car"): ['7th Customer', '4th Car']
     * @example splitPossessions("7th Customer's 4th Car's 2nd Wheel"): ['7th Customer', '4th Car', '2nd Wheel']
     *
     * @param  string   $key the possessive key to parse
     * @return string[] an array of nouns
     */
    protected function splitPossessions($key)
    {
        return ($nouns = preg_split('/' . self::POSSESSION . '/', $key)) ? $nouns : [];
    }
}

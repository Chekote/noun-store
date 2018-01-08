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
     * Provides the ordinal notation for the specified nth number.
     *
     * @param  int    $nth the number to determine the ordinal for
     * @return string the ordinal
     */
    public function getOrdinal($nth)
    {
        if ($nth < 0) {
            throw new InvalidArgumentException('$nth must be a positive number');
        }

        return $nth > 9 && $nth < 20 ? self::ORDINAL_TH : self::$ordinals[substr($nth, -1)];
    }
}

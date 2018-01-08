<?php namespace Chekote\NounStore\Key;

use InvalidArgumentException;

class GetOrdinalTest extends KeyTest
{
    public function successScenariosDataProvider()
    {
        return [
        //   nth  expected
            [0,   'th'],
            [1,   'st'],
            [2,   'nd'],
            [3,   'rd'],
            [4,   'th'],
            [5,   'th'],
            [6,   'th'],
            [7,   'th'],
            [8,   'th'],
            [9,   'th'],
            [10,  'th'],
            [11,  'th'],
            [12,  'th'],
            [13,  'th'],
            [14,  'th'],
            [21,  'st'],
            [22,  'nd'],
            [23,  'rd'],
            [24,  'th'],
            [101, 'st'],
            [102, 'nd'],
            [103, 'rd'],
            [104, 'th'],
        ];
    }

    public function failureScenariosDataProvider()
    {
        return [
        //   nth  exception class                  exception message
            [-1,  InvalidArgumentException::class, '$nth must be a positive number'],
        ];
    }

    /**
     * Executes a success scenario against the method.
     *
     * @dataProvider successScenariosDataProvider
     * @param int    $nth      the nth to pass to the method.
     * @param string $expected the ordinal expected from the method.
     */
    public function testSuccessScenario($nth, $expected)
    {
        $actual = $this->key->getOrdinal($nth);;

        $this->assertEquals($expected, $actual);
    }

    /**
     * Executes a failure scenario against the method.
     *
     * @dataProvider failureScenariosDataProvider
     * @param int    $nth              the nth to pass to the method.
     * @param string $exceptionClass   the expected class of the exception.
     * @param string $exceptionMessage the expected message of the exception.
     */
    public function testFailureScenario($nth, $exceptionClass, $exceptionMessage)
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        $this->key->getOrdinal($nth);
    }
}

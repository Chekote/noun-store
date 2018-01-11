<?php namespace Chekote\PHPUnit\Framework;

use Exception;
use PHPUnit\Framework\TestCase as BaseTestCase;
use PHPUnit_Framework_Constraint_Exception;
use PHPUnit_Framework_Constraint_ExceptionMessage;
use PHPUnit_Framework_ExpectationFailedException;
use Throwable;

/**
 * Adds additional functionality to the PHPUnit TestCase class
 */
class TestCase extends BaseTestCase
{
    /**
     * Asserts that the lambda throws the specified exception.
     *
     * @param  Exception $expected the expected exception (class and message must match)
     * @param  callable  $lambda   the lambda to execute
     * @throws PHPUnit_Framework_ExpectationFailedException If the specified exception is not thrown.`
     */
    public function assertException(Exception $expected, callable $lambda): void {
        try {
            $lambda();
            $this->fail(get_class($expected) . ' was not thrown');
        } catch (Throwable | Exception $actual) {
            $this->assertThat($actual, new PHPUnit_Framework_Constraint_Exception(get_class($expected)));
            $this->assertThat($actual, new PHPUnit_Framework_Constraint_ExceptionMessage($expected->getMessage()));
        }
    }
}

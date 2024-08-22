<?php namespace Unit\Chekote\Phake;

use Phake_IMock;
use Phake_Proxies_StubberProxy;
use Phake_Proxies_VerifierProxy;
use Unit\Chekote\Phake\Exception\ExpectationException;

/**
 * Combines a Phake Stubber and Verifier to create an expectation.
 *
 * This class is used to accomplish something similar to Prophecy predictions, but without the opinionated restrictions
 * that come with that mocking framework.
 */
class Expectation
{
    /** @var Phake_IMock the mock object */
    protected $mock;

    /** @var string the method expected to be called */
    protected $method;

    /** @var array the arguments expected to be passed to the method */
    protected $args;

    /** @var int the number of times the method is expected to be called */
    protected $count;

    /**
     * Expectation constructor.
     *
     * @param Phake_IMock $mock  the mock object
     * @param int         $count number of times the method is expected to be called
     */
    public function __construct(Phake_IMock $mock, $count)
    {
        $this->mock = $mock;
        $this->count = $count;
    }

    /**
     * Verifies that the expected method was called.
     *
     * @throws ExpectationException        if a method has not been set for the expectation.
     * @throws ExpectationException        if args have not been set for the expectation.
     * @return array|Phake_Proxies_VerifierProxy
     */
    public function verify(): array|Phake_Proxies_VerifierProxy
    {
        if (!isset($this->method)) {
            throw new ExpectationException('Expectation method was not set');
        }

        if (!isset($this->args)) {
            throw new ExpectationException('Expectation args were not set');
        }

        /** @var Phake_Proxies_VerifierProxy $verifier */
        return Phake::verify($this->mock, Phake::times($this->count))->{$this->method}(...$this->args);
    }

    /**
     * Sets the expected method to be called.
     *
     * @param  string                     $method the method that is expected to be called.
     * @param  array                      $args   the args that are expected to be passed to the method.
     * @return Phake_Proxies_AnswerBinderProxy|Phake_Proxies_StubberProxy
     */
    public function __call($method, array $args): Phake_Proxies_AnswerBinderProxy|Phake_Proxies_StubberProxy
    {
        // record the method and args for verification later
        $this->method = $method;
        $this->args = $args;

        return Phake::when($this->mock)->$method(...$args);
    }
}

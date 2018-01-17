<?php namespace Chekote\Phake;

use Phake_IMock;
use Phake_Proxies_StubberProxy;
use Phake_Proxies_VerifierProxy;
use RuntimeException;

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
    public function __construct(Phake_IMock $mock, int $count)
    {
        $this->mock = $mock;
        $this->count = $count;
    }

    /**
     * Verifies that the expected method was called.
     *
     * @throws RuntimeException            if a method has not been set for the expectation.
     * @throws RuntimeException            if args have not been set for the expectation.
     * @return Phake_Proxies_VerifierProxy
     */
    public function verify()
    {
        if (!isset($this->method)) {
            throw new RuntimeException('Expectation method was not set');
        }

        if (!isset($this->args)) {
            throw new RuntimeException('Expectation args were not set');
        }

        $method = $this->method;

        /** @var Phake_Proxies_VerifierProxy $verifier */
        $verifier = Phake::verify($this->mock, Phake::times($this->count))->$method(...$this->args);

        return $verifier;
    }

    /**
     * Sets the expected method to be called.
     *
     * @param  string                     $method the method that is expected to be called.
     * @param  array                      $args   the args that are expected to be passed to the method.
     * @return Phake_Proxies_StubberProxy
     */
    public function __call(string $method, array $args)
    {
        // record the method and args for verification later
        $this->method = $method;
        $this->args = $args;

        return Phake::when($this->mock)->$method(...$args);
    }
}

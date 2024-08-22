<?php namespace Unit\Chekote\Phake;

use Phake as BasePhake;
use Phake_CallRecorder_Recorder;
use Phake_ClassGenerator_MockClass;
use Phake_IMock;
use Phake_Stubber_AnswerCollection;
use RuntimeException;
use Unit\Chekote\Phake\Proxies\VisibilityProxy;
use Unit\Chekote\Phake\Stubber\Answers\UnMockedResponseExceptionAnswer;

/**
 * Extends Phake to add strict mock functionality.
 *
 * A strict mock will throw an Exception stating the class and method name of any method that is called without
 * its response being mocked. This is to assist in strict London style TDD, whereby only explicitly mocked out
 * methods are allowed to be invoked.
 */
abstract class Phake extends BasePhake
{
    /** @var Expectation[] */
    protected static $expectations;

    public static function clearExpectations(): void
    {
        self::$expectations = [];
    }

    /**
     * Declare an expectation for a mock.
     *
     * This method combines the behavior of when() and verify() to create an expectation. The returned expectation
     * should be utilized in the same way that the Stubber from when() would be used. The expectations will be
     * recorded and can be verified via verifyExpectations(), which should typically be invoked in tearDown().
     *
     * @param  Phake_IMock $mock  the mock.
     * @param  int         $count the expected call count.
     * @return Expectation the expectation.
     */
    public static function expect(Phake_IMock $mock, $count): Expectation
    {
        $expectation = new Expectation($mock, $count);
        self::$expectations[] = $expectation;

        return $expectation;
    }

    /**
     * Verifies all expectations.
     *
     * @throws RuntimeException if a method has not been set for the expectation.
     * @throws RuntimeException if args have not been set for the expectation.
     */
    public static function verifyExpectations(): void
    {
        foreach (self::$expectations as $expectation) {
            $expectation->verify();
        }
    }

    /**
     * Overrides the base method to use our custom VisibilityProxy.
     *
     * @param  Phake_IMock     $mock
     * @return VisibilityProxy $mock
     */
    public static function makeVisible(Phake_IMock $mock)
    {
        return new VisibilityProxy($mock);
    }

    /**
     * Creates a strict mock.
     *
     * @param  string      $className the name of the class to mock.
     * @return Phake_IMock the mocked class instance.
     */
    public static function strictMock($className): Phake_IMock
    {
        return self::mock($className, new Phake_Stubber_AnswerCollection(new UnMockedResponseExceptionAnswer()));
    }

    /**
     * Creates a strict mock and calls the real classes constructor.
     *
     * This method creates a mock that is somewhere between what Phake::mock and Phake::partialMock would create. The
     * returned mock behaves exactly the same as what Phake::mock would return, except that it calls the constructor
     * of the mocked class (as Phake::partialMock does). However, the returned mock will NOT thenCallParent() for every
     * mocked method in the way that a partial mock would.
     *
     * @param  string      $className the name of the class to mock.
     * @param  mixed       ...$args   arguments for the classes constructor.
     * @return Phake_IMock the mocked class instance.
     */
    public static function strictMockWithConstructor($className, ...$args): Phake_IMock
    {
        return self::getPhake()->mock(
            $className,
            new Phake_ClassGenerator_MockClass(self::getMockLoader()),
            new Phake_CallRecorder_Recorder(),
            new UnMockedResponseExceptionAnswer(),
            $args
        );
    }
}

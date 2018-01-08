<?php namespace Chekote\Phake;

use Chekote\Phake\Stubber\Answers\UnMockedResponseExceptionAnswer;
use Phake as BasePhake;
use Phake_CallRecorder_Recorder;
use Phake_ClassGenerator_MockClass;
use Phake_Stubber_AnswerCollection;

/**
 * Extends Phake to add strict mock functionality.
 *
 * A strict mock will throw an Exception stating the class and method name of any method that is called without
 * its response being mocked. This is to assist in strict London style TDD, whereby only explicitly mocked out
 * methods are allowed to be invoked.
 */
abstract class Phake extends BasePhake
{
    /**
     * Creates a strict mock.
     *
     * @param  string $className the name of the class to mock.
     * @return mixed  the mocked class instance.
     */
    public static function strictMock(string $className)
    {
        return Phake::mock($className, new Phake_Stubber_AnswerCollection(new UnMockedResponseExceptionAnswer()));
    }

    /**
     * Creates a strict mock and calls the real classes constructor.
     *
     * This method creates a mock that is somewhere between what Phake::mock and Phake::partialMock would create. The
     * returned mock behaves exactly the same as what Phake::mock would return, except that it calls the constructor
     * of the mocked class (as Phake::partialMock does). However, the returned mock will NOT thenCallParent() for every
     * mocked method in the way that a partial mock would.
     *
     * @param  string $className the name of the class to mock.
     * @param  array  ...$args   arguments for the classes constructor.
     * @return mixed  the mocked class instance.
     */
    public static function strictMockWithConstructor(string $className, ...$args)
    {
        return Phake::getPhake()->mock(
            $className,
            new Phake_ClassGenerator_MockClass(Phake::getMockLoader()),
            new Phake_CallRecorder_Recorder(),
            new UnMockedResponseExceptionAnswer(),
            $args
        );
    }
}

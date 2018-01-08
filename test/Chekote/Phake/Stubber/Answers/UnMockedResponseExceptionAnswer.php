<?php namespace Chekote\Phake\Stubber\Answers;

use Chekote\Phake\Exception\UnMockedResponseException;
use Phake_Stubber_IAnswer;

/**
 * Throws an Exception stating the class and method name that was called.
 */
class UnMockedResponseExceptionAnswer implements Phake_Stubber_IAnswer
{
    /**
     * {@inheritdoc}
     */
    public function getAnswerCallback($context, $method)
    {
        $class = get_parent_class($context);
        return function () use ($class, $method) {
            throw new UnMockedResponseException(
                "$class::$method was called on mock without having its response mocked"
            );
        };
    }

    /**
     * {@inheritdoc}
     */
    public function processAnswer($answer)
    {
    }
}

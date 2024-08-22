<?php namespace Unit\Chekote\Phake\Stubber\Answers;

use Phake\Stubber\IAnswer;
use Unit\Chekote\Phake\Exception\UnMockedResponseException;

/**
 * Throws an Exception stating the class and method name that was called.
 */
class UnMockedResponseExceptionAnswer implements IAnswer
{
    /**
     * {@inheritdoc}
     */
    public function getAnswerCallback($context, $method): callable
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
    public function processAnswer($answer): void
    {
    }
}

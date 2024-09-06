<?php namespace Unit\Chekote\NounStore;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Unit\Chekote\Phake\Phake;

abstract class TestCase extends BaseTestCase
{
    /**
     * @before
     */
    public function clearExpectations(): void
    {
        Phake::clearExpectations();
    }

    /**
     * @after
     */
    public function verifyExpectations(): void
    {
        Phake::verifyExpectations();
    }
}

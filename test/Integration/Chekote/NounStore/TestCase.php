<?php namespace Integration\Chekote\NounStore;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Unit\Chekote\Phake\Phake;

class TestCase extends BaseTestCase
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

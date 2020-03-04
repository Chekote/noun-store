<?php namespace Integration\Chekote\NounStore;

use Unit\Chekote\Phake\Phake;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @before
     */
    public function clearExpectations()
    {
        Phake::clearExpectations();
    }

    /**
     * @after
     */
    public function verifyExpectations()
    {
        Phake::verifyExpectations();
    }
}

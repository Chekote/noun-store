<?php namespace Integration\Chekote\NounStore;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Unit\Chekote\Phake\Phake;

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

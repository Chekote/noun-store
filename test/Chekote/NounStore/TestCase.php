<?php namespace Chekote\NounStore;

use Chekote\Phake\Phake;
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

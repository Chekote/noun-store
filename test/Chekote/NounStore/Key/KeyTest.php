<?php namespace Chekote\NounStore\Key;

use Chekote\NounStore\Key;
use PHPUnit\Framework\TestCase;

abstract class KeyTest extends TestCase
{
    /** @var Key */
    protected $key;

    /**
     * Sets up the environment before each test.
     */
    public function setUp()
    {
        $this->key = Key::getInstance();
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown()
    {
        $this->key = null;
    }
}

<?php namespace Chekote\NounStore\Key;

use Chekote\NounStore\Key;
use Chekote\Phake\Phake;
use Phake_IMock;
use PHPUnit\Framework\TestCase;

abstract class KeyTest extends TestCase
{
    /** @var Key|Phake_IMock */
    protected $key;

    /**
     * Sets up the environment before each test.
     */
    public function setUp()
    {
        $this->key = Phake::strictMock(Key::class);
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown()
    {
        $this->key = null;
    }
}

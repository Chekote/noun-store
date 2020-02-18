<?php namespace Chekote\NounStore\Key;

use Chekote\NounStore\Key;
use Chekote\NounStore\TestCase;
use Chekote\Phake\Phake;
use Phake_IMock;

abstract class KeyTest extends TestCase
{
    const INVALID_KEY = "It's's invalid because of the double apostrophe";

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

        Phake::verifyExpectations();
    }
}

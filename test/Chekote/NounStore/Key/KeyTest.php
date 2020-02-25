<?php namespace Chekote\NounStore\Key;

use Chekote\NounStore\Key;
use Chekote\NounStore\TestCase;
use Chekote\Phake\Phake;

abstract class KeyTest extends TestCase
{
    const INVALID_KEY = "It's invalid because of the apostrophe";

    /** @var KeyPhake */
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

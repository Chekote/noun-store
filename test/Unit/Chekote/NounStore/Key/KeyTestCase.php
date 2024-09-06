<?php namespace Unit\Chekote\NounStore\Key;

use Chekote\NounStore\Key;
use Phake\IMock;
use Unit\Chekote\NounStore\Assert\KeyPhake;
use Unit\Chekote\NounStore\TestCase;
use Unit\Chekote\Phake\Phake;

abstract class KeyTestCase extends TestCase
{
    const INVALID_KEY = "It's's invalid because of the double apostrophe";

    protected IMock|KeyPhake $key;

    /**
     * Sets up the environment before each test.
     */
    public function setUp(): void
    {
        $this->key = Phake::strictMock(Key::class);
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown(): void
    {
        unset($this->key);

        Phake::verifyExpectations();
    }
}

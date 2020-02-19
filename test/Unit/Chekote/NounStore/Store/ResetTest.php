<?php namespace Unit\Chekote\NounStore\Store;

use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Store::reset()
 */
class ResetTest extends StoreTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->reset()->thenCallParent();
    }

    public function testResetSetsNounsToEmptyArray()
    {
        $this->store->reset();

        $store = Phake::makeVisible($this->store);
        /* @noinspection PhpUndefinedFieldInspection */
        {
            $this->assertEquals('array', gettype($store->nouns));
            $this->assertCount(0, $store->nouns);
        }
    }
}

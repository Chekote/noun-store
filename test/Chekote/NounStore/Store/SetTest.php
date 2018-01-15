<?php namespace Chekote\NounStore\Store;

use Chekote\Phake\Phake;

class SetTest extends StoreTest
{
    public function setUp()
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->set(Phake::anyParameters())->thenCallParent();
    }

    /**
     * Tests that calling store::set once stores the value correctly.
     */
    public function testSetOnceStoresValue()
    {
        $key = 'My Key';
        $value = 'My Value';

        $this->store->set($key, $value);

        $store = Phake::makeVisible($this->store);
        /* @noinspection PhpUndefinedFieldInspection */
        {
            $this->assertCount(1, $store->nouns[$key]);
            $this->assertEquals($value, $store->nouns[$key][0]);
        }
    }

    /**
     * Tests that calling store::set twice for the same key stores both values correctly.
     */
    public function testSetTwiceForSameKeyStoresMultipleValues()
    {
        $key = 'My Key';
        $value1 = 'My Value';
        $value2 = 'My Second Value';

        $this->store->set($key, $value1);
        $this->store->set($key, $value2);

        $store = Phake::makeVisible($this->store);
        /* @noinspection PhpUndefinedFieldInspection */
        {
            $this->assertCount(2, $store->nouns[$key]);
            $this->assertEquals($value1, $store->nouns[$key][0]);
            $this->assertEquals($value2, $store->nouns[$key][1]);
        }
    }
}

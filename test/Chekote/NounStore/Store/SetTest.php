<?php namespace Chekote\NounStore\Store;

use Chekote\NounStore\Store;
use ReflectionClass;

class SetTest extends StoreTest
{
    /**
     * Tests that calling store::set once stores the value correctly.
     */
    public function testSetOnceStoresValue()
    {
        $key = 'My Key';
        $value = 'My Value';

        $class = new ReflectionClass(Store::class);
        $nouns = $class->getProperty('nouns');
        $nouns->setAccessible(true);

        $this->store->set($key, $value);

        $this->assertCount(1, $nouns->getValue($this->store)[$key]);
        $this->assertEquals($value, $nouns->getValue($this->store)[$key][0]);
    }

    /**
     * Tests that calling store::set twice for the same key stores both values correctly.
     */
    public function testSetTwiceForSameKeyStoresMultipleValues()
    {
        $key = 'My Key';
        $value1 = 'My Value';
        $value2 = 'My Second Value';

        $nouns = $this->makePropertyAccessible('nouns');
        $nouns->setAccessible(true);

        $this->store->set($key, $value1);
        $this->store->set($key, $value2);

        $this->assertCount(2, $nouns->getValue($this->store)[$key]);
        $this->assertEquals($value1, $nouns->getValue($this->store)[$key][0]);
        $this->assertEquals($value2, $nouns->getValue($this->store)[$key][1]);
    }
}

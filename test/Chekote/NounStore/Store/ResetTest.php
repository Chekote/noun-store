<?php namespace Chekote\NounStore\Store;

class ResetTest extends StoreTest
{
    public function testResetSetsNounsToEmptyArray()
    {
        $nouns = $this->makePropertyAccessible('nouns');
        $nouns->setAccessible(true);
        $nouns->setValue($this->store, ['Key' => []]);

        $this->store->reset();

        $this->assertEquals('array', gettype($nouns->getValue($this->store)));
        $this->assertCount(0, $nouns->getValue($this->store));
    }
}

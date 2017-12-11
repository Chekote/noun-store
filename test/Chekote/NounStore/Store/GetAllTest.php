<?php namespace Chekote\NounStore\Store;

use OutOfBoundsException;

class GetAllTest extends StoreTest
{
    /**
     * Tests that Store::getAll throws exception when the specified $key does not exist.
     */
    public function testGetAllThrowsExceptionWhenKeyDoesNotExist()
    {
        $this->expectException(OutOfBoundsException::class);

        $this->assertEquals(null, $this->store->getAll('Thing'));
    }

    /**
     * Tests that Store::getAll returns all values for specified key.
     */
    public function testGetAllReturnsAllValuesForSpecifiedKey()
    {
        $values = $this->store->getAll(self::KEY);

        $this->assertCount(2, $values);
        $this->assertEquals(self::FIRST_VALUE, $values[0]);
        $this->assertEquals(self::SECOND_VALUE, $values[1]);
    }
}

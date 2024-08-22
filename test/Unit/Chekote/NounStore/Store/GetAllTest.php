<?php namespace Unit\Chekote\NounStore\Store;

use Unit\Chekote\Phake\Phake;

/**
 * @covers \Chekote\NounStore\Store::getAll()
 */
class GetAllTest extends StoreTest
{
    public function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpUndefinedMethodInspection */
        Phake::when($this->store)->getAll(Phake::anyParameters())->thenCallParent();
    }

    /**
     * Tests that Store::getAll returns an empty array the specified $key does not exist.
     */
    public function testGetAllReturnsEmptyArrayWhenKeyDoesNotExist(): void
    {
        $this->assertEquals([], $this->store->getAll('Thing'));
    }

    /**
     * Tests that Store::getAll returns all values for specified key.
     */
    public function testGetAllReturnsAllValuesForSpecifiedKey(): void
    {
        $values = $this->store->getAll(self::KEY);

        $this->assertCount(2, $values);
        $this->assertEquals(self::$FIRST_VALUE, $values[0]);
        $this->assertEquals(self::$SECOND_VALUE, $values[1]);
    }
}

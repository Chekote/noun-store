<?php namespace Chekote\NounStore;

use Exception;
use InvalidArgumentException;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers Store
 */
class StoreTest extends TestCase {

  /** @var Store */
  protected $store;

  const KEY = 'Some Key';
  const FIRST_VALUE = 'The First Value';
  const SECOND_VALUE = 'The Second Value';

  /**
   * Sets up the environment before each test.
   */
  public function setUp() {
    $this->store = new Store();
    $this->store->set(self::KEY, self::FIRST_VALUE);
    $this->store->set(self::KEY, self::SECOND_VALUE);
  }

  /**
   * Tears down the environment after each test.
   */
  public function tearDown() {
    $this->store = null;
  }

  //--------------------------
  // Store::assertHas tests
  //--------------------------

  /**
   * Tests that InvalidArgumentException is thrown if Store::assertHas is called with the nth parameter
   * and the key also contains an nth value, but they do not match.
   */
  public function testAssertHasThrowsInvalidArgumentExceptionWithMismatchedNthInKeyAndParam() {
    $this->expectException(InvalidArgumentException::class);

    /** @noinspection PhpUnhandledExceptionInspection */
    $this->store->assertHas('1st Thing', 1);
  }

  /**
   * Tests that store::assertHas returns value if nth value in key is found in store
   */
  public function testAssertHasWithExistingNthKeyReturnsValue() {
    /** @noinspection PhpUnhandledExceptionInspection */
    $this->assertEquals(self::FIRST_VALUE, $this->store->assertHas('1st ' . self::KEY));
  }

  /**
   * Tests that store::assertHas throws an Exception if nth value in key is not found in store
   */
  public function testAssertHasWithMissingNthKeyThrowsException() {
    $this->expectException(Exception::class);

    /** @noinspection PhpUnhandledExceptionInspection */
    $this->store->assertHas('3rd ' . self::KEY);
  }

  /**
   * Tests that store::assertHas returns value if nth param value is found in store
   */
  public function testAssertHasWithExistingNthParameterReturnsValue() {
    /** @noinspection PhpUnhandledExceptionInspection */
    $this->assertEquals(self::FIRST_VALUE, $this->store->assertHas(self::KEY, 0));
  }

  /**
   * Tests that store::assertHas throws an Exception if nth param value is not found in store
   */
  public function testAssertHasWithMissingNthParameterThrowsException() {
    $this->expectException(Exception::class);

    /** @noinspection PhpUnhandledExceptionInspection */
    $this->store->assertHas(self::KEY, 2);
  }

  //--------------------------
  // Store::get tests
  //--------------------------

  /**
   * Tests that InvalidArgumentException is thrown if Store::get is called with the nth parameter
   * and the key also contains an nth value, but they do not match.
   */
  public function testGetThrowsInvalidArgumentExceptionWithMismatchedNthInKeyAndParam() {
    $this->expectException(InvalidArgumentException::class);

    $this->store->get('1st Thing', 1);
  }

  /**
   * Tests that Store::get returns the item at the end of the stack.
   */
  public function testGetReturnsItemAtEndOfStack() {
    $this->assertEquals(self::SECOND_VALUE, $this->store->get(self::KEY));
  }

  /**
   * Tests that Store::get returns the nth item at of the stack when the $key contains nth.
   */
  public function testGetWithNthKeyReturnsNthItem() {
    $this->assertEquals(self::FIRST_VALUE, $this->store->get('1st ' . self::KEY));
  }

  /**
   * Tests that Store::get returns the nth item at of the stack when $nth parameter is provided.
   */
  public function testGetWithNthParameterReturnsNthItem() {
    $this->assertEquals(self::FIRST_VALUE, $this->store->get(self::KEY, 0));
  }

  /**
   * Tests that Store::get returns null when the specified $key does not exist.
   */
  public function testGetReturnsNullWhenKeyDoesNotExist() {
    $this->assertEquals(null, $this->store->get('Thing'));
  }

  /**
   * Tests that Store::get returns null when the specified nth $key does not exist.
   */
  public function testGetReturnsNullWhenNthKeyDoesNotExist() {
    $this->assertEquals(null, $this->store->get('3rd ' . self::KEY));
  }

  /**
   * Tests that Store::get returns null when the specified $nth param does not exist.
   */
  public function testGetReturnsNullWhenNthDoesNotExist() {
    $this->assertEquals(null, $this->store->get(self::KEY, 2));
  }

  //--------------------------
  // Store::getAll tests
  //--------------------------

  /**
   * Tests that Store::getAll throws exception when the specified $key does not exist.
   */
  public function testGetAllThrowsExceptionWhenKeyDoesNotExist() {
    $this->expectException(OutOfBoundsException::class);

    $this->assertEquals(null, $this->store->getAll('Thing'));
  }

  /**
   * Tests that Store::getAll returns all values for specified key.
   */
  public function testGetAllReturnsAllValuesForSpecifiedKey() {
    $values = $this->store->getAll(self::KEY);

    $this->assertCount(2, $values);
    $this->assertEquals(self::FIRST_VALUE, $values[0]);
    $this->assertEquals(self::SECOND_VALUE, $values[1]);
  }

  //--------------------------
  // Store::has tests
  //--------------------------

  /**
   * Tests that InvalidArgumentException is thrown if Store::has is called with the nth parameter
   * and the key also contains an nth value, but they do not match.
   */
  public function testHasThrowsInvalidArgumentExceptionWithMismatchedNthInKeyAndParam() {
    $this->expectException(InvalidArgumentException::class);

    $this->store->has('1st Thing', 1);
  }

  /**
   * Tests that store::has returns true if nth value in key is found in store
   */
  public function testHasWithExistingNthKeyReturnsTrue() {
    $this->assertTrue($this->store->has('1st ' . self::KEY));
  }

  /**
   * Tests that store::has returns false if nth value in key is not found in store
   */
  public function testHasWithMissingNthKeyReturnsFalse() {
    $this->assertFalse($this->store->has('3rd ' . self::KEY));
  }

  /**
   * Tests that store::has returns true if nth param value is found in store
   */
  public function testHasWithExistingNthParameterReturnsTrue() {
    $this->assertTrue($this->store->has(self::KEY, 0));
  }

  /**
   * Tests that store::has returns false if nth param value is not found in store
   */
  public function testHasWithMissingNthParameterReturnsFalse() {
    $this->assertFalse($this->store->has(self::KEY, 2));
  }

  //--------------------------
  // Store::set tests
  //--------------------------

  /**
   * Tests that calling store::set once stores the value correctly
   */
  public function testSetOnceStoresValue() {
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
   * Tests that calling store::set twice for the same key stores both values correctly
   */
  public function testSetTwiceForSameKeyStoresMultipleValues() {
    $key = 'My Key';
    $value1 = 'My Value';
    $value2 = 'My Second Value';

    $class = new ReflectionClass(Store::class);
    $nouns = $class->getProperty('nouns');
    $nouns->setAccessible(true);

    $this->store->set($key, $value1);
    $this->store->set($key, $value2);

    $this->assertCount(2, $nouns->getValue($this->store)[$key]);
    $this->assertEquals($value1, $nouns->getValue($this->store)[$key][0]);
    $this->assertEquals($value2, $nouns->getValue($this->store)[$key][1]);
  }
}

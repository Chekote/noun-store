<?php namespace nounstore;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

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

    $this->store->assertHas('1st Thing', 1);
  }

  /**
   * Tests that store::assertHas returns value if nth value in key is found in store
   */
  public function testAssertHasWithExistingNthKeyReturnsValue() {
    $this->assertEquals(self::FIRST_VALUE, $this->store->assertHas('1st ' . self::KEY));
  }

  /**
   * Tests that store::assertHas throws an Exception if nth value in key is not found in store
   */
  public function testAssertHasWithMissingNthKeyThrowsException() {
    $this->expectException(Exception::class);
    $this->store->assertHas('3rd ' . self::KEY);
  }

  /**
   * Tests that store::assertHas returns value if nth param value is found in store
   */
  public function testAssertHasWithExistingNthParameterReturnsValue() {
    $this->assertEquals(self::FIRST_VALUE, $this->store->assertHas(self::KEY, 0));
  }

  /**
   * Tests that store::assertHas throws an Exception if nth param value is not found in store
   */
  public function testAssertHasWithMissingNthParameterThrowsException() {
    $this->expectException(Exception::class);
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
  // Store::has tests
  //--------------------------

  // @todo write tests for Store::has

  //--------------------------
  // Store::set tests
  //--------------------------

  // @todo write tests for Store::set
}

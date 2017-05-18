<?php namespace nounstore;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers Store
 */
class StoreTest extends TestCase {

  /** @var Store */
  protected $store;

  /**
   * Sets up the environment before each test.
   */
  public function setUp() {
    $this->store = new Store();
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

  // @todo write tests for Store::assertHas

  //--------------------------
  // Store::get tests
  //--------------------------

  /**
   * Tests that InvalidArgumentException is thrown if Store::has is called with the nth parameter
   * and the key also contains an nth value, but they do not match.
   */
  public function testGetThrowsInvalidArgumentExceptionWithMismatchedNthInKeyAndParam() {
    $this->expectException(InvalidArgumentException::class);

    $this->store->get('1st Thing', 2);
  }

  /**
   * Tests that Store::get returns the item at the end of the stack.
   */
  public function testGetReturnsItemAtEndOfStack() {
    $key = 'Some Key';
    $firstValue = 'The First Value';
    $secondValue = 'The Second Value';

    $this->store->set($key, $firstValue);
    $this->store->set($key, $secondValue);

    $this->assertEquals($secondValue, $this->store->get($key));
  }

  /**
   * Tests that Store::get returns the nth item at of the stack when the $key contains nth.
   */
  public function testGetWithNthKeyReturnsNthItem() {
    $key = 'Some Key';
    $firstValue = 'The First Value';
    $secondValue = 'The Second Value';

    $this->store->set($key, $firstValue);
    $this->store->set($key, $secondValue);

    $this->assertEquals($firstValue, $this->store->get('1st ' . $key));
  }

  /**
   * Tests that Store::get returns the nth item at of the stack when $nth parameter is provided.
   */
  public function testGetWithNthParameterReturnsNthItem() {
    $key = 'Some Key';
    $firstValue = 'The First Value';
    $secondValue = 'The Second Value';

    $this->store->set($key, $firstValue);
    $this->store->set($key, $secondValue);

    $this->assertEquals($firstValue, $this->store->get($key, 1));
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
    $key = 'Some Key';
    $firstValue = 'The First Value';

    $this->store->set($key, $firstValue);

    $this->assertEquals(null, $this->store->get('2nd ' . $key));
  }

  /**
   * Tests that Store::get returns null when the specified $nth param does not exist.
   */
  public function testGetReturnsNullWhenNthDoesNotExist() {
    $key = 'Some Key';
    $firstValue = 'The First Value';

    $this->store->set($key, $firstValue);

    $this->assertEquals(null, $this->store->get($key, 2));
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

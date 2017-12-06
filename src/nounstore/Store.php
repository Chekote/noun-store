<?php namespace nounstore;

use Exception;
use InvalidArgumentException;
use OutOfBoundsException;

class Store {

  /** @var array */
  protected $nouns;

  /**
   * Ensures that a value has been stored for the specified key.
   *
   * @param   string  $key  The key to check. @see self::get() for formatting options.
   * @param   int     $nth  The nth (zero indexed) value for the key to check. If not specified, the method will
   *                        ensure that at least one item is stored for the specified key.
   * @throws  Exception     If a value has not been stored for the specified key.
   * @return  mixed         The value.
   */
  public function assertHas($key, $nth = null) {
    if (!$this->has($key, $nth)) {
      throw new Exception("Entry $nth for $key was not found in the store.");
    }

    return $this->get($key, $nth);
  }

  /**
   * Removes all entries from the store.
   *
   * @return void
   */
  public function reset() {
    $this->nouns = [];
  }

  /**
   * Retrieves a value for the specified key.
   *
   * Each key is actually a collection. If you do not specify which item in the collection you want,
   * the method will return the most recent entry. You can specify the entry you want by either
   * using the plain english 1st, 2nd, 3rd etc in the $key param, or by specifying 0, 1, 2 etc in
   * the $nth param. For example:
   *
   * Retrieve the most recent entry "Thing" collection:
   *   retrieve("Thing")
   *
   * Retrieve the 1st entry in the "Thing" collection:
   *   retrieve("1st Thing")
   *   retrieve("Thing", 0)
   *
   * Retrieve the 3rd entry in the "Thing" collection:
   *   retrieve("3rd Thing")
   *   retrieve("Thing", 2)
   *
   * Please note: The nth value in the string key is indexed from 1. In that 1st is the very first item stored.
   * The nth value in the nth parameter is indexed from 0. In that 0 is the first item stored.
   *
   * Please Note: You should not specify both an $nth *and* a plain english nth via the $key. If you
   * do, the method will throw an InvalidArgumentException. e.g:
   *
   * retrieve("1st Thing", 1);
   *
   * @param  string $key The key to retrieve the value for. Can be prefixed with an nth descriptor.
   * @param  int    $nth [optional] The nth (zero indexed) value for the key to retrieve.
   * @throws InvalidArgumentException $nth parameter is provided and $key contains an nth value, but they do not match.
   * @return mixed       The value, or null if no value exists for the specified key/nth combination.
   */
  public function get($key, $nth = null) {
    if (preg_match('/^([1-9][0-9]*)(?:st|nd|rd|th) (.+)$/', $key, $matches)) {
      if ($nth && $nth != $matches[1] - 1) {
        throw new InvalidArgumentException(
            '$nth parameter was provided when $key contains an nth value, and they do not match'
        );
      }

      $nth = $matches[1] - 1;
      $key = $matches[2];
    }

    if (!$this->has($key, $nth)) {
      return null;
    }

    return $nth !== null ? $this->nouns[$key][$nth] : end($this->nouns[$key]);
  }

  /**
   * Retrieves all values for the specified key.
   *
   * @param  string $key The key to retrieve the values for.
   * @throws OutOfBoundsException if the specified $key does not exist in the store.
   * @return array       The values.
   */
  public function getAll($key) {
    if (!isset($this->nouns[$key])) {
      throw new OutOfBoundsException("'$key' does not exist in the store");
    }

    return $this->nouns[$key];
  }

  /**
   * Determines if a value has been stored for the specified key.
   *
   * @param   string  $key  The key to check.
   * @param   int     $nth  The nth (zero indexed) value for the key to check. If not specified, the method will
   *                        ensure that at least one item is stored for the specified key.
   * @return  bool    True if the a value has been stored, false if not.
   */
  public function has($key, $nth = null) {
    return $nth !== null ? isset($this->nouns[$key][$nth]) : isset($this->nouns[$key]);
  }

  /**
   * Stores a value for the specified key.
   *
   * @param  string $key   The key to store the value under.
   * @param  mixed  $value The value to store.
   */
  public function set($key, $value) {
    $this->nouns[$key][] = $value;
  }
}

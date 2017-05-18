<?php namespace nounstore;

use Exception;
use InvalidArgumentException;

class Store {

  /** @var array */
  protected $nouns;

  /**
   * Ensures that a value has been stored for the specified key.
   *
   * @param   string  $key  The key to check.
   * @param   int     $nth  The nth value for the key to check. If not specified, the method will
   *                        ensure that at least one item is stored for the specified key.
   * @throws  Exception     If a value has not been stored for the specified key.
   */
  public function assertHas($key, $nth = null) {
    if (!$this->has($key, $nth)) {
      throw new Exception("Entry $nth for $key was not found in the store.");
    }
  }

  /**
   * Retrieves a value for the specified key.
   *
   * Each key is actually a collection. If you do not specify which item in the collection you want,
   * the method will return the most recent entry. You can specify the entry you want by either
   * using the plain english 1st, 2nd, 3rd etc in the $key param, or by specifying 1, 2, 3 etc in
   * the $nth param. For example:
   *
   * Retrieve the most recent entry "Thing" collection:
   *   retrieve("Thing")
   *
   * Retrieve the 1st entry in the "Thing" collection:
   *   retrieve("Thing", 1)
   *   retrieve("1st Thing")
   *
   * Retrieve the 3rd entry in the "Thing" collection:
   *
   *   retrieve("Thing", 3)
   *   retrieve("3rd Thing")
   *
   * Please note: Both the $nth parameter and the plain english approach should always start at 1,
   * not 0.
   *
   * Please Note: You should not specify both an $nth *and* a plain english nth via the $key. If you
   * do, the method will throw an InvalidArgumentException. e.g:
   *
   * retrieve("1st Thing", 1);
   *
   * @param  string $key The key to retrieve the value for. Can be prefixed with an nth descriptor.
   * @param  int    $nth [optional] The nth value for the key to retrieve.
   * @throws InvalidArgumentException $nth parameter is provided and $key contains an nth value, but they do not match.
   * @return mixed       The value, or null if no value exists for the specified key/nth combination.
   */
  public function get($key, $nth = null) {
    if (preg_match('/^([1-9][0-9]*)(?:st|nd|rd|th) (.+)$/', $key, $matches)) {
      if ($nth && $nth != $matches[1]) {
        throw new InvalidArgumentException(
            '$nth parameter was provided when $key contains an nth value, and they do not match'
        );
      }

      $nth = $matches[1];
      $key = $matches[2];
    }

    if (!$this->has($key, $nth)) {
      return null;
    }

    return $nth ? $this->nouns[$key][$nth - 1] : end($this->nouns[$key]);
  }

  /**
   * Determines if a value has been stored for the specified key.
   *
   * @param   string  $key  The key to check.
   * @param   int     $nth  The nth value for the key to check. If not specified, the method will
   *                        ensure that at least one item is stored for the specified key.
   * @return  bool    True if the a value has been stored, false if not.
   */
  public function has($key, $nth = null) {
    return $nth ? isset($this->nouns[$key][$nth - 1]) : isset($this->nouns[$key]);
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

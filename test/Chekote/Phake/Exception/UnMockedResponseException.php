<?php namespace Chekote\Phake\Exception;

use Exception;

/**
 * An Exception for when a mocked method is called without its response being mocked.
 */
class UnMockedResponseException extends Exception
{

}

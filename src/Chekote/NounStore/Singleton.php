<?php namespace Chekote\NounStore;

/**
 * Ensures that one (and only one) instance of the class exists.
 */
trait Singleton
{
    /** @var self */
    protected static $instance;

    /**
     * Singleton constructor.
     *
     * Exists purely to restrict visibility.
     *
     * @codeCoverageIgnore
     */
    protected function __construct()
    {
        // do nothing
    }

    /**
     * Provides access to one (and only one) instance of this class.
     *
     * Subsequent calls to this method will return the same instance.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

<?php namespace Chekote\Phake\Proxies;

use InvalidArgumentException;
use Phake_Proxies_VisibilityProxy;
use ReflectionProperty;

/**
 * Extends the Phake_Proxies_VisibilityProxy to allow accessing private or protected properties.
 */
class VisibilityProxy extends Phake_Proxies_VisibilityProxy
{
    /** @var object the object being proxied */
    protected $proxied;

    public function __construct($proxied)
    {
        parent::__construct($proxied);

        $this->proxied = $proxied;
    }

    /**
     * Attempts to get the value of the specified property.
     *
     * @param  string                   $property the name of the property to get.
     * @throws InvalidArgumentException if the specified property does not exist on the proxied class.
     * @return mixed                    the value of the property.
     */
    public function __get(string $property)
    {
        return $this->makePropertyAccessible($property)->getValue($this->proxied);
    }

    /**
     * Attempts to set the property name to the specified value.
     *
     * @param  string $property the name of the property to set
     * @param  mixed  $value    the value to set
     * @throws InvalidArgumentException if the specified property does not exist on the proxied class.
     */
    public function __set(string $property, $value): void
    {
        $this->makePropertyAccessible($property)->setValue($this->proxied, $value);
    }

    /**
     * Ensures that the specified property on the proxied class is accessible.
     *
     * @param  string                   $name the property to make accessible.
     * @throws InvalidArgumentException if the specified property does not exist on the proxied class.
     * @return ReflectionProperty       the property.
     */
    protected function makePropertyAccessible(string $name): ReflectionProperty
    {
        if (!property_exists($this->proxied, $name)) {
            throw new InvalidArgumentException(
                "Property $name does not exist on " . get_class($this->proxied) . '.'
            );
        }

        $property = new ReflectionProperty($this->proxied, $name);
        $property->setAccessible(true);

        return $property;
    }
}

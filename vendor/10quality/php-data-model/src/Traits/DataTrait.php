<?php

namespace TenQuality\Data\Traits;

/**
 * Holds all model properties and methods related with data.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.0
 */
trait DataTrait
{
    /**
     * Holds the model data.
     * @since 1.0.0
     *
     * @var array
     */
    protected $attributes = [];
    /**
     * Holds the list of attributes or properties that should be part of the data casted to array or string.
     * Those not listed in this array will remain as hidden.
     * @since 1.0.0
     *
     * @var array
     */
    protected $properties = [];
    /**
     * Getter property.
     * Returns value as reference, reference to aliases based on functions will not work.
     * @since 1.0.0
     */
    public function &__get($property)
    {
        $value = null;
        // Protected properties
        if (property_exists($this, $property))
            return $this->$property;
        // Normal data handled in attributes
        if (isset($this->attributes[$property]))
            return $this->attributes[$property];
        // Aliases
        if (method_exists($this, 'get'.ucfirst($property).'Alias')) {
            $value = call_user_func_array([&$this, 'get'.ucfirst($property).'Alias'], []);
        }
        return $value;
    }
    /**
     * Setter property values.
     * @since 1.0.0
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            // Protected properties
            $this->$property = $value;
        } else if (method_exists($this, 'set'.ucfirst($property).'Alias')) {
            // Aliases
            call_user_func_array([&$this, 'set'.ucfirst($property).'Alias'],[$value]);
        } else {
            // Normal attribute
            $this->attributes[$property] = $value;
        }
    }
}
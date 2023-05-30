<?php

namespace TenQuality\Data;

use ArrayObject;
use TenQuality\Data\Contracts\Arrayable;
use TenQuality\Data\Contracts\Stringable;
use TenQuality\Data\Contracts\JSONable;

/**
 * Collection of data.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.2
 */
class Collection extends ArrayObject implements Arrayable, JSONable, Stringable
{
    /**
     * Returns collection sorted by specific attribute name.
     * Only works with Models or arrays with string defined keys.
     * @since 1.0.2
     * 
     * @link http://php.net/manual/en/array.constants.php
     *
     * @param string $attribute Attribute to sort by.
     * @param string $sortFlag  Sort direction. Use PHP sort constants
     *
     * @return array (this for chaining)
     */
    public function sortBy($attribute, $sortFlag = SORT_REGULAR)
    {
        $values = array();
        for ($i = count( $this ) -1; $i >= 0; --$i) {
            $values[] = $this[$i]->$attribute;
        }
        $values = array_unique($values);
        sort($values, $sortFlag);
        $new = new self();
        foreach ($values as $value) {
            for ($i = count($this) -1; $i >= 0; --$i) {
                if ($value == $this[$i]->$attribute) {
                    $new[] = $this[$i];
                }
            }
        }
        return $new;
    }

    /**
     * Returns collection grouped by an attribute's name.
     * Only works with Models or arrays with string defined keys.
     * @since 1.0.2
     *
     * @param string $attribute Attribute to group by.
     *
     * @return array (this for chaining)
     */
    public function groupby($attribute)
    {
        $new = new self();
        for ($i = 0; $i < count( $this ); ++$i) {
            $key = (string)$this[$i]->$attribute; 
            if (!isset( $new[$key]))
                $new[$key] = new self();
            $new[$key][] = $this[$i];
        }
        return $new;
    }
    /**
     * Returns collection as pure array.
     * Does depth array casting.
     * @since 1.0.2
     *
     * @return array
     */
    public function __toArray()
    {
        $output = [];
        $value = null;
        foreach ($this as $key => $value) {
            $output[$key] = !is_object($value)
                ? $value
                : (method_exists($value, '__toArray')
                    ? $value->__toArray()
                    : (array)$value
                );
        }
        return $output;
    }
    /**
     * Returns collection as pure array.
     * Does depth array casting.
     * @since 1.0.2
     *
     * @return array
     */
    public function toArray()
    {
        return $this->__toArray();
    }
    /**
     * Returns collection as a string.
     * @since 1.0.2
     *
     * @param string
     */
    public function __toString()
    {
        return json_encode($this->__toArray());
    }
    /**
     * Returns object as JSON string.
     * @since 1.0.2
     * 
     * @link http://php.net/manual/en/function.json-encode.php
     * 
     * @param int $options JSON encoding options. See @link.
     * @param int $depth   JSON encoding depth. See @link.
     * 
     * @return string
     */
    public function __toJSON($options = 0, $depth = 512)
    {
        return json_encode($this->__toArray(), $options, $depth);
    }
    /**
     * Returns object as JSON string.
     * @since 1.0.2
     * 
     * @link http://php.net/manual/en/function.json-encode.php
     * 
     * @param int $options JSON encoding options. See @link.
     * @param int $depth   JSON encoding depth. See @link.
     * 
     * @return string
     */
    public function toJSON($options = 0, $depth = 512)
    {
        return $this->__toJSON($options, $depth);
    }
}
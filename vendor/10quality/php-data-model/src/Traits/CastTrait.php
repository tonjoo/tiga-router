<?php

namespace TenQuality\Data\Traits;

/**
 * Holds all model functionality related to casting.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.1
 */
trait CastTrait
{
    /**
     * Returns model as array.
     * @since 1.0.0
     * @since 1.0.1 Extended casting for object|array|Model values.
     *
     * @return array
     */
    public function __toArray()
    {
        $output = [];
        $value = null;
        foreach ($this->properties as $property) {
            if ($this->$property !== null)
                $output[$property] = $this->__getCleaned($this->$property);
        }
        return $output;
    }
    /**
     * Returns model as array.
     * @since 1.0.0
     *
     * @return array
     */
    public function toArray()
    {
        return $this->__toArray();
    }
    /**
     * Returns model as json string.
     * @since 1.0.0
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->__toArray());
    }
    /**
     * Returns cleaned value for casting.
     * @since 1.0.1
     *
     * @param mixed $value Value to clean.
     *
     * @return mixed
     */
    private function __getCleaned($value)
    {
        switch (gettype($value)) {
            case 'object':
                return method_exists($value, '__toArray')
                    ? $value->__toArray()
                    : (method_exists($value, 'toArray')
                        ? $value->toArray()
                        :(array)$value
                    );
            case 'array':
                $output = [];
                foreach ($value as $key => $data) {
                    if ($data !== null)
                        $output[$key] = $this->__getCleaned($data);
                }
                return $output;
        }
        return $value;
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
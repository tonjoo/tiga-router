<?php

namespace TenQuality\Data;

use TenQuality\Data\Contracts\Arrayable;
use TenQuality\Data\Contracts\Stringable;
use TenQuality\Data\Contracts\JSONable;
use TenQuality\Data\Traits\DataTrait;
use TenQuality\Data\Traits\CastTrait;

/**
 * Base abstract "DATA MODEL" class.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.2
 */
abstract class Model implements Arrayable, Stringable, JSONable
{
    use DataTrait, CastTrait;
    /**
     * Model constructor. Enables to init model with the attributes data
     * passed as an array.
     * @since 1.0.2
     * 
     * @param array $attributes Initial attributes data.
     */
    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }
}
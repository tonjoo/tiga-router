<?php

namespace TenQuality\Data\Contracts;

/**
 * Interface for any object that can be casted to JSON.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.2
 */
interface JSONable
{
    /**
     * Returns object as JSON string.
     * @since 1.0.2
     */
    public function __toJSON($options = 0, $depth = 512);
    /**
     * Returns object as JSON string.
     * @since 1.0.2
     */
    public function toJSON($options = 0, $depth = 512);
}
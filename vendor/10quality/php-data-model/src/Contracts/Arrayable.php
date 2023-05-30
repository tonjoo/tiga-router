<?php

namespace TenQuality\Data\Contracts;

/**
 * Interface for any object that can be casted to array.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.0
 */
interface Arrayable
{
    /**
     * Returns object as string.
     * @since 1.0.0
     */
    public function __toArray();
    /**
     * Returns object as string.
     * @since 1.0.0
     */
    public function toArray();
}
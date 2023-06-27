<?php

namespace TenQuality\Data\Contracts;

/**
 * Interface for any object that can be casted to string.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.0
 */
interface Stringable
{
    /**
     * Returns object as string.
     * @since 1.0.0
     */
    public function __toString();
}
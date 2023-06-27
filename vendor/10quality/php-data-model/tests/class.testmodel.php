<?php

use TenQuality\Data\Model;

/**
 * Test model.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.0
 */
class TestModel extends Model
{
    /**
     * Method for alias property `displayPrice`.
     * Return `price` property concatenated with the $ (dollar) symbol
     */
    protected function getDisplayPriceAlias()
    {
        return '$'.$this->price;
    }

    /**
     * Method for alias property `displayPrice`.
     * Sets a the price value if the alias is used.
     */
    protected function setDisplayPriceAlias($value)
    {
        $this->price = floatval(str_replace('$', '', $value));
    }
}
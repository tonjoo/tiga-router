<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests data handling.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.1
 */
class DataTest extends TestCase
{
    /**
     * Tests data handled in attributes.
     * @since 1.0.0
     */
    public function testProperty()
    {
        // Prepare
        $model = new TestModel;
        $model->name = 'Test';
        $model->price = 19.99;
        // Assert
        $this->assertNotNull($model->name);
        $this->assertNotNull($model->price);
        $this->assertIsString($model->name);
        $this->assertIsFloat($model->price);
        $this->assertEquals('Test', $model->name);
        $this->assertEquals(19.99, $model->price);
    }
    /**
     * Tests data handled in aliases.
     * @since 1.0.0
     */
    public function testGetAlias()
    {
        // Prepare
        $model = new TestModel;
        $model->price = 19.99;
        // Assert
        $this->assertIsString($model->displayPrice);
        $this->assertEquals('$19.99', $model->displayPrice);
    }
    /**
     * Tests data handled in aliases.
     * @since 1.0.0
     */
    public function testSetAlias()
    {
        // Prepare
        $model = new TestModel;
        $model->price = 19.99;
        // Execure
        $model->displayPrice = '$29.99';
        // Assert
        $this->assertIsFloat($model->price);
        $this->assertEquals('$29.99', $model->displayPrice);
        $this->assertEquals(29.99, $model->price);
        $this->assertIsString($model->displayPrice);
    }
    /**
     * Empty properties.
     * @since 1.0.0
     */
    public function testNonexistentProperty()
    {
        // Prepare
        $model = new TestModel;
        // Assert
        $this->assertNull($model->name);
    }
    /**
     * Test construct method.
     * @since 1.0.1
     */
    public function testConstructMethod()
    {
        // Prepare
        $model = new TestModel(['price' => 19.99]);
        // Assert
        $this->assertNull($model->name);
        $this->assertIsFloat($model->price);
        $this->assertIsString($model->displayPrice);
        $this->assertEquals(19.99, $model->price);
        $this->assertEquals('$19.99', $model->displayPrice);
    }
}
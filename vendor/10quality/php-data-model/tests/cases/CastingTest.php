<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests casting.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.2
 */
class CastingTest extends TestCase
{
    /**
     * Array casting.
     * @since 1.0.0
     */
    public function testArrayCasting()
    {
        // Prepare
        $model = new TestModel;
        $model->name = 'Test';
        $model->price = 19.99;
        $model->properties = ['price','displayPrice'];
        // Execute
        $array = $model->toArray();
        // Assert
        $this->assertIsArray($array);
        $this->assertArrayHasKey('price', $array);
        $this->assertArrayHasKey('displayPrice', $array);
        $this->assertArrayNotHasKey('name', $array);
        $this->assertEquals(19.99, $array['price']);
        $this->assertEquals('$19.99', $array['displayPrice']);
    }
    /**
     * String casting.
     * @since 1.0.0
     */
    public function testStringCasting()
    {
        // Prepare
        $model = new TestModel;
        $model->name = 'Test';
        $model->price = 19.99;
        $model->properties = ['price','displayPrice'];
        // Execute
        $json = (string)$model;
        // Assert
        $this->assertIsString($json);
        $this->assertEquals('{"price":19.99,"displayPrice":"$19.99"}', $json);
    }
    /**
     * String casting.
     * @since 1.0.1
     */
    public function testWithObjectArrayCasting()
    {
        // Prepare
        $model = new TestModel;
        $model->code = 'Test';
        $obj = new stdClass;
        $obj->id = 7;
        $obj->name = 'Bond';
        $model->agent = $obj;
        $model->properties = ['code','agent'];
        // Execute
        $array = $model->toArray();
        // Assert
        $this->assertIsArray($array);
        $this->assertArrayHasKey('code', $array);
        $this->assertArrayHasKey('agent', $array);
        $this->assertEquals('Test', $array['code']);
        $this->assertIsArray($array['agent']);
        $this->assertArrayHasKey('id', $array['agent']);
        $this->assertArrayHasKey('name', $array['agent']);
        $this->assertEquals(7, $array['agent']['id']);
        $this->assertEquals('Bond', $array['agent']['name']);
    }
    /**
     * String casting.
     * @since 1.0.1
     */
    public function testWithModelArrayCasting()
    {
        // Prepare
        $model = new TestModel;
        $model->code = 'Parent';
        $sub = new TestModel;
        $sub->code = 'Child';
        $sub->price = 19.99;
        $sub->properties = ['code','displayPrice'];
        $model->sub = $sub;
        $model->properties = ['code','sub'];
        // Execute
        $array = $model->toArray();
        // Assert
        $this->assertIsArray($array);
        $this->assertArrayHasKey('code', $array);
        $this->assertArrayHasKey('sub', $array);
        $this->assertEquals('Parent', $array['code']);
        $this->assertIsArray($array['sub']);
        $this->assertArrayHasKey('code', $array['sub']);
        $this->assertArrayHasKey('displayPrice', $array['sub']);
        $this->assertEquals('Child', $array['sub']['code']);
        $this->assertEquals('$19.99', $array['sub']['displayPrice']);
    }
    /**
     * String casting.
     * @since 1.0.1
     */
    public function testWithInnerArrayCasting()
    {
        // Prepare
        $model = new TestModel;
        $model->children = [7];
        $child = new TestModel;
        $child->code = 'Model';
        $child->properties = ['code'];
        $model->children[] = $child;
        $child = new stdClass;
        $child->id = 1;
        $child->code = 'Object';
        $model->children[] = $child;
        $model->children[] = ['a','b','c'];
        $model->properties = ['children'];
        // Execute
        $array = $model->toArray();
        // Assert
        $this->assertIsArray($array);
        $this->assertArrayHasKey('children', $array);
        $this->assertIsArray($array['children']);
        $this->assertEquals(4, count($array['children']));
        $this->assertEquals(7, $array['children'][0]);
        $this->assertIsArray($array['children'][1]);
        $this->assertEquals('Model', $array['children'][1]['code']);
        $this->assertIsArray($array['children'][2]);
        $this->assertEquals(1, $array['children'][2]['id']);
        $this->assertEquals('Object', $array['children'][2]['code']);
        $this->assertIsArray($array['children'][3]);
        $this->assertEquals('a', $array['children'][3][0]);
        $this->assertEquals('b', $array['children'][3][1]);
        $this->assertEquals('c', $array['children'][3][2]);
    }
    /**
     * JSON casting.
     * @since 1.0.2
     */
    public function testJSONCasting()
    {
        // Prepare
        $model = new TestModel;
        $model->name = 'Test';
        $model->price = 19.99;
        $model->properties = ['price','displayPrice'];
        // Execute
        $json = $model->toJSON();
        // Assert
        $this->assertIsString($json);
        $this->assertEquals('{"price":19.99,"displayPrice":"$19.99"}', $json);
    }
}
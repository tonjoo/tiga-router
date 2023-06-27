<?php

use PHPUnit\Framework\TestCase;
use TenQuality\Data\Collection;

/**
 * Tests collections.
 *
 * @author Cami M <info@10quality.com>
 * @copyright 10 Quality <info@10quality.com>
 * @package TenQuality\Data\Model
 * @version 1.0.2
 */
class CollectionTest extends TestCase
{
    /**
     * Tests data handling and array properties.
     * @since 1.0.2
     */
    public function testArrayBehavior()
    {
        // Prepare
        $collection = new Collection;
        $collection[] = 'A';
        $collection[] = 'B';
        $collection[] = 'C';
        // Assert
        $this->assertEquals(3, count($collection));
        $this->assertEquals('C', $collection[2]);
    }
    /**
     * Tests casting.
     * @since 1.0.2
     */
    public function testArrayCasting()
    {
        // Prepare
        $collection = new Collection;
        $model = new TestModel;
        $model->name = 'Test';
        $model->properties = ['name'];
        $collection[] = $model;
        // Execute
        $array = $collection->toArray();
        // Assert
        $this->assertIsArray($array);
        $this->assertEquals(1, count($array));
        $this->assertIsArray($array[0]);
        $this->assertEquals('Test', $array[0]['name']);
    }
    /**
     * Tests casting.
     * @since 1.0.2
     */
    public function testDepthArrayCasting()
    {
        // Prepare
        $collection = new Collection;
        $collection[] = new Collection; // Subcollection
        $model = new TestModel;
        $model->name = 'Test';
        $model->properties = ['name'];
        $collection[0][] = $model;
        // Execute
        $array = $collection->toArray();
        // Assert
        $this->assertIsArray($array);
        $this->assertEquals(1, count($array));
        $this->assertIsArray($array[0]);
        $this->assertEquals(1, count($array[0]));
        $this->assertIsArray($array[0][0]);
        $this->assertEquals('Test', $array[0][0]['name']);
    }
    /**
     * Tests casting.
     * @since 1.0.2
     */
    public function testStringCasting()
    {
        // Prepare
        $collection = new Collection;
        $model = new TestModel;
        $model->name = 'Test';
        $model->properties = ['name'];
        $collection[] = $model;
        // Execute
        $string = (string)$collection;
        // Assert
        $this->assertIsString($string);
        $this->assertEquals('[{"name":"Test"}]', $string);
    }
    /**
     * Tests casting.
     * @since 1.0.2
     */
    public function testJsonCasting()
    {
        // Prepare
        $collection = new Collection;
        $model = new TestModel;
        $model->name = 'Test';
        $model->properties = ['name'];
        $collection[] = $model;
        // Execute
        $json = $collection->toJSON();
        // Assert
        $this->assertIsString($json);
        $this->assertEquals('[{"name":"Test"}]', $json);
    }
    /**
     * Tests casting.
     * @since 1.0.2
     */
    public function testSortByMethod()
    {
        // Prepare
        $collection = new Collection;
        $collection[] = new TestModel(['name' => 'Zelda']);
        $collection[] = new TestModel(['name' => 'Alphonse']);
        $collection[] = new TestModel(['name' => 'Link']);
        $collection[] = new TestModel(['name' => 'Mario']);
        // Execute
        $collection = $collection->sortBy('name');
        // Assert
        $this->assertEquals('Alphonse', $collection[0]->name);
        $this->assertEquals('Link', $collection[1]->name);
        $this->assertEquals('Mario', $collection[2]->name);
        $this->assertEquals('Zelda', $collection[3]->name);
    }
    /**
     * Tests casting.
     * @since 1.0.2
     */
    public function testGroupByMethod()
    {
        // Prepare
        $collection = new Collection;
        $collection[] = new TestModel(['name' => 'Zelda', 'game' => 'Zelda']);
        $collection[] = new TestModel(['name' => 'Peach', 'game' => 'Mario Bros']);
        $collection[] = new TestModel(['name' => 'Link', 'game' => 'Zelda']);
        $collection[] = new TestModel(['name' => 'Mario', 'game' => 'Mario Bros']);
        $collection[] = new TestModel(['name' => 'Ganondorf', 'game' => 'Zelda']);
        // Execute
        $collection = $collection->groupBy('game');
        // Assert
        $this->assertEquals(2, count($collection));
        $this->assertEquals(3, count($collection['Zelda']));
        $this->assertEquals(2, count($collection['Mario Bros']));
        $this->assertEquals('Zelda', $collection['Zelda'][0]->name);
        $this->assertEquals('Link', $collection['Zelda'][1]->name);
        $this->assertEquals('Ganondorf', $collection['Zelda'][2]->name);
        $this->assertEquals('Peach', $collection['Mario Bros'][0]->name);
        $this->assertEquals('Mario', $collection['Mario Bros'][1]->name);
    }
    /**
     * Tests casting.
     * @since 1.0.2
     */
    public function testSortGroupByCombined()
    {
        // Prepare
        $collection = new Collection;
        $collection[] = new TestModel(['name' => 'Zelda', 'game' => 'Zelda']);
        $collection[] = new TestModel(['name' => 'Peach', 'game' => 'Mario Bros']);
        $collection[] = new TestModel(['name' => 'Link', 'game' => 'Zelda']);
        $collection[] = new TestModel(['name' => 'Mario', 'game' => 'Mario Bros']);
        $collection[] = new TestModel(['name' => 'Ganondorf', 'game' => 'Zelda']);
        // Execute
        $collection = $collection->sortBy('name')->groupBy('game');
        // Assert
        $this->assertEquals(2, count($collection));
        $this->assertEquals(3, count($collection['Zelda']));
        $this->assertEquals(2, count($collection['Mario Bros']));
        $this->assertEquals('Ganondorf', $collection['Zelda'][0]->name);
        $this->assertEquals('Link', $collection['Zelda'][1]->name);
        $this->assertEquals('Zelda', $collection['Zelda'][2]->name);
        $this->assertEquals('Mario', $collection['Mario Bros'][0]->name);
        $this->assertEquals('Peach', $collection['Mario Bros'][1]->name);
    }
}
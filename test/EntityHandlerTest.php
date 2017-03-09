<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\Test\GeneratorExample\Entity\ElementList;
use PDO;
use PHPUnit\Framework\TestCase;

class EntityHandlerTest extends TestCase
{
    /**
     *
     * @var string
     */
    protected static $class='De\Idrinth\EntityGenerator\Test\GeneratorExample\Entity\ElementList';
    /**
     *
     * @var EntityHandler
     */
    protected $object;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new EntityHandler(new PDO(
            'mysql:host:localhost',
            'root',
            ''
        ));
    }

    /**
     */
    public function testCanProvideClass()
    {
        $instance = $this->object->loadInstance(self::$class, 'generator-example', 'element_list', 2);
        $this->assertInstanceOf(
            self::$class,
            $instance
        );
        $this->assertEquals(2, $instance->getAid());
    }

    /**
     * @depends testCanProvideClass
     */
    public function testCanStoreClass()
    {
        $this->assertEquals(1,
            $this->object->writeToDB(
                'generator-example',
                'element_list',
                new ElementList(),
                array('name' => 'test')
            )
        );
    }

    /**
     * @depends testCanStoreClass
     */
    public function testCanLoadClass()
    {
        $entity = new ElementList(1);
        $this->object->loadFromDB('generator-example', 'element_list', $entity);
        $this->assertEquals('test', $entity->getName());
    }

    /**
     * @depends testCanStoreClass
     * @depends testCanLoadClass
     * @depends testCanProvideClass
     */
    public function testCanUpdateClass()
    {
        $this->assertEquals(true,
            $this->object->writeToDB('generator-example', 'element_list',
                new ElementList(1),
                array('name' => 'test-static')
            )
        );
    }
}
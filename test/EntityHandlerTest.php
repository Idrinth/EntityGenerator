<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\Test\GeneratorExample\Entity\ElementList;
use PDO;
use De\Idrinth\EntityGenerator\EntityHandler as EntityHandler2;
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
        $database = new PDO(
            'mysql:host:localhost',
            'root',
            ''
        );
        $this->object = new EntityHandler($database);
        EntityHandler2::init($database);
    }

    /**
     * @covers EntityHandler2::loadInstance
     */
    public function testCanProvideClass()
    {
        $instance = $this->object->loadInstance(self::$class, 'generator-example', 'element_list', 1);
        $this->assertInstanceOf(self::$class, $instance);
        $this->assertEquals(1, $instance->getAid());
    }

    /**
     * @depends testCanProvideClass
     */
    public function testCanStaticProvideClass()
    {
        $instance = EntityHandler2::provide(self::$class, 2);
        $this->assertInstanceOf(self::$class, $instance);
        $this->assertEquals(2, $instance->getAid());
    }

    /**
     * @depends testCanProvideClass
     * @covers EntityHandler2::writeToDB
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
     * @depends testCanStaticProvideClass
     */
    public function testCanStaticStoreClass()
    {
        $entity = new ElementList();
        $entity->setName('test2');
        $this->assertEquals(2, EntityHandler2::store($entity));
    }

    /**
     * @depends testCanStoreClass
     * @covers EntityHandler2::writeToDB
     */
    public function testCanNotStoreWrongClass()
    {
        $this->assertFalse(
            $this->object->writeToDB(
                'generator-example',
                'missing_element_list',
                new ElementList(),
                array('name' => 'test')
            )
        );
    }

    /**
     * @depends testCanStoreClass
     * @covers EntityHandler2::loadFromDB
     */
    public function testCanLoadClass()
    {
        $entity = new ElementList(1);
        $this->object->loadFromDB('generator-example', 'element_list', $entity);
        $this->assertEquals('test', $entity->getName());
    }

    /**
     * @depends testCanLoadClass
     * @covers EntityHandler2::loadFromDB
     */
    public function testCanNotLoadMissingId()
    {
        $entity = new ElementList(17);
        $this->object->loadFromDB('generator-example', 'element_list', $entity);
        $this->assertFalse($entity->entityInitialized);
    }

    /**
     * @depends testCanLoadClass
     * @depends testCanStaticStoreClass
     */
    public function testCanStaticLoadClass()
    {
        $entity = new ElementList(2);
        EntityHandler2::load($entity);
        $this->assertEquals('test2', $entity->getName());
    }

    /**
     * @depends testCanStoreClass
     * @depends testCanLoadClass
     * @depends testCanProvideClass
     * @covers EntityHandler2::writeToDB
     */
    public function testCanUpdateClass()
    {
        $this->assertTrue(
            $this->object->writeToDB('generator-example', 'element_list',
                new ElementList(1),
                array('name' => 'test-static')
            )
        );
    }

    /**
     * @depends testCanUpdateClass
     * @depends testCanStaticStoreClass
     * @depends testCanStaticProvideClass
     * @depends testCanStaticLoadClass
     */
    public function testCanStaticUpdateClass()
    {
        $entity = new ElementList(2);
        $entity->setName('test2-static');
        $this->assertTrue(EntityHandler2::store($entity));
    }
}
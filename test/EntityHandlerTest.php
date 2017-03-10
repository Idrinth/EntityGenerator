<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityHandler;
use De\Idrinth\EntityGenerator\Test\GeneratorExample\Entity\ElementList;
use PDO;

class EntityHandlerTest extends AbstractTestCase
{
    /**
     *
     * @var string
     */
    protected static $class='De\Idrinth\EntityGenerator\Test\Test\Entity\Element';

    /**
     *
     * @var int
     */
    protected $aid=1;

    /**
     * @return EntityHandler
     */
    protected function getHandler()
    {
        return new EntityHandler($this->database);
    }

    /**
     */
    public function testCanProvideClass()
    {
        $instance = $this->getHandler()->provide(self::$class, 1);
        $this->assertInstanceOf(self::$class, $instance);
        $this->assertEquals(1, $instance->getAid());
    }

    /**
     * @depends testCanProvideClass
     */
    public function testCanStoreClass()
    {
        $entity = new ElementList();
        $entity->setName('test');
        $this->aid = $this->getHandler()->store($entity);
        $this->assertGreaterThan(0, $this->aid);
    }

    /**
     * @depends testCanStoreClass
     */
    public function testCanLoadClass()
    {
        $entity = new ElementList($this->aid);
        $this->getHandler()->load($entity);
        $this->assertEquals('test', $entity->getName());
    }

    /**
     * @depends testCanLoadClass
     */
    public function testCanNotLoadMissingId()
    {
        $entity = new ElementList($this->aid+17);
        $this->getHandler()->load($entity);
        $this->assertFalse($entity->isEntityInitialized());
    }

    /**
     * @depends testCanStoreClass
     * @depends testCanLoadClass
     * @depends testCanProvideClass
     */
    public function testCanUpdateClass()
    {
        $entity = new ElementList($this->aid);
        $entity->setName('test1');
        $this->assertTrue($this->getHandler()->store($entity));
    }

    /**
     * @depends testCanStoreClass
     */
    public function testCanChangeClass()
    {
        $entity = new ElementList($this->aid);
        $this->getHandler()->load($entity);
        $this->assertEquals('test1', $entity->getName());
    }
}
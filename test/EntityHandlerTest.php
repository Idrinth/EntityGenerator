<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityHandler;
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
     * @return EntityHandler
     */
    protected function getHandler()
    {
        return new EntityHandler(new PDO(
            'mysql:host:localhost',
            'root',
            ''
        ));
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
        $this->assertEquals(1, $this->getHandler()->store($entity));
    }

    /**
     * @depends testCanStoreClass
     */
    public function testCanLoadClass()
    {
        $entity = new ElementList(1);
        $this->getHandler()->load($entity);
        $this->assertEquals('test', $entity->getName());
    }

    /**
     * @depends testCanLoadClass
     */
    public function testCanNotLoadMissingId()
    {
        $entity = new ElementList(17);
        $this->getHandler()->load($entity);
        $this->assertFalse($entity->getEntityInitialized());
    }

    /**
     * @depends testCanStoreClass
     * @depends testCanLoadClass
     * @depends testCanProvideClass
     */
    public function testCanUpdateClass()
    {
        $entity = new ElementList(1);
        $entity->setName('test1');
        $this->assertTrue($this->getHandler()->store($entity));
    }

    /**
     * @depends testCanStoreClass
     */
    public function testCanChangeClass()
    {
        $entity = new ElementList(1);
        $this->getHandler()->load($entity);
        $this->assertEquals('test1', $entity->getName());
    }
}
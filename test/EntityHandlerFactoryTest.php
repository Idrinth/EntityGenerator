<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityHandlerFactory;

class EntityHandlerFactoryTest extends AbstractTestCase
{
    /*+
     * @var string
     */
    protected static $class = 'De\Idrinth\EntityGenerator\EntityHandler';

    /**
     */
    public function testReset()
    {
        $this->assertTrue(EntityHandlerFactory::reset());
    }

    /**
     * @depends testReset
     */
    public function testHasToBeInitialised()
    {
        EntityHandlerFactory::reset();
        $this->assertNull(EntityHandlerFactory::get());
    }

    /**
     * @depends testHasToBeInitialised
     */
    public function testCanInitialize()
    {
        $this->assertInstanceOf(self::$class, EntityHandlerFactory::init($this->database));
    }

    /**
     * @depends testCanInitialize
     */
    public function testCanProvideClass()
    {
        $this->assertInstanceOf(self::$class, EntityHandlerFactory::get());
    }
}
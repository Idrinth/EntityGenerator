<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityHandlerFactory;
use PDO;
use PHPUnit\Framework\TestCase;

class EntityHandlerFactoryTest extends TestCase
{
    /*+
     * @var string
     */
    protected static $class = 'De\Idrinth\EntityGenerator\EntityHandler';

    /**
     */
    public function testHasToBeInitialised()
    {
        $this->assertNull(EntityHandlerFactory::get());
    }

    /**
     * @depends testHasToBeInitialised
     */
    public function testCanInitialize()
    {
        $this->assertInstanceOf(
            self::$class,
            EntityHandlerFactory::init(
                new PDO(
                    'mysql:host:localhost',
                    'root',
                    ''
                )
            )
        );
    }

    /**
     * @depends testCanInitialize
     */
    public function testCanProvideClass()
    {
        $this->assertInstanceOf(self::$class, EntityHandlerFactory::get());
    }
}
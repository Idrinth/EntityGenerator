<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\Test\Test\Entity\Element;

class BaseEntityTest extends AbstractTestCase
{
    /**
     */
    public function testCanConstructClass()
    {
        $element = new Element(11);
        $this->assertInstanceOf('De\Idrinth\EntityGenerator\Test\Test\Entity\Element', $element);
        $this->assertEquals(11, $element->getAid());
    }

    /**
     * @depends testCanConstructClass
     */
    public function testCanStoreClass()
    {
        $element = new Element();
        $element->setName('base-entity-test');
        $element->store();
        $this->assertNotFalse($element->getAid());
        $this->assertGreaterThan(0, $element->getAid());
    }

    /**
     * @depends testCanStoreClass
     */
    public function testCanFillClass()
    {
        $element = new Element(1);
        $this->assertEquals('base-entity-test', $element->getName());
    }
}
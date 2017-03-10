<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\DocBlockHelper;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DocBlockHelperTest extends TestCase
{
    /*+
     * @var DocBlockHelper
     */
    protected $helper;

    /*+
     * @var ReflectionClass
     */
    protected $reflection;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->helper = new DocBlockHelper();
        $this->reflection = new ReflectionClass('De\Idrinth\EntityGenerator\Test\Test\Entity\Element');
    }

    /**
     */
    public function testCanGetDatabase()
    {
        $this->assertEquals('test', $this->helper->getDatabase($this->reflection));
    }

    /**
     */
    public function testCanGetTable()
    {
        $this->assertEquals('element', $this->helper->getTable($this->reflection));
    }

    /**
     */
    public function testCanGetColumn()
    {
        $this->assertEquals('aid', $this->helper->getColumn($this->reflection->getProperty('aid')));
    }

    /**
     */
    public function testIsAutoincrement()
    {
        $this->assertTrue($this->helper->isAutoincrement($this->reflection->getProperty('aid')));
    }
}
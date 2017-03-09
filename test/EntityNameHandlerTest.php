<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityNameHandler;
use PHPUnit\Framework\TestCase;

class EntityNameHandlerTest extends TestCase
{
    /**
     *
     * @var EntityNameHandler
     */
    protected $object;

    /**
     * Provide EntityNameHandler
     */
    public function setUp()
    {
        parent::setUp();
        $this->object = new EntityNameHandler();
    }

    /**
     */
    public function testCanHandleUnderscore()
    {
        $this->compare('an-underscore-here', 'AnUnderscoreHere', 'anUnderscoreHere');
    }

    /**
     */
    public function testCanHandleMinus()
    {
        $this->compare('a-minus-here', 'AMinusHere', 'aMinusHere');
    }

    /**
     */
    public function testCanHandleSlash()
    {
        $this->compare('a/slash/here', 'ASlashHere', 'aSlashHere');
    }

    /**
     */
    public function testCanHandleBackSlash()
    {
        $this->compare('a\\backslash\\here', 'ABackslashHere', 'aBackslashHere');
    }

    /**
     */
    public function testCanHandleDot()
    {
        $this->compare('a.dot.here', 'ADotHere', 'aDotHere');
    }

    /**
     */
    protected function compare($input, $upper, $lower)
    {
        $this->assertEquals($upper, $this->object->toUpperCamelCase($input));
        $this->assertEquals($lower, $this->object->toLowerCamelCase($input));
    }
}
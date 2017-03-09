<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityNameHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_AssertionFailedError;

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
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testCanHandleUnderscore()
    {
        $this->compare('an-underscore-here', 'AnUnderscoreHere', 'anUnderscoreHere');
    }

    /**
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testCanHandleMinus()
    {
        $this->compare('a-minus-here', 'AMinusHere', 'aMinusHere');
    }

    /**
     * 
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testCanHandleSlash()
    {
        $this->compare('a/slash/here', 'ASlashHere', 'aSlashHere');
    }

    /**
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testCanHandleBackSlash()
    {
        $this->compare('a\\backslash\\here', 'ABackslashHere', 'aBackslashHere');
    }

    /**
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testCanHandleDot()
    {
        $this->compare('a.dot.here', 'ADotHere', 'aDotHere');
    }

    /**
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    protected function compare($input, $upper, $lower)
    {
        $this->assertEquals($upper, $this->object->toUpperCamelCase($input));
        $this->assertEquals($lower, $this->object->toLowerCamelCase($input));
    }
}
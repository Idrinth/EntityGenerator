<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityTwig;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_AssertionFailedError;

class EntityTwigTest extends TestCase {
    /**
     *
     * @var EntityTwig
     */
    protected $object;

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
        if (!$this->object)
        {
            $this->object = new EntityTwig();
        }
        $this->assertEquals($upper, $this->object->toUpperCamelCase($input));
        $this->assertEquals($lower, $this->object->toLowerCamelCase($input));
    }

}

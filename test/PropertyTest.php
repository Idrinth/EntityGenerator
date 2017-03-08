<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityGenerator;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_AssertionFailedError;
use SebastianBergmann\RecursionContext\Exception;

class PropertyTest extends TestCase {

    /**
     *
     * @var string[]
     */
    protected static $types = array(
        'tinyint' => 'int',
        'smallint' => 'int',
        'mediumint' => 'int',
        'int' => 'int',
        'bigint' => 'int',
        'bit' => 'int',
        'year' => 'int',
        'float' => 'float',
        'double' => 'float',
        'decimal' => 'float',
        'char' => 'string',
        'varchar' => 'string',
        'text' => 'string',
        'tinytext' => 'string',
        'mediumtext' => 'string',
        'longtext' => 'string',
        'json' => 'string',
        'binary' => 'string',
        'varbinary' => 'string',
        'tinyblob' => 'string',
        'blob' => 'string',
        'mediumblob' => 'string',
        'longblob' => 'string',
        'date' => 'string',
        'time' => 'string',
        'datetime' => 'string',
        'timestamp' => 'string'
    );
    /**
     * 
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testCanHandleBasicTypes()
    {
        foreach(self::$types as $from => $to) {
            $this->handleBasicType($from, $to);
        }
    }
    /**
     * @param string $type
     * @param string $expected
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function handleBasicType($type,$expected)
    {
        $property = new Property('test', $type, null, false);
        $this->assertEquals($expected, $property->getType());
        $this->assertEquals('', $property->getTarget());
    }
    /**
     * @param string $type
     * @param string $expected
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function handleFKType($type,$expected)
    {
        $property = new Property('test', $type, $expected, false);
        $this->assertEquals($expected, $property->getType());
        $this->assertEquals($expected, $property->getTarget());
    }
    /**
     * 
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testCanHandleForeignKeys()
    {
        foreach(array_keys(self::$types) as $from) {
            $this->handleFKType($from, 'my-key');
        }
    }

}
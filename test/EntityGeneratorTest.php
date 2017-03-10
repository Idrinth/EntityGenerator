<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityGenerator as EntityGeneratorImplementation;
use PDO;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\Exception;

class EntityGeneratorTest extends TestCase
{
    /**
     *
     * @var EntityGenerator
     */
    protected $object;
    /**
     *
     * @var string
     */
    protected $path;

    /**
     * provide EntityGenerator
     */
    public function setUp()
    {
        parent::setUp();
        $this->object = new EntityGenerator(
            new PDO('mysql:host:localhost', 'root', ''),
            __DIR__.DIRECTORY_SEPARATOR.'{{schema}}',
            'De\Idrinth\EntityGenerator\Test'
        );
        $this->path = __DIR__.DIRECTORY_SEPARATOR.'GeneratorExample'.DIRECTORY_SEPARATOR.'Entity';
    }

    /**
     *
     */
    public function testCanFindTables()
    {
        $this->assertCount(2, $this->object->getTablesResult('generator-example'));
    }

    /**
     * @depends testCanFindTables
     */
    public function testCanFindProperties()
    {
        $this->assertGreaterThan(
            0,
            count(
                $this->object->getTableProperties('element', 'generator-example')
            )
        );
        $this->assertGreaterThan(
            0,
            count(
                $this->object->getTableProperties('element_list', 'generator-example')
            )
        );
    }

    /**
     * @depends testCanFindTables
     * @covers EntityGenerator<extended>::createDirectoryIfNotExists
     */
    public function testCanCreateFolder()
    {
        $this->assertTrue($this->object->createDirectoryIfNotExists($this->path));
    }

    /**
     * @depends testCanFindTables
     * @covers EntityGenerator<extended>::write
     */
    public function testWriteClass()
    {
        $this->assertTrue(
            $this->object->write(
                $this->path.DIRECTORY_SEPARATOR.'Element.php',
                array(
                    'table' => 'element',
                    'schema' => 'entity-generator',
                    'namespace' => 'De\Idrinth\EntityGenerator\Test\GeneratorExample\Entity',
                    'properties' => $this->object->getTableProperties('element', 'generator-example')
                )
            )
        );
    }

    /**
     * @depends testWriteClass
     * @covers EntityGenerator<extended>::buildClass
     */
    public function testCanBuildClass()
    {
        $this->assertTrue($this->object->buildClass('element', 'generator-example'));
    }

    /**
     * @covers EntityGenerator<extended>::run
     * @large
     */
    public function testCanGenerateDefaultTableClasses()
    {
        $object = new EntityGeneratorImplementation(
                new PDO('mysql:host:localhost', 'root', ''),
                __DIR__.DIRECTORY_SEPARATOR.'{{schema}}',
                'De\Idrinth\EntityGenerator\Test'
            );
        $this->assertTrue($object->run(array('generator-example')));
    }

    /**
     * @depends testCanGenerateDefaultTableClasses
     * @large
     */
    public function testDoFilesExist()
    {
        $base = $this->path.DIRECTORY_SEPARATOR;
        foreach (array('Element', 'ElementList') as $class) {
            $this->assertTrue(is_file($base.$class.'.php'));
        }
    }

    /**
     * @depends testDoFilesExist
     * @large
     */
    public function testDoClassesExist()
    {
        foreach (array('Element', 'ElementList') as $class) {
            $this->assertTrue(class_exists('De\Idrinth\EntityGenerator\Test\GeneratorExample\Entity\\'.$class));
        }
    }
}
<?php

namespace De\Idrinth\EntityGenerator\Test;

use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_AssertionFailedError;
use SebastianBergmann\RecursionContext\Exception;

class EntityGeneratorTest extends TestCase {
    /**
     *
     * @var EntityGenerator
     */
    protected $object;

    /**
     * 
     */
    public function setUp()
    {
        parent::setUp();
        $this->object=new EntityGenerator(
                    new PDO('mysql:host:localhost', 'root', ''),
                    __DIR__ . DIRECTORY_SEPARATOR . '{{schema}}',
                    'De\Idrinth\EntityGenerator\Test'
                );
    }

    /**
     * 
     */
    public function testCanFindTables() {
        $list = $this->object->getTablesResult('generator-example');
        $this->assertEquals(2, count($list));
    }

    /**
     * @depends testCanFindTables
     */
    public function testCanFindProperties() {
        $this->assertGreaterThan(
                0,
                count(
                    $this->object->getTableProperties('element','generator-example')
                )
        );
        $this->assertGreaterThan(
                0,
                count(
                    $this->object->getTableProperties('element_list','generator-example')
                )
        );
    }

    /**
     * @depends testCanFindProperties
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testCanGenerateDefaultTableClasses()
    {
        try {
            $this->object->run(array('generator-example'));
        } catch (Exception $ex) {
            throw new PHPUnit_Framework_AssertionFailedError(null, 1, $ex);
        }
    }

    /**
     * @depends testCanGenerateDefaultTableClasses
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testDoFilesExist()
    {
        foreach (array('Element', 'ElementList') as $class)
        {
            if (!is_file(__DIR__.DIRECTORY_SEPARATOR.'GeneratorExample'.DIRECTORY_SEPARATOR.'Entity' .DIRECTORY_SEPARATOR. $class.'.php'))
            {
                throw new PHPUnit_Framework_AssertionFailedError(
                        $class . ' has no file.',
                        3
                );
            }
        }
    }

    /**
     * @depends testDoFilesExist
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testDoClassesExist()
    {
        foreach (array('Element', 'ElementList') as $class)
        {
            if (!class_exists('De\Idrinth\EntityGenerator\Test\GeneratorExample\Entity\\' . $class))
            {
                throw new PHPUnit_Framework_AssertionFailedError(
                        $class . ' couldn\'t be autoloaded.',
                        2
                );
            }
        }
    }
}

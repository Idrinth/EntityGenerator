<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityGenerator;
use PDO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_AssertionFailedError;
use SebastianBergmann\RecursionContext\Exception;

class EntityGeneratorTest extends TestCase {

    /**
     * 
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testCanGenerateDefaultTableClasses()
    {
        try {
            $gen = new EntityGenerator(
                    new PDO('mysql:host:localhost', 'root', ''),
                    __DIR__ . DIRECTORY_SEPARATOR . '{{schema}}',
                    'De\Idrinth\EntityGenerator\Test'
            );
            $gen->run(array('generator-example'));
        } catch (Exception $ex) {
            throw new PHPUnit_Framework_AssertionFailedError(null, 1, $ex);
        }
    }

    /**
     * @depends testCanGenerateDefaultTableClasses
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

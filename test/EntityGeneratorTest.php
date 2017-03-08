<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityGenerator;
use PDO;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\Exception;

class EntityGeneratorTest extends TestCase {

    /**
     * 
     * @return boolean
     */
    public static function testCanGenerateDefaultTableClasses()
    {
        try {
            $gen = new EntityGenerator(
                    new PDO(
                            'mysql:host:localhost',
                            'root',
                            ''
                        ),
                        __DIR__ . DIRECTORY_SEPARATOR . '{{schema}}Result',
                        'TestResult'
                    );
            $gen->run(array('generator-example'));
        } catch (Exception $ex) {
            throw new \PHPUnit_Framework_AssertionFailedError(null,1,$ex);
        }
    }

}

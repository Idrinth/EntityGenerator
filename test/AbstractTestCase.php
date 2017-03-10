<?php

namespace De\Idrinth\EntityGenerator\Test;

use De\Idrinth\EntityGenerator\EntityHandlerFactory;
use PDO;
use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    /**
     *
     * @var PDO
     */
    protected $database;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->database = new PDO('mysql:host:localhost', 'root', '');
        EntityHandlerFactory::init($this->database);
    }

    /**
     *
     */
    protected function tearDown()
    {
        parent::tearDown();
        EntityHandlerFactory::reset();
    }
}
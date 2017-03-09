<?php

use De\Idrinth\EntityGenerator\Test\EntityGenerator;
require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
$object = new EntityGenerator(
            new PDO('mysql:host:localhost', 'root', ''),
            __DIR__.DIRECTORY_SEPARATOR.'{{schema}}',
            'De\Idrinth\EntityGenerator\Test'
        );
$object->run(array('generator-example'));
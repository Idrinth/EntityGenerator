<?php

namespace De\Idrinth\EntityGenerator\Test;
use De\Idrinth\EntityGenerator\BaseEntity as BaseEntityImplementation;

use De\Idrinth\EntityGenerator\EntityHandler as EntityHandlerImplementation;
use PDO;

class EntityHandler extends EntityHandlerImplementation
{

    /**
     *
     * @param PDO $database
     */
    public function __construct(PDO $database)
    {
        parent::__construct($database);
        self::$instance = $this;
    }

    /**
     *
     * @param string $scheme
     * @param string $table
     * @param BaseEntityImplementation $object
     */
    public function loadFromDB($scheme, $table, BaseEntityImplementation $object)
    {
        $this->cache = array();
        parent::loadFromDB($scheme, $table, $object);
        $this->cache = array();
    }
}
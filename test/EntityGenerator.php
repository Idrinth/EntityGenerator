<?php

namespace De\Idrinth\EntityGenerator\Test;

use PDO;
use De\Idrinth\EntityGenerator\EntityGenerator as EntityGeneratorImplementation;

class EntityGenerator extends EntityGeneratorImplementation
{

    /**
     *
     * @param string $schema
     * @return string[]
     */
    public function getTablesResult($schema)
    {
        $this->getTables->execute(array(':schema' => $schema));
        return $this->getTables->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *
     * @param string $table
     * @param string $schema
     * @return Property[]
     */
    public function getTableProperties($table, $schema)
    {
        return parent::getTableProperties($table, $schema);
    }

    /**
     *
     * @param string $path
     * @return boolean
     */
    public function createDirectoryIfNotExists($path)
    {
        return parent::createDirectoryIfNotExists($path);
    }
}
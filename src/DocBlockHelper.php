<?php
namespace De\Idrinth\EntityGenerator;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class DocBlockHelper
{
    /**
     *
     * @param ReflectionClass $reflection
     * @param string $identifier
     * @return string
     */
    public function getDatabase(ReflectionClass $reflection)
    {
        return $this->getDocValue($reflection, 'database');
    }

    /**
     *
     * @param ReflectionClass $reflection
     * @param string $identifier
     * @return string
     */
    public function getTable(ReflectionClass $reflection)
    {
        return $this->getDocValue($reflection, 'table');
    }

    /**
     *
     * @param ReflectionProperty $reflection
     * @param string $identifier
     * @return string
     */
    public function getColumn(ReflectionProperty $reflection)
    {
        return $this->getDocValue($reflection, 'column');
    }

    /**
     *
     * @param ReflectionProperty $reflection
     * @param string $identifier
     * @return boolean
     */
    public function isAutoincrement(ReflectionProperty $reflection)
    {
        return (bool) preg_match("/@autoincrement\s/",$reflection->getDocComment());
    }

    /**
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param string $identifier
     * @return string
     */
    protected function getDocValue($reflection, $identifier)
    {
        preg_match(
            "/@$identifier (.*?)\s/",
            $reflection->getDocComment(),
            $value
        );
        return $value[1];
    }
}
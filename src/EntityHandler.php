<?php

namespace De\Idrinth\EntityGenerator;

use PDO;
use ReflectionClass;
use ReflectionProperty;

class EntityHandler
{
    /**
     *
     * @var PDO
     */
    protected $database;

    /**
     *
     * @var DocBlockHelper
     */
    protected $handler;

    /**
     *
     * @var EntityNameHandler
     */
    protected $names;

    /**
     *
     * @var BaseEntity[]
     */
    protected $cache = array();

    /**
     *
     * @param PDO $database
     */
    public function __construct(PDO $database, DocBlockHelper $handler=null,EntityNameHandler $names=null)
    {
        $this->database = $database;
        $this->handler = $handler?$handler:new DocBlockHelper();
        $this->names = $names?$names:new EntityNameHandler();
    }

    /**
     *
     * @param string $schema
     * @param string $table
     * @param int $aid
     * @param string[] $data
     * @return boolean
     */
    protected function updateEntity($schema, $table, $aid, $data)
    {
        $string  = array();
        $replace = array();
        foreach ($data as $key => $value) {
            $string[] = "`$key`=:$key";
            $replace[':'.$key] = $value;
        }
        $prep = $this->database->prepare(
            "UPDATE `$schema`.`$table` "
            . "SET ".implode(',', $string)." "
            . "WHERE aid={$aid}"
        );
        return $prep->execute($replace);
    }

    /**
     *
     * @param string $schema
     * @param string $table
     * @param BaseEntity $entity
     * @param string[] $data
     * @return int|boolean
     */
    protected function createEntity($schema, $table, BaseEntity $entity, $data)
    {
        $replace = array();
        foreach ($data as $key => $value) {
            $replace[':'.$key] = $value;
        }
        $prep = $this->database->prepare(
            "INSERT INTO `$schema`.`$table` "
            . "(`".implode("`,`", array_keys($data))."`) "
            . "VALUES (".implode(",", array_keys($replace)).")"
        );
        if ($prep->execute($replace)) {
            $aid = $this->database->lastInsertId();
            $this->cache[$schema][$table][$aid] = $entity;
            return $aid;
        }
        return false;
    }

    /**
     * This loads data into the given entity
     * @param BaseEntity $entity
     */
    public function load(BaseEntity $entity)
    {
        $reflection = new ReflectionClass($entity);
        $schema = $this->handler->getDatabase($reflection);
        $table = $this->handler->getTable($reflection);
        $result = $this->database->query("SELECT * FROM `$schema`.`$table` WHERE aid={$entity->getAid()}");
        if (!$result || $result->rowCount() !== 1) {
            return;
        }
        foreach ($result->fetch(PDO::FETCH_ASSOC) as $column => $value) {
            $setter = 'set'.$this->names->toUpperCamelCase($column);
            if (method_exists($entity, $setter)) {
                call_user_func(array($entity, $setter), $value);
            }
        }
        $this->cache[$schema][$table][$entity->getAid()] = $entity;
    }

    /**
     * This loads data into the given entity
     * @param string $class
     * @param int $aid
     * @return BaseEntity $entity
     */
    public function provide($class, $aid)
    {
        $reflection = new ReflectionClass($class);
        $schema = $this->handler->getDatabase($reflection);
        $table = $this->handler->getTable($reflection);
        if (!isset($this->cache[$schema][$table][$aid])) {
            $this->cache[$schema][$table][$aid] = new $class($aid);
        }
        return $this->cache[$schema][$table][$aid];
    }

    /**
     * This stores a given entity in the database
     * @param BaseEntity $entity
     * @return boolean|int
     */
    public function store(BaseEntity $entity)
    {
        $reflection = new ReflectionClass($entity);
        if ($entity->getAid()) {
            return $this->updateEntity(
                $this->handler->getDatabase($reflection),
                $this->handler->getTable($reflection),
                $entity->getAid(),
                $this->getPropertiesToUpdate($reflection, $entity)
            );
        }
        return $this->createEntity(
            $this->handler->getDatabase($reflection),
            $this->handler->getTable($reflection),
            $entity,
            $this->getPropertiesToUpdate($reflection, $entity)
        );
    }

    /**
     *
     * @param \De\Idrinth\EntityGenerator\ReflectionClass $reflection
     * @param BaseEntity $entity
     * @return array
     */
    protected function getPropertiesToUpdate(ReflectionClass $reflection, BaseEntity $entity) {
        $data = array();
        foreach ($reflection->getProperties() as $property) {
            /* @var $property ReflectionProperty */
            $methodName = 'get'.strtoupper($property->name{0}).substr($property->name, 1);
            if (
                $this->shouldPropertyBeUpdated($property) &&
                $this->doesGetterExist($reflection, $methodName)
            ) {
                $data[$this->handler->getColumn($property)] = $reflection->getMethod($methodName)->invoke($entity);
            }
        }
        return $data;
    }

    /**
     *
     * @param ReflectionProperty $property
     * @param string $methodName
     * @return boolean
     */
    protected function shouldPropertyBeUpdated(ReflectionProperty $property) {
        return !(
            $property->getName() === 'aid' ||
            $property->getName() === 'entityInitialized' ||
            $property->isStatic()
        );
    }

    /**
     *
     * @param ReflectionClass $reflection
     * @param string $methodName
     * @return boolean
     */
    protected function doesGetterExist(ReflectionClass $reflection, $methodName) {
        return $reflection->hasMethod($methodName) &&
                $reflection->getMethod($methodName)->isPublic() &&
                !$reflection->getMethod($methodName)->isStatic();
    }
}
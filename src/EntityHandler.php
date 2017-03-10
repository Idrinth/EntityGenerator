<?php

namespace De\Idrinth\EntityGenerator;

use PDO;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class EntityHandler
{
    /**
     *
     * @var EntityHandler
     */
    protected static $instance;

    /**
     *
     * @var PDO
     */
    protected $database;

    /**
     *
     * @var BaseEntity[]
     */
    protected $cache = array();

    /**
     *
     * @param PDO $database
     * @return EntityHandler
     */
    public static function init(PDO $database)
    {
        if (!self::$instance) {
            // @codeCoverageIgnoreStart
            self::$instance = new self($database);
            // @codeCoverageIgnoreEnd
        }
        return self::$instance;
    }

    /**
     *
     * @param PDO $database
     */
    protected function __construct(PDO $database)
    {
        $this->database = $database;
    }

    /**
     *
     * @param string $scheme
     * @param string $table
     * @param BaseEntity $object
     */
    public function loadFromDB($scheme, $table, BaseEntity $object)
    {
        $result = $this->database->query("SELECT * FROM `$scheme`.`$table` WHERE aid={$object->getAid()}");
        if (!$result || $result->rowCount() !== 1) {
            return;
        }
        $nameHandler = new EntityNameHandler();
        $object->entityInitialized = true;
        foreach ($result->fetch(PDO::FETCH_ASSOC) as $column => $value) {
            $setter = 'set'.$nameHandler->toUpperCamelCase($column);
            if (method_exists($object, $setter)) {
                call_user_func(array($object, $setter), $value);
            }
        }
        $this->cache[$scheme][$table][$object->getAid()] = $object;
    }

    /**
     *
     * @param string $scheme
     * @param string $table
     * @param BaseEntity $object
     * @param string[] $data
     * @return boolean
     */
    protected function updateEntity($scheme, $table, BaseEntity $object, $data)
    {
        $string  = array();
        $replace = array();
        foreach ($data as $key => $value) {
            $string[] = "`$key`=:$key";
            $replace[':'.$key] = $value;
        }
        $prep = $this->database->prepare(
            "UPDATE `$scheme`.`$table` "
            . "SET ".implode(',', $string)." "
            . "WHERE aid={$object->getAid()}"
        );
        return $prep->execute($replace);
    }

    /**
     *
     * @param string $scheme
     * @param string $table
     * @param BaseEntity $object
     * @param string[] $data
     * @return int|boolean
     */
    protected function createEntity($scheme, $table, BaseEntity $object, $data)
    {
        $replace = array();
        foreach ($data as $key => $value) {
            $replace[':'.$key] = $value;
        }
        $prep = $this->database->prepare(
            "INSERT INTO `$scheme`.`$table` "
            . "(`".implode("`,`", array_keys($data))."`) "
            . "VALUES (".implode(",", array_keys($replace)).")"
        );
        if ($prep->execute($replace)) {
            $aid = $this->database->lastInsertId();
            $this->cache[$scheme][$table][$aid] = $object;
            return $aid;
        }
        return false;
    }

    /**
     *
     * @param string $scheme
     * @param string $table
     * @param BaseEntity $object
     * @param string[] $data
     * @return int|boolean
     */
    public function writeToDB($scheme, $table, BaseEntity $object, $data)
    {
        if ($object->getAid()) {
            return $this->updateEntity($scheme, $table, $object, $data);
        }
        return $this->createEntity($scheme, $table, $object, $data);
    }

    /**
     * This loads data into the given entity
     * @param BaseEntity $entity
     */
    public static function load(BaseEntity $entity)
    {
        $reflection = new ReflectionClass($entity);
        self::$instance->loadFromDB(
            self::getDocValue($reflection, 'database'),
            self::getDocValue($reflection, 'table'),
            $entity
        );
    }

    /**
     * This loads data into the given entity
     * @param string $scheme
     * @param string $table
     * @param int $aid
     * @return BaseEntity
     */
    public function loadInstance($class, $scheme, $table, $aid)
    {
        if (!isset($this->cache[$scheme][$table][$aid])) {
            $this->cache[$scheme][$table][$aid] = new $class($aid);
        }
        return $this->cache[$scheme][$table][$aid];
    }

    /**
     * This loads data into the given entity
     * @param string $class
     * @param int $aid
     * @return BaseEntity $entity
     */
    public static function provide($class, $aid)
    {
        $reflection = new ReflectionClass($class);
        return self::$instance->loadInstance(
                $class,
                self::getDocValue($reflection, 'database'),
                self::getDocValue($reflection, 'table'),
                $aid
        );
    }

    /**
     * This stores a given entity in the database
     * @param BaseEntity $entity
     * @return boolean|int
     */
    public static function store(BaseEntity $entity)
    {
        $reflection = new ReflectionClass($entity);
        $data = array();
        foreach ($reflection->getProperties() as $property) {
            /* @var $property ReflectionProperty */
            $methodName = 'get'.strtoupper($property->name{0}).substr($property->name, 1);
            if (
                !$property->isStatic() &&
                $property->getName() !== 'aid' &&
                $property->getName() !== 'entityInitialized' &&
                $reflection->hasMethod($methodName) &&
                $reflection->getMethod($methodName)->isPublic() &&
                !$reflection->getMethod($methodName)->isStatic()
            ) {
                $data[self::getDocValue($property, 'column')] = $reflection->getMethod($methodName)->invoke($entity);
            }
        }
        return self::$instance->writeToDB(
                self::getDocValue($reflection, 'database'),
                self::getDocValue($reflection, 'table'),
                $entity,
                $data
        );
    }

    /**
     *
     * @param ReflectionClass|ReflectionMethod $reflection
     * @param string $identifier
     * @return string
     */
    protected static function getDocValue($reflection, $identifier)
    {
        preg_match(
            "/@$identifier (.*?)\s/",
            $reflection->getDocComment(),
            $value
        );
        return $value[1];
    }
}
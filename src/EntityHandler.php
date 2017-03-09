<?php

namespace De\Idrinth\EntityGenerator;

use PDO;
use ReflectionClass;
use ReflectionMethod;

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
     */
    public static function init(PDO $database)
    {
        if (!self::$instance) {
            self::$instance = new self($database);
        }
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
        if(!$result){
            return;
        }
        $nameHandler = new EntityNameHandler();
        $object->entityInitialized = true;
        foreach($result->fetch(PDO::FETCH_ASSOC) as $column => $value) {
            $setter = 'set'.$nameHandler->toUpperCamelCase($column);
            if(method_exists($object, $setter)) {
                call_user_func(array($object, $setter),$value);
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
     */
    public function writeToDB($scheme, $table, BaseEntity $object, $data)
    {
        if ($object->getAid()) {
            $string  = array();
            $replace = array();
            foreach ($data as $key => $value) {
                $string[] = "`$key`=:$key";
                $replace[':'.$key] = $value;
            }
            $prep = $this->database->prepare("UPDATE `$scheme`.`$table` SET ".implode(',',
                    $string)." WHERE aid={$object->getAid()}");
            return $prep->execute($replace);
        }
        $replace = array();
        foreach ($data as $key => $value) {
            $replace[':'.$key] = $value;
        }
        $prep = $this->database->prepare("INSERT INTO `$scheme`.`$table` (`".implode("`,`",
                array_keys($data))."`) VALUES (".implode(",",
                array_keys($replace)).")");
        if ($prep->execute($replace)) {
            $id = $this->database->lastInsertId();
            $this->cache[$scheme][$table][$id] = $object;
            return $id;
        }
        return false;
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
     * @param int $id
     * @return BaseEntity
     */
    public function loadInstance($class, $scheme, $table, $id)
    {
        if(!isset($this->cache[$scheme][$table][$id])) {
            $this->cache[$scheme][$table][$id] = new $class($id);
        }
        return $this->cache[$scheme][$table][$id];
    }

    /**
     * This loads data into the given entity
     * @param string $class
     * @param int $id
     * @return BaseEntity $entity
     */
    public static function provide($class, $id)
    {
        $reflection = new ReflectionClass($class);
        return self::$instance->loadInstance(
            $class,
            self::getDocValue($reflection, 'database'),
            self::getDocValue($reflection, 'table'),
            $id
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
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            /* @var $method ReflectionMethod */
            if (!$method->isStatic() && $method->getName() === 'get' && $method->getName() !== 'getAid') {
                $data[self::getDocValue($method, 'column')] = $method->invoke($entity);
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
    protected static function getDocValue($reflection,$identifier) {
        preg_match("/@$identifier (.*?)\s/", $reflection->getDocComment(), $value);
        return $value[1];
    }
}
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
    protected $cache=array();

    /**
     *
     * @param PDO $database
     */
    protected static function init(PDO $database) {
        if(!self::$instance) {
            self::$instance = new self($database);
        }
    }

    /**
     *
     * @return EntityHandler
     */
    protected static function get() {
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
    public function loadFromDB($scheme,$table,BaseEntity $object) {
        if(!isset($this->cache[$scheme][$table][$object->getAid()])) {
            $object = $this->cache[$scheme][$table][$object->getAid()];
            return;
        }
        $result = $this->database->query("SELECT * FROM `$scheme`.`$table` WHERE aid={$object->getAid()}");
        $result->fetch(PDO::FETCH_INTO,$object);
        $this->cache[$scheme][$table][$object->getAid()] = $object;
    }

    /**
     *
     * @param string $scheme
     * @param string $table
     * @param BaseEntity $object
     * @param string[] $data
     */
    public function writeToDB($scheme,$table,BaseEntity $object,$data) {
        if($object->getAid()) {
            $string = array();
            $replace = array();
            foreach($data as $key => $value) {
                $string[]="`$key`=:$key";
                $replace[$key.':']=$value;
            }
            $prep = $this->database->prepare("UPDATE `$scheme`.`$table` SET ".implode(',',$string)." WHERE aid={$object->getAid()}");
            return $prep->execute($replace);
        }
        $prep = $this->database->prepare("INSERT INTO `$scheme`.`$table` (`".implode("`,`", array_keys($data))."`) VALUES (:".implode(",:", array_keys($data)).")");
        $replace = array();
        foreach($data as $key => $value) {
            $replace[$key.':']=$value;
        }
        if($prep->execute($replace)) {
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
    public static function load(BaseEntity $entity) {
        $reflection = new ReflectionClass($entity);
        preg_match("/@table (.*?)\n/", $reflection->getDocComment(),$table);
        preg_match("/@database (.*?)\n/", $reflection->getDocComment(),$scheme);
        self::$instance->loadFromDB(
            $scheme[1],
            $table[1],
            $entity
        );
    }

    /**
     * This stores a given entity in the database
     * @param BaseEntity $entity
     * @return boolean|int
     */
    public static function store(BaseEntity $entity) {
        $reflection = new ReflectionClass($entity);
        preg_match("/@table (.*?)\n/", $reflection->getDocComment(),$table);
        preg_match("/@database (.*?)\n/", $reflection->getDocComment(),$scheme);
        $data=array();
        foreach($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            /* @var $method ReflectionMethod */
            if(!$method->isStatic()&&$method->getName()==='get'&&$method->getName()!=='getAid') {
                preg_match("/column (.*?)\n/", $method->getDocComment(),$column);
                $data[$column[0]] = $method->invoke($entity);
            }
        }
        return self::$instance->writeToDB($scheme, $table, $entity, $data);
    }
}
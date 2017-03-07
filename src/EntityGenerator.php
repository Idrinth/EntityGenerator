<?php

namespace De\Idrinth\EntityGenerator;

use PDO;
use PDOStatement;
use Twig_Enviroment;
use Twig_Environment;
use Twig_Loader_Filesystem;
class EntityGenerator {
    /**
     *
     * @var string
     */
    protected $basePath;
    /**
     *
     * @var string
     */
    protected $namespace;
    /**
     *
     * @var Twig_Enviroment
     */
    protected $twig;
    /**
     *
     * @var PDOStatement
     */
    protected $getTables;
    /**
     *
     * @var PDOStatement
     */
    protected $getProperties;
    /**
     *
     * @var string
     */
    protected static $getTablesStatement = "SELECT TABLE_NAME AS name
FROM information_schema.`TABLES`
WHERE TABLE_SCHEMA=:schema";
    /**
     *
     * @var string
     */
    protected static $getPropertiesStatement = "SELECT c.COLUMN_NAME AS name,c.DATA_TYPE AS type,fk.REFERENCED_TABLE_NAME AS target,IF(c.EXTRA='auto_increment',1,0) as autoincrement
FROM information_schema.`COLUMNS` AS c
LEFT JOIN information_schema.`KEY_COLUMN_USAGE` AS fk
    ON c.TABLE_SCHEMA=fk.TABLE_SCHEMA
    AND c.TABLE_NAME=fk.TABLE_NAME
    AND c.COLUMN_NAME=fk.COLUMN_NAME
    AND c.TABLE_SCHEMA=fk.REFERENCED_TABLE_SCHEMA
WHERE c.TABLE_SCHEMA=:schema
    AND c.COLUMN_NAME != 'aid'
    AND c.TABLE_NAME=:table";
    /**
     *
     * @param PDO $database
     * @param string $basePath
     * @param string $namespace
     */
    public function __construct(PDO $database,$basePath,$namespace,array $sources = array()) {
        $this->basePath = $basePath;
        $this->namespace = trim($namespace,'\\');
        array_push($sources,__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates');
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem($sources));
        $this->twig->addExtension(new \De\Idrinth\EntityGenerator\EntityTwig());
        $this->getTables = $database->prepare(self::$getTablesStatement);
        $this->getProperties = $database->prepare(self::$getPropertiesStatement);
    }
    /**
     *
     * @param string $schemas
     * @return string[]
     */
    public function run(array $schemas) {
        foreach($schemas as $schema) {
            $this->getTables->execute(array(':schema' => $schema));
            foreach($this->getTables->fetchAll(PDO::FETCH_OBJ) as $table) {
                $this->buildClass($table->name,$schema);
            }
            $this->getTables->closeCursor();
        }
    }
    /**
     *
     * @param string $table
     * @param string $schema
     * @return \De\Idrinth\EntityGenerator\Property[]
     */
    protected function getProperties($table,$schema) {
        $this->getProperties->execute(array(':schema' => $schema,':table' => $table));
        $properties = $this->getProperties->fetchAll(PDO::FETCH_CLASS,'De\Idrinth\EntityGenerator\Property');
        $this->getProperties->closeCursor();
        return $properties;
    }
    /**
     *
     * @param type $table
     * @param string $schema
     * @return type
     */
    protected function buildClass($table,$schema) {
        $class = EntityTwig::toUpperCamelCase($table);
        $path = str_replace('{{schema}}',EntityTwig::toUpperCamelCase($schema),$this->basePath) . '/Entity/';
        if(!file_exists($path)) {
            mkdir($path,0777,true);
        }
        file_put_contents(
                $path . $class . '.php',$this->twig->resolveTemplate(array('custom.twig','base.twig'))->render(
                        array(
                            'table' => $table,
                            'schema' => $schema,
                            'namespace' => $this->namespace,
                            'properties' => $this->getProperties($table,$schema)
        )));
    }
}
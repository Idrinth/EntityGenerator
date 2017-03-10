<?php

namespace De\Idrinth\EntityGenerator;

use PDO;
use PDOStatement;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_Template;

class EntityGenerator
{
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
     * @var Twig_Template
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
     * @var EntityTwig
     */
    protected $formatter;

    /**
     *
     * @var string
     */
    protected static $tablesStatement = "SELECT TABLE_NAME AS name
FROM information_schema.`TABLES`
WHERE TABLE_SCHEMA=:schema";

    /**
     *
     * @var string
     */
    protected static $propertiesStatement = "
SELECT c.COLUMN_NAME AS name,
    c.DATA_TYPE AS type,
    fk.REFERENCED_TABLE_NAME AS target,
    IF(c.EXTRA='auto_increment',1,0) as autoincrement
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
     * @var string[]
     */
    protected static $templates = array('custom.twig', 'base.twig');

    /**
     *
     * @param PDO $database
     * @param string $basePath
     * @param string $namespace
     * @param Twig_Environment $twig
     */
    public function __construct(
        PDO $database,
        $basePath,
        $namespace,
        Twig_Environment $twig = null
    ) {
        $this->basePath = $basePath;
        $this->namespace = trim($namespace, '\\');
        $this->twig = $this->getTwig($twig)->resolveTemplate(self::$templates);
        $this->getTables  = $database->prepare(self::$tablesStatement);
        $this->getProperties = $database->prepare(self::$propertiesStatement);
        $this->formatter = new EntityNameHandler();
    }

    /**
     *
     * @param Twig_Environment $twig
     * @return Twig_Environment
     */
    protected function getTwig(Twig_Environment $twig = null)
    {
        if (!$twig) {
            $folder = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates';
            $loader = new Twig_Loader_Filesystem(array($folder));
            $twig   = new Twig_Environment($loader);
        }
        $twig->addExtension(new EntityTwig());
        return $twig;
    }

    /**
     *
     * @param string[] $schemas
     * @return boolean
     */
    public function run(array $schemas)
    {
        $status = true;
        foreach ($schemas as $schema) {
            $this->getTables->execute(array(':schema' => $schema));
            foreach ($this->getTables->fetchAll(PDO::FETCH_OBJ) as $table) {
                $status = $status && $this->buildClass($table->name, $schema);
            }
            $this->getTables->closeCursor();
        }
        return $status;
    }

    /**
     *
     * @param string $table
     * @param string $schema
     * @return Property[]
     */
    protected function getTableProperties($table, $schema)
    {
        $this->getProperties->execute(array(':schema' => $schema, ':table' => $table));
        $properties = $this->getProperties->fetchAll(
            PDO::FETCH_CLASS,
            'De\Idrinth\EntityGenerator\Property'
        );
        $this->getProperties->closeCursor();
        return $properties;
    }

    /**
     *
     * @param type $table
     * @param string $schema
     * @return boolean
     */
    protected function buildClass($table, $schema)
    {
        $class = $this->formatter->toUpperCamelCase($table);
        $path  = str_replace(
                '{{schema}}',
                $this->formatter->toUpperCamelCase($schema),
                $this->basePath
            ).DIRECTORY_SEPARATOR.'Entity';
        if(!$this->createDirectoryIfNotExists($path)) {
            return false;
        }
        if (!$this->write(
                $path.DIRECTORY_SEPARATOR.$class.'.php',
                array(
                'table' => $table,
                'schema' => $schema,
                'namespace' => $this->namespace,
                'properties' => $this->getTableProperties($table, $schema)
                )
            )) {
            return false;
        }
        return true;
    }

    /**
     *
     * @param string $path
     * @return boolean
     */
    protected function createDirectoryIfNotExists($path)
    {
        if (is_dir($path)) {
            return true;
        }
        return mkdir($path, 0777, true) && is_dir($path);
    }

    /**
     *
     * @param string $path
     * @param array $data
     * @return boolean
     */
    protected function write($path, $data)
    {
        return (bool) file_put_contents($path, $this->twig->render($data));
    }
}
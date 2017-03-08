<?php

namespace De\Idrinth\EntityGenerator;

use PDO;
use PDOStatement;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_Template;
use UnderflowException;

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
        $this->getTables = $database->prepare(self::$getTablesStatement);
        $this->getProperties = $database->prepare(self::$getPropertiesStatement);
    }

    /**
     *
     * @param Twig_Environment $twig
     * @return Twig_Environment
     */
    protected function getTwig(Twig_Environment $twig = null)
    {
        if (!$twig)
        {
            $folder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates';
            $loader = new Twig_Loader_Filesystem(array($folder));
            $twig = new Twig_Environment($loader);
        }
        $twig->addExtension(new EntityTwig());
        return $twig;
    }

    /**
     *
     * @param string[] $schemas
     * @return void
     */
    public function run(array $schemas)
    {
        foreach ($schemas as $schema)
        {
            $this->getTables->execute(array(':schema' => $schema));
            foreach ($this->getTables->fetchAll(PDO::FETCH_OBJ) as $table)
            {
                $this->buildClass($table->name, $schema);
            }
            $this->getTables->closeCursor();
        }
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
     * @return type
     * @throws UnderflowException
     */
    protected function buildClass($table, $schema)
    {
        $class = EntityTwig::toUpperCamelCase($table);
        $path = str_replace(
                '{{schema}}',
                EntityTwig::toUpperCamelCase($schema),
                $this->basePath
            ) . '/Entity/';
        $this->createDirectoryIfNotExists($path);
        if(!$this->write(
                $path . $class . '.php',
                array(
                    'table' => $table,
                    'schema' => $schema,
                    'namespace' => $this->namespace,
                    'properties' => $this->getTableProperties($table, $schema)
                )
            )) {
            throw new UnderflowException($path . $class . '.php was not writeable.');
        }
    }

    /**
     *
     * @param string $path
     * @return void
     * @throws UnderflowException
     */
    protected function createDirectoryIfNotExists($path) {
        if (file_exists($path))
        {
            return true;
        }
        if(mkdir($path, 0777, true)) {
            sleep(1);
            return file_exists($path);
        }
        throw new UnderflowException($path . ' could\'t be created.');
    }

    /**
     *
     * @param string $path
     * @param array $data
     * @return boolean
     */
    protected function write($path,$data) {
        return file_put_contents($path, $this->twig->render($data));
    }

}
